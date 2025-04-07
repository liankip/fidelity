<?php

namespace App\Http\Livewire\WorkOrder;

use App\Models\Inventory;
use App\Models\InventoryDetail;
use App\Models\InventoryHistory;
use App\Models\Item;
use App\Models\Sku;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

use Livewire\Component;

class MonitoringWorkOrder extends Component
{
    public $workOrder;
    public $productDetails = [];
    public $search;
    public $sortBy = 'item_name'; // Default filter
    public $allItemsReady = true;


    public function mount(\App\Models\WorkOrder $work)
    {
        $this->workOrder = $work;
        $this->updateProductDetails();
    }

    public function updatedSearch()
    {
        $this->updateProductDetails();
    }

    public function updatedSortBy()
    {
        $this->updateProductDetails();
    }

    public function updateProductDetails()
    {
        $productIds = collect(json_decode($this->workOrder->product))->pluck('product')->toArray();

        $this->productDetails = Sku::whereIn('id', $productIds)
            ->get()
            ->map(function ($sku) {
                $qty = collect(json_decode($this->workOrder->product))->firstWhere('product', $sku->id)->qty;

                $boqDetails = collect($sku->boq)->map(function ($boq) use ($qty) {
                    $query = Item::where('id', $boq[0]);

                    if ($this->search) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    }

                    $item = $query->first();

                    if (!$item) {
                        return null;
                    }

                    return [
                        'item_id' => $boq[0],
                        'item_name' => $item->name,
                        'unit' => $boq[1],
                        'qty' => $boq[3] * $qty,
                        'price' => $boq[2],
                        'shipping' => $boq[4],
                        'total' => $boq[2] * $boq[3] * $qty,
                    ];
                })->filter();

                return [
                    'product_name' => $sku->name,
                    'boqDetails' => $boqDetails,
                    'qty' => $qty

                ];
            })->filter(function ($detail) {
                return $detail['boqDetails']->isNotEmpty();
            });

