<?php

namespace App\Http\Livewire\Invontory;

use App\Models\InventoryDetail;
use App\Models\InventoryOut;
use App\Models\InventoryOutEditHistoryModel;
use App\Models\Project;
use App\Models\PurchaseRequest;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class DraftItem extends Component
{
    use WithFileUploads, WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $projects, $users;

    public $projectmodel, $detailItem, $out, $item_pic, $new_item_pic, $user_model, $desc, $userModel2, $publicItems;
    public $search;
    private $items;

    public $itemNote;
    public $notes = [];
    public $itemRowspan;
    public $partofRowspan;

    // modal
    public $selectedItem;
    public $selectedItemPart;
    public $draftStatus = false;
    public $halfStatus = false;
    public $dateOut;
    public $selectedItemHistory;
    public $selectedItemHistoryPart;
    public $selectedInventoryOut;

    // protected $rules = [
    //     'out' => 'required',
    //     'user_model' => 'required',
    //     'desc' => 'required',
    // ];

    // protected function rules()
    // {
    //     return [
    //         'out' => ['required', 'integer', 'min:1', 'max:' . $this->item->stock],
    //         'user_model' => 'required',
    //         'desc' => 'required',
    //     ];
    // }

    protected $listeners = [
        'refresh' => '$refresh'
    ];

    public function addNoteField($itemId)
    {
        $this->notes[$itemId] = '';
    }

    public function saveNote($itemId)
    {
        // Save the note to the database or perform any necessary action
        $note = $this->notes[$itemId];

        // For example, you can save it like this
        InventoryDetail::where('id', $itemId)->update(['note' => $note]);

        // After saving, you can remove the note from the array
        unset($this->notes[$itemId]);
    }

    public function cancelNote($itemId)
    {
        unset($this->notes[$itemId]);
    }

    public function editNote($itemId)
    {
        $item = $this->publicItems->firstWhere('id', $itemId);
        if ($item) {
            $this->notes[$itemId] = $item['note'] ?? '';
        }
    }


    public function inventoryHistory(InventoryDetail $item, $paramPart)
    {

        $this->selectedItemHistory = $item->id;
        $this->selectedItemHistoryPart = $paramPart;
        $this->detailItem = InventoryOut::where('inventory_detail_id', $item->id)->where('out', '!=', null)->where('out', '!=', 0)->where('partof', $this->selectedItemHistoryPart)->orderBy('created_at', 'desc')->get();
        // dd($this->detailItem);
    }

    public function editInventory(InventoryOut $item)
    {
        $this->selectedInventoryOut = $item->id;
        $this->out = $item->out;
        $this->user_model = $item->user_id;
        $this->desc = $item->desc;
    }

    public function cancelEditInventory(InventoryOut $item)
    {
        $this->reset('selectedInventoryOut', 'item_pic', 'out', 'user_model', 'desc', 'dateOut');
    }

    public function saveEditInventory(InventoryOut $item)
    {
        $existing_stock = $item->inventoryDetail->stock + $item->out;
        $this->validate([
            'out' => ['required', 'numeric', 'min:0.01', 'max:' . $existing_stock],
            'user_model' => 'required',
            'desc' => 'required',
        ]);

        $prev_out_qty = $item->out;
        $prev_user_id = $item->user_id;
        $prev_desc = $item->desc;

        if ($this->new_item_pic) {
            $item->update([
                'item_pic' => $this->new_item_pic->store('inventory_out', 'public'),
                'out' => $this->out,
                'user_id' => $this->user_model,
                'desc' => $this->desc,
                'date_out' => $this->dateOut

            ]);
        } else {
            $item->update([
                'out' => $this->out,
                'user_id' => $this->user_model,
                'desc' => $this->desc,
            ]);
        }
        $item->inventoryDetail->update([
            'stock' => $existing_stock - $this->out
        ]);

        $item->inventoryDetail->inventory->update([
            'stock' => $item->inventoryDetail->inventory->details->sum('stock')
        ]);

        InventoryOutEditHistoryModel::create([
            'inventory_out_id' => $item->id,
            'prev_out_qty' => $prev_out_qty,
            'new_out_qty' => $this->out,
            'prev_user_id' => $prev_user_id,
            'new_user_id' => $this->user_model,
            'prev_desc' => $prev_desc,
            'new_desc' => $this->desc,
            'edited_by' => auth()->user()->id
        ]);
    

        $this->emitSelf('refresh');
        $this->reset('selectedInventoryOut', 'item_pic', 'out', 'user_model', 'desc');
    }

    public function inventoryOutModal(InventoryDetail $item, $partofParam)
    {
        $this->selectedItem = $item->id;
        $this->detailItem = $item;
        $this->selectedItemPart = $partofParam;
    }

    public function closeModal()
    {
        $this->reset('selectedItem', 'detailItem', 'out', 'item_pic', 'user_model', 'desc', 'dateOut', 'selectedItemHistory');
    }

    public function save(InventoryDetail $item, $partofParam)
    {
        $rules = [
            'user_model' => 'required',
            'desc' => 'required',
            'out' => ['required', 'numeric', 'min:0.01', 'max:' . $item->stock],
        ];


        $this->validate($rules);

        DB::beginTransaction();
        try {
            //code...
            $item->update([
                'stock' => $item->stock - $this->out
            ]);

            if ($this->item_pic) {
                InventoryOut::create([
                    'inventory_detail_id' => $item->id,
                    'project_id' => $item->project->id,
                    'partof' => $partofParam,
                    'item_pic' => $this->item_pic->store('inventory_out', 'public'),
                    'out' => $this->out,
                    'is_partial' => $this->halfStatus ? 'true' : 'false',
                    'user_id' => $this->user_model,
                    'desc' => $this->desc,
                    'date_out' => $this->dateOut
                ]);
            } else {
                InventoryOut::create([
                    'inventory_detail_id' => $item->id,
                    'project_id' => $item->project->id,
                    'partof' => $partofParam,
                    'out' => $this->out,
                    'is_partial' => $this->halfStatus ? 'true' : 'false',
                    'user_id' => $this->user_model,
                    'desc' => $this->desc,
                    'date_out' => $this->dateOut
                ]);
            }


            $item->inventory->update([
                'stock' => $item->inventory->details->sum('stock')
            ]);

            DB::commit();
            $this->reset('selectedItem', 'detailItem', 'out', 'item_pic', 'user_model', 'desc', 'dateOut');

            // return redirect(route('inventory.out'))->with('success', 'Berhasil mengurangi inventory ' . $item->inventory->item->name . ' pada project ' . $item->project->name);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public function render()
    {
        $this->projects = Project::where("status", "On going")->get();
        $this->users = User::where('active', 1)->get();


        
            $currentUserId = auth()->id();
            $this->items = InventoryDetail::join('inventories', 'inventory_details.inventory_id', '=', 'inventories.id')
                ->join('items', 'inventories.item_id', '=', 'items.id')
                ->join('inventory_outs', function ($join) use ($currentUserId) {
                    $join->on('inventory_details.id', '=', 'inventory_outs.inventory_detail_id')
                        ->where('inventory_outs.owner_id', '=', $currentUserId)
                        ->where('inventory_outs.reserved', '=', 'true');
                })
                ->leftJoin('purchase_requests', 'purchase_requests.project_id', '=', 'inventory_details.project_id')
                ->where(function ($query) {
                    $query->where('items.name', 'like', '%' . $this->search . '%')
                        ->orWhere('purchase_requests.partof', 'like', '%' . $this->search . '%');
                })
                ->orderBy('items.name', 'asc')
                ->select('inventory_details.*', 'items.name as item_name', 'inventory_outs.id as inventory_out_id', 'inventory_outs.partof as inventory_out_partof')
                ->with(['inventory.item', 'inventory_outs'])
                ->distinct()
                ->get();


        // Merge PurchaseRequest records with InventoryDetail records
        $mergedItems = [];
        

            foreach ($this->items as $item) {
                $total = 0;
                foreach ($item->purchaseRequest as $purchaseRequest) {
                    if (($purchaseRequest->status === 'Processed' || $purchaseRequest->status === 'Partially') && $purchaseRequest->partof === $item->inventory_out_partof) {
                        foreach ($purchaseRequest->po as $po) {
                            if (($po->status === 'Approved' || strtolower($po->status) === 'need to pay') && ($po->status_barang === 'Arrived' || $po->status_barang === 'Partially Arrived')) {
                                foreach ($po->podetail as $podetail) {
                                    if ($podetail->item_id === $item->inventory->item->id) {
                                        $total += $podetail->qty;
                                        $existingItemIndex = null;
            
                                        // Check if the item with the same inventory_id already exists in mergedItems
                                        foreach ($mergedItems as $index => $mergedItem) {
                                            if ($mergedItem->inventory_id === $item->inventory_id) {
                                                $existingItemIndex = $index;
                                                break;
                                            }
                                        }
            
                                        if ($existingItemIndex !== null) {
                                            // Override the existing item
                                            $mergedItems[$existingItemIndex]->total = $total;
                                            $mergedItems[$existingItemIndex]->podetailid = $podetail->id;
                                            $mergedItems[$existingItemIndex]->poid = $podetail->po->id;
                                            $mergedItems[$existingItemIndex]->earlystock = $total;
                                            $mergedItems[$existingItemIndex]->partof = $purchaseRequest->partof;
                                        } else {
                                            // Create a new item
                                            $itemClone = clone $item;
                                            $itemClone->podetailid = $podetail->id;
                                            $itemClone->poid = $podetail->po->id;
                                            $itemClone->earlystock = $total;
                                            $itemClone->partof = $purchaseRequest->partof;
                                            $itemClone->total = $total;
                                            
                                            $mergedItems[] = $itemClone;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        

        $this->items = $this->paginateCollection($mergedItems, 10);
        $this->publicItems = collect($mergedItems);
        // dd($this->items);

        $this->partofRowspan = [];
        $this->itemRowspan = [];

        foreach ($this->items as $item) {
            $itemName = $item->inventory->item->name;
            $partof = $item->partof;

            if (!isset($this->itemRowspan[$itemName])) {
                $this->itemRowspan[$itemName] = 0;
            }
            $this->itemRowspan[$itemName]++;

            if (!isset($this->partofRowspan[$itemName])) {
                $this->partofRowspan[$itemName] = [];
            }
            if (!isset($this->partofRowspan[$itemName][$partof])) {
                $this->partofRowspan[$itemName][$partof] = 0;
            }
            $this->partofRowspan[$itemName][$partof]++;
        }


        return view('livewire.invontory.draft-item', ['items' => $this->items]);
    }

    public function toggleReserve($paramId, $paramProject, $paramPart)
    {
        $existData = InventoryOut::where('inventory_detail_id', $paramId)->where('project_id', $paramProject)->where('partof', $paramPart)->first();
        $ownerId = auth()->user()->id;

        DB::beginTransaction();
        try {
            if ($existData) {
                $reservedValue = $existData->reserved;
                $existData->update([
                    'owner_id' => $reservedValue === 'true' ? null : $ownerId,
                    'reserved' => $reservedValue === 'true' ? 'false' : 'true'
                ]);
            } else {
                InventoryOut::create([
                    'inventory_detail_id' => $paramId,
                    'project_id' => $paramProject,
                    'partof' => $paramPart,
                    'out' => 0,
                    'user_id' => null,
                    'owner_id' => $ownerId,
                    'desc' => null,
                    'reserved' => 'true',
                    'date_out' => null
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public function toggleDraft()
    {
        $this->draftStatus = !$this->draftStatus;
    }

    public function toggleHalfStatus()
    {
        $this->halfStatus = !$this->halfStatus;
    }

    public function paginateCollection($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

}
