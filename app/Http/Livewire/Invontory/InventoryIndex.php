<?php

namespace App\Http\Livewire\Invontory;

use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\Inventory;
use App\Models\InventoryDetail;
use App\Models\InventoryHistory;
use App\Models\Item;
use App\Models\Project;
use Livewire\WithPagination;

class InventoryIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search, $projectmodel, $projects;
    public $warehouseModel;
    public $items=[];
    public $outQty, $keteranganModel, $actualDate;

    protected $listeners = [
        'refreshComponent' => '$refresh',
        'refreshProject' => 'getProject',
    ];
    
    public const MANUFACTURE_CONSTANT = 'Manufacture';
    public function mount()
    {
        $this->items = [['item_id' => '', 'qty' => 1]];
    }

    public function addItem()
    {
        $this->items = [...$this->items, ['item_id' => '', 'qty' => 1]];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items); // Reindex array
    }

    public function render()
    {
        $this->getProject();
        $inventories = $this->getData();

        $groupedInventories = $inventories->groupBy(function ($item) {
            return $item->project_name . ' _ ' . $item->inventory_project_id;
        });

        $masterDataItems = Item::all();

        return view('livewire.inventory.inventory-index', [
            'inventories' => $inventories,
            'groupedInventories' => $groupedInventories,
            'itemsData' => $masterDataItems,
            'CONSTANT' => self::MANUFACTURE_CONSTANT
        ]);
    }


    public function getProject()
    {
        $this->projects = Project::all()->map(function ($project) {
            return(object) [
                'id' => $project->id,
                'name' => $project->name,
                'company_name' => $project->company_name
            ];
        });

        $warehouseData = $this->inventoryWithoutProject();

        $this->projects =collect($this->projects)->merge($warehouseData);
    }


    public function edit($id)
    {
        $this->emit('openModal', [
            'name' => 'inventory.update-stock-modal',
            'arguments' => [
                'inventoryId' => $id,
            ]
        ]);
    }

    public function generate()
    {
        DB::table('inventories')->delete();

        $purchase_orders = PurchaseOrder::where('status_barang', 'arrived')->get();
        foreach ($purchase_orders as $purchase_order) {
            foreach ($purchase_order->podetail as $detail) {
                $inventory = Inventory::where('item_id', $detail->item_id)->first();
                if ($inventory) {
                    $inventory->update([
                        'stock' => $inventory->stock + $detail->qty
                    ]);
                    if ($inventory->details()->where('project_id', $purchase_order->project_id)->exists()) {
                        $inventory->details()->where('project_id', $purchase_order->project_id)->update([
                            'stock' => $inventory->details()->where('project_id', $purchase_order->project_id)->first()->stock + $detail->qty
                        ]);
                    } else {
                        $inventory->details()->create([
                            'project_id' => $purchase_order->project_id,
                            'stock' => $detail->qty
                        ]);
                    }
                } else {
                    $inventory = Inventory::create([
                        'item_id' => $detail->item_id,
                        'stock' => $detail->qty,
                    ]);

                    $inventory->details()->create([
                        'project_id' => $purchase_order->project_id,
                        'stock' => $detail->qty
                    ]);
                }
            }
        }

        return redirect()->route('inventory.index')->with('success', 'Inventory has been generated');
    }

    public function updateStock()
    {
        $this->validate([
            'stock' => 'required|numeric|min:5'
        ]);

        // $this->selectedInventory->histories()->create([
        //     'type' => 'OUT',
        //     'stock_before' => $this->selectedInventory->stock,
        //     'stock_after' => $this->stock,
        //     'stock_change' => $this->selectedInventory->stock - $this->stock,
        //     'user_id' => auth()->id(),
        //     'notes' => $this->notes
        // ]);

        // $this->selectedInventory->update([
        //     'stock' => $this->stock
        // ]);

        return redirect()->route('inventory.index')->with('success', 'Stock has been updated');
    }

    public function getData()
    {
        $perPage = 15;

        if ($this->projectmodel) {
            $query = $this->buildQueryForProjectModel($this->projectmodel);
        } else {
            $query = $this->buildQueryForAllProjects();
        }

        return $query->paginate($perPage);
    }

    /**
     * Builds query based on project model type
     */
    private function buildQueryForProjectModel($projectmodel)
    {
        $sliceProjectName = explode(self::MANUFACTURE_CONSTANT . ': ', $projectmodel);
        
        
        if (count($sliceProjectName) > 1) {
            return $this->buildWarehouseQuery(
                $sliceProjectName[1], 
                '"' . self::MANUFACTURE_CONSTANT . ': ' . $sliceProjectName[1] . '"'
            );
        } elseif ($projectmodel == 'Gudang') {
            return $this->buildWarehouseQuery(null, '"Gudang"');
        } elseif(!intval($projectmodel)) {
            return $this->buildWarehouseQuery($sliceProjectName[0], $projectmodel);
        } else {
            return $this->buildProjectQuery($projectmodel);
        }
    }

    /**
     * Builds query for a specific warehouse type
     */
    private function buildWarehouseQuery($warehouseType, $projectName)
    {
        $query = Inventory::join('items', 'inventories.item_id', '=', 'items.id')
            ->leftJoin('inventory_details', 'inventories.id', '=', 'inventory_details.inventory_id')
            ->leftJoin('projects', 'inventory_details.project_id', '=', 'projects.id')
            ->whereNull('inventory_details.project_id')
            ->whereNotNull('inventory_details.id')
            ->whereNull('inventories.project_id')
            ->where(function ($query) use ($warehouseType) {
                if ($warehouseType) {
                    $query->where('inventory_details.warehouse_type', $warehouseType);
                } else {
                    $query->whereNull('inventory_details.warehouse_type');
                }
            })
            ->where(function ($query) {
                $query->where('items.name', 'like', '%' . $this->search . '%')
                    ->orWhere('items.item_code', 'like', '%' . $this->search . '%');
            })
            ->select(
                'inventories.*',
                'inventory_details.id as detail_id',
                DB::raw('NULL as inventory_project_id'),
                'inventory_details.stock as detail_stock',
                'items.name as item_name',
                'items.item_code',
                DB::raw("'" . $projectName . "' as project_name")
            );

        return $query;
    }


    /**
     * Builds query for projects
     */
    private function buildProjectQuery($projectId)
    {

        return Inventory::join('items', 'inventories.item_id', '=', 'items.id')
            ->join('inventory_details', 'inventories.id', '=', 'inventory_details.inventory_id')
            ->join('projects', 'inventory_details.project_id', '=', 'projects.id')
            ->where(function ($query) use ($projectId) {
                if ($projectId != '*') {
                    $query->where('projects.id', $projectId);
                } else {
                    $query->whereNotNull('projects.id');
                }
            })
            ->where(function ($query) {
                $query->where('items.name', 'like', '%' . $this->search . '%')
                    ->orWhere('items.item_code', 'like', '%' . $this->search . '%');
            })
            ->orderBy('projects.name')
            ->orderBy('items.name')
            ->select(
                'inventories.*',
                'inventory_details.id as detail_id',
                'inventory_details.project_id as inventory_project_id',
                'inventory_details.stock as detail_stock',
                'items.name as item_name',
                'items.item_code',
                'projects.name as project_name'
            )
            ->distinct();
    }

    /**
     * Builds query for fetching all projects and warehouse data
     */
    private function buildQueryForAllProjects()
    {
        $queryProjects = $this->buildProjectQuery('*'); // Fetch all projects

        $queryGudang = $this->buildWarehouseQuery('Gudang Medan', '"Gudang Medan"');
        $queryRawMaterials = $this->buildWarehouseQuery('RAW MATERIALS', '"' . self::MANUFACTURE_CONSTANT . ': Raw Materials"');
        $queryReadyGoods = $this->buildWarehouseQuery('READY GOODS', '"' . self::MANUFACTURE_CONSTANT . ': Ready Goods"');

        return $queryProjects->union($queryGudang)->union($queryRawMaterials)->union($queryReadyGoods);
    }

    public function inventoryWithoutProject()
    {
        $inventoryDetailData = InventoryDetail::whereNull('project_id')->pluck('warehouse_type')->unique();
        
        return $inventoryDetailData->map(function ($type) {
            $name = '';
            
            if($type == null) {
                $name = 'Gudang';
            } elseif($type == 'RAW MATERIALS' || $type == 'READY GOODS') {
                $name = self::MANUFACTURE_CONSTANT . ': ' . $type;
            } else {
                $name = $type;
            }

            return(object) [
                'id' => null,
                'name' => $name
            ];
        });
    }

    public function saveWarehouse()
    {
        $this->validate([
            'warehouseModel' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (InventoryDetail::where('warehouse_type', $value)->exists()) {
                        $fail('Warehouse name has already been taken.');
                    }
                }
            ],
        ]);

        DB::beginTransaction();

        try {
            InventoryDetail::create([
                'warehouse_type' => $this->warehouseModel,
                'stock' => 0
            ]);

            DB::commit();
            return redirect()->route('inventory.index')->with('success', 'Warehouse has been saved');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function saveItems()
    {
        DB::beginTransaction();

        try {
            // Prepare item quantities for quick lookup
            $itemQuantities = collect($this->items)->pluck('qty', 'item_id')->toArray();
            $itemsId = array_keys($itemQuantities);

            // Fetch existing inventory items
            $inventoryData = Inventory::whereIn('item_id', $itemsId)->with('details')->get();
            $existingItemIds = $inventoryData->pluck('item_id')->toArray();

            // Identify new items
            $newItems = array_diff($itemsId, $existingItemIds);

            // Remove empty warehouse entries
            InventoryDetail::whereNull(['inventory_id', 'project_id'])
                ->where('warehouse_type', $this->projectmodel)
                ->delete();
            
            $conditionalWarehouseType = null;
            $explodeProjectModel = explode(self::MANUFACTURE_CONSTANT . ': ', $this->projectmodel);
            if($this->projectmodel !== 'Gudang' && count($explodeProjectModel) > 1) {
                $conditionalWarehouseType = explode(self::MANUFACTURE_CONSTANT . ': ', $this->projectmodel)[1];
            } elseif ($this->projectmodel === 'Gudang') {
                $conditionalWarehouseType = null;
            } else {
                $conditionalWarehouseType = $this->projectmodel;
            }

            // Process existing inventory items
            foreach ($inventoryData as $inventory) {
                $itemId = $inventory->item_id;
                $qty = $itemQuantities[$itemId];

                // Update inventory stock
                $inventory->increment('stock', $qty);

                // Find or create inventory detail

                $inventoryDetail = $inventory->details()
                    ->whereNull('project_id')
                    ->where('warehouse_type', $conditionalWarehouseType)
                    ->first();

                if ($inventoryDetail) {
                    $stockBefore = $inventoryDetail->stock;
                    $inventoryDetail->increment('stock', $qty);
                } else {
                    $inventoryDetail = InventoryDetail::create([
                        'inventory_id' => $inventory->id,
                        'warehouse_type' => $conditionalWarehouseType,
                        'stock' => $qty
                    ]);
                    $stockBefore = 0;
                }

                // Create inventory history
                InventoryHistory::create([
                    'inventory_detail_id' => $inventoryDetail->id,
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockBefore + $qty,
                    'stock_change' => $qty,
                    'user_id' => auth()->id()
                ]);
            }

            // Process new items
            foreach ($newItems as $itemId) {
                $qty = $itemQuantities[$itemId];

                $inventory = Inventory::create([
                    'item_id' => $itemId,
                    'stock' => $qty
                ]);

                $inventoryDetail = InventoryDetail::create([
                    'inventory_id' => $inventory->id,
                    'warehouse_type' => $conditionalWarehouseType,
                    'stock' => $qty
                ]);

                InventoryHistory::create([
                    'inventory_detail_id' => $inventoryDetail->id,
                    'stock_before' => 0,
                    'stock_after' => $qty,
                    'stock_change' => $qty,
                    'user_id' => auth()->id()
                ]);
            }

            DB::commit();
            $this->reset(['items']); // Reset all properties in the component
            $this->dispatchBrowserEvent('close-modal'); // Close modal using JavaScript
            $this->emitSelf('refreshComponent'); // Refresh the component itself
            session()->flash('success', 'Inventory has been updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public function saveInventoryOut(InventoryDetail $inventoryDetailParam, $remainingStockParam)
    {
        $this->validate([
            'outQty' => 'required|numeric|min:1|max:' . $remainingStockParam
        ], [
            'outQty.required' => 'Quantity is required.',
            'outQty.numeric' => 'Quantity must be a number.',
            'outQty.min' => 'Quantity must be at least 1.',
            'outQty.max' => 'Quantity cannot exceed ' . $remainingStockParam . '.',
        ]);

        DB::beginTransaction();
        try {
            $inventoryDetail = $inventoryDetailParam;



            $historyData = [
                'inventory_detail_id' => $inventoryDetail->id,
                'stock_before' => $inventoryDetail->stock,
                'stock_after' => $inventoryDetail->stock - $this->outQty,
                'stock_change' => $this->outQty,
                'type' => 'OUT',
                'notes' => $this->keteranganModel,
                'actual_date' => $this->actualDate,
                'user_id' => auth()->id()
            ];

            $updateInventoryStock = $inventoryDetail->inventory->decrement('stock', $this->outQty);
            $updateDetailStock = $inventoryDetail->decrement('stock', $this->outQty);
            InventoryHistory::create($historyData);
        
            DB::commit();
            $this->reset(['outQty', 'keteranganModel', 'actualDate']); // Reset all properties in the component
            $this->dispatchBrowserEvent('close-modal'); // Close modal using JavaScript
            $this->emitSelf('refreshComponent'); // Refresh the component itself
            session()->flash('success', 'Inventory has been updated.');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function updatedProjectModel()
    {
        $this->items = [['item_id' => '', 'qty' => 1]];
    }
}