        // Apply sorting
        $this->productDetails = $this->productDetails->map(function ($detail) {
            $sortedBoqDetails = $detail['boqDetails']->sortBy($this->sortBy, SORT_REGULAR, false);
            $detail['boqDetails'] = $sortedBoqDetails->values();
            return $detail;
        });
    }

    public function render()
    {
        $this->checkItemStock();
        $workOrderStatus = $this->workOrder->status;
        return view('livewire.work-order.monitoring-work-order', [
            'productDetails' => $this->productDetails,
            'workOrderStatus' => $workOrderStatus
        ]);
    }

    public function checkItemStock()
    {
        // Collect all item IDs from product details
        $itemIds = collect($this->productDetails)
            ->flatMap(fn($product) => collect($product['boqDetails'])->pluck('item_id'))
            ->unique();

        // Fetch inventory details in one query
        $inventoryDetails = InventoryDetail::with('inventory')
            ->whereNotNull('warehouse_type')
            ->whereHas('inventory', fn($query) => $query->whereIn('item_id', $itemIds))
            ->get();

        // Create a lookup for stock levels based on item_id
        $stockLookup = $inventoryDetails
        ->groupBy('inventory_id') 
        ->mapWithKeys(function ($group) {
            return [$group->first()->inventory->item_id => $group->sum('stock')];
        });

        // Map over productDetails to update stock availability
        $this->productDetails = collect($this->productDetails)->map(function ($product) use ($stockLookup) {
            $product['boqDetails'] = collect($product['boqDetails'])->map(function ($detail) use ($stockLookup) {
                $detail['available_stock'] = $stockLookup[$detail['item_id']] ?? 0;

                // Check if all items have sufficient stock
                if ($detail['available_stock'] < $detail['qty']) {
                    $this->allItemsReady = false;
                }

                return $detail;
            });

            return $product;
        });
    }

    public function startWorkOrder()
    {
        DB::beginTransaction();

        try {
            $boqItems = collect($this->productDetails)->flatMap(fn($productDetail) => collect($productDetail['boqDetails'])->pluck('item_id'))->unique();

            $inventoryDetails = InventoryDetail::whereIn('warehouse_type', ['RAW MATERIALS', 'Gudang Medan'])
                ->whereHas('inventory', fn($query) => $query->whereIn('item_id', $boqItems))
                ->with('inventory')
                ->orderByRaw("FIELD(warehouse_type, 'RAW MATERIALS', 'Gudang Medan')")
                ->get()
                ->groupBy(fn($detail) => optional($detail->inventory)->item_id);

            $historyRecords = [];
            foreach ($this->productDetails as $productDetail) {
                foreach ($productDetail['boqDetails'] as $boqDetail) {
                    $itemId = $boqDetail['item_id'];
                    $remainingQty = $boqDetail['qty'];

                    if (!isset($inventoryDetails[$itemId])) {
                        continue;
                    }

                    foreach ($inventoryDetails[$itemId] as $inventoryDetail) {
                        if ($remainingQty <= 0) {
                            break;
                        }

                        $deductQty = min($remainingQty, $inventoryDetail->stock);
                        if ($deductQty <= 0) {
                            continue;
                        }

                        $historyRecords[] = [
                            'inventory_detail_id' => $inventoryDetail->id,
                            'stock_before' => $inventoryDetail->stock,
                            'stock_after' => max(0, $inventoryDetail->stock - $deductQty),
                            'stock_change' => $deductQty,
                            'user_id' => auth()->id(),
                            'type' => 'OUT',
                            'work_order_id' => $this->workOrder->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];

                        // Update stock
                        $inventoryDetail->decrement('stock', $deductQty);

                        $remainingQty -= $deductQty;
                    }
                }
            }

            InventoryHistory::insert($historyRecords);
            WorkOrder::where('id', $this->workOrder->id)->update(['status' => 'STARTED']);

            DB::commit();
            return redirect()->route('work-order.monitoring', $this->workOrder->id)->with('success', 'Work Order has been started');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function finishWorkOrder()
    {
        DB::beginTransaction();
        try {
            WorkOrder::where('id', $this->workOrder->id)->update(['status' => 'FINISHED']);

            $inventoryData = Inventory::with('details')->get();

            foreach ($this->productDetails as $productDetail) {
                $checkExisting = Item::firstOrCreate(
                    ['name' => $productDetail['product_name']],
                    [
                        'item_code' => 'NA',
                        'type' => 'NA',
                        'unit' => 'NA',
                        'created_by' => auth()->user()->id,
                        'image' => 'images/no_image.png',
                        'notes_k3' => 'NA',
                    ]
                );

                $filteredInventory = $inventoryData->filter(function ($inventory) {
                    return $inventory->details->contains('project_id', null);
                });

                $itemInventory = $filteredInventory->firstWhere('item_id', $checkExisting->id);

                $historyData = [];

                if ($itemInventory) {

                    $itemInventory->update([
                        'stock' => $itemInventory->stock + $productDetail['qty']
                    ]);

                    $itemInventory->details()->update([
                        'stock' => $itemInventory->details()->first()->stock + $productDetail['qty']
                    ]);

                    $historyData = [
                        'inventory_detail_id' => $itemInventory->details()->first()->id,
                        'stock_before' => $itemInventory->stock,
                        'stock_after' => $itemInventory->stock + $productDetail['qty'],
                        'stock_change' => $productDetail['qty'],
                        'user_id' => auth()->id(),
                        'type' => 'IN',
                        'work_order_id' => $this->workOrder->id
                    ];
                } else {
                    $inventory = Inventory::create([
                        'item_id' => $checkExisting->id,
                        'stock' => $productDetail['qty'],
                    ]);

                    $inventory->details()->create([
                        'stock' => $productDetail['qty'],
                        'warehouse_type' => 'READY GOODS'
                    ]);

                    $historyData = [
                        'inventory_detail_id' => $inventory->details()->first()->id,
                        'stock_before' => 0,
                        'stock_after' => $productDetail['qty'],
                        'stock_change' => $productDetail['qty'],
                        'user_id' => auth()->id(),
                        'type' => 'IN',
                        'work_order_id' => $this->workOrder->id
                    ];
                }
                InventoryHistory::create($historyData);
            }


            DB::commit();

            return redirect()->route('work-order.monitoring', $this->workOrder->id)->with('success', 'Work Order has been finished');
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
