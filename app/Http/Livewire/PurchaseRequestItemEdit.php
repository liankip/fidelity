<?php

namespace App\Http\Livewire;

use App\Models\BOQ;
use App\Models\BOQSpreadsheet;
use App\Models\Item;
use App\Models\Project;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestDetail;
use App\Models\Setting;
use App\Models\Task;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PurchaseRequestItemEdit extends Component
{
    use WithPagination;

    public $search;
    protected $updatesQueryString = ['search'];
    protected $paginationTheme = 'bootstrap';

    public $prid, $prequest;
    public $itemsarray = [];
    public $itemsarray1;
    public $cartmodal = false;

    public $showadditem = false;
    public $itemname, $itemunit, $matchitem;

    public $setting;

    public Project $project;

    public $qty = [];
    public $notes = [];
    public $task;

    public function mount($id): void
    {
        $this->setting = Setting::first();

        $this->prid = $id;

        $this->prequest = PurchaseRequest::where("id", $id)->first();

        $this->task = Task::where('task_number', $this->prequest->partof)->first();

        $this->project = $this->prequest->project;

        $prd = PurchaseRequestDetail::with('item', 'podetail')->where("pr_id", $id)
            ->get();

        $itemIds = $prd->pluck('item_id')->toArray();
        $items_in_boq = $this->project->itemQuantity($itemIds);
        $prQty = PurchaseRequestDetail::getItemQuantityWithPrFilter($this->prequest->id, $itemIds, $this->project->id);

        // $prd = PurchaseRequestDetail::where("pr_id", $id)->get();
        $this->qty = [];
        $this->notes = [];

        foreach ($prd as $key => $value) {
            $item_in_boq = $items_in_boq[$value->item_id]->qty ?? 0;
            $item_in_pr = $prQty[$value->item_id]->total_qty ?? 0;
            $max_item = $item_in_boq - $item_in_pr;
            $po = $value->podetail->where('item_id', $value->item_id);
            $min_item = 0;

            if ($po->sum('qty') > 0) {
                $min_item = $po->sum('qty');
            }

            $this->itemsarray[] = [
                "id" => $value->item_id,
                "item_code" => $value->item ? $value->item->item_code : '',
                "name" => $value->item_name,
                "type" => $value->type,
                "unit" => $value->unit,
                "image" => $value->item ? $value->item->image : '',
                "created_by" => null,
                "updated_by" => null,
                "deleted_by" => null,
                "qty" => $value->qty,
                "note" => $value->notes,
                "estimation_date" => $value->estimation_date,
                'min_item' => $min_item,
                'max_item' => $max_item,
            ];
            $this->qty[$key] = (float)$value->qty;
            $this->notes[$key] = $value->notes;

        }
    }

    public function addItem()
    {
        // dd($this->itemsarray);
        DB::beginTransaction();
        try {
            $existingItemIds = PurchaseRequestDetail::whereHas('purchaseRequest', function ($query) {
                $query->where('partof', $this->prequest->partof);
            })->get();

            foreach ($this->itemsarray as $index => $item) {
                if ($this->qty[$index] > $item['qty']) {
                    throw new \Exception("Quantity untuk item {$item['name']} tidak boleh melebihi {$item['qty']}.");
                }

                $purchaseRequestDetail = PurchaseRequestDetail::where('pr_id', $this->prequest->id)
                    ->where('item_id', $item['id'])
                    ->first();

                if ($purchaseRequestDetail) {
                    $purchaseRequestDetail->update([
                        'qty' => $this->qty[$index],
                        'notes' => $this->notes[$index],
                        'updated_by' => auth()->user()->id,
                    ]);
                } else {
                    PurchaseRequestDetail::create([
                        'pr_id' => $this->prequest->id,
                        'item_id' => $item['id'],
                        'item_name' => $item['name'],
                        'type' => $item['type'],
                        'unit' => $item['unit'],
                        'qty' => $this->qty[$index],
                        'created_by' => auth()->user()->id,
                        'updated_by' => auth()->user()->id,
                        'status' => "baru",
                        'notes' => $this->notes[$index] ?? '',
                        'estimation_date' => Carbon::parse($item['estimation_date'])->format('Y-m-d H:i:s'),
                    ]);
                }

                BOQ::updateOrCreate(
                    [
                        'project_id' => $this->project->id,
                        'item_id' => $item['id'],
                        'task_number' => $this->task->task_number,
                    ],
                    [
                        'qty' => (float)$this->qty[$index],
                        'updated_by' => auth()->user()->id,
                    ]
                );

                $existingPRDetails = $existingItemIds->where('item_id', $item['id']);
                $totalExistingQty = $existingPRDetails->sum('qty');
                
                if($totalExistingQty === 0) {
                    BOQ::updateOrCreate(
                        [
                            'project_id' => $this->project->id,
                            'item_id' => $item['id'],
                            'task_number' => $this->task->task_number,
                        ],
                        [
                            'qty' => (float)$this->qty[$index],
                            'updated_by' => auth()->user()->id,
                        ]
                    );
                } else {
                    $existingBOQ = BOQ::where('project_id', $this->project->id)->where('item_id', $item['id'])->where('task_number', $this->task->task_number)->first();

                    if($this->qty[$index] < $item['qty']) {
                        $minusValue = $item['qty'] - $this->qty[$index];
                        $existingBOQ->update([
                            'qty' => $totalExistingQty - $minusValue,
                            'updated_by' => auth()->user()->id,
                        ]);
                    }
                }
            }
            
            DB::commit();
            return redirect()->to('/task-monitoring/' . $this->task->id)->with('success', 'Item successfully added or updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.purchase-request-item-edit');
    }
}


// public function render()
//     {
//         if ($this->setting->boq || (!$this->setting->boq && $this->project->boq)) {
//             if ($this->search) {
//                 $itemsQuery = $this->project->purchaseRequestItems($this->prequest->id, $this->search);
//             } else {
//                 $itemsQuery = $this->project->purchaseRequestItems($this->prequest->id);
//             }
//         } else {
//             if ($this->search) {
//                 $itemsQuery = Item::where('name', 'like', '%' . $this->search . '%')->get();
//             } else {
//                 $itemsQuery = Item::all();
//             }
//         }

//         if ($this->prequest->is_task == 1) {
//             $itemsQuery = $itemsQuery->where('task_number', $this->prequest->partof);
//         } else {
//             $itemsQuery = $itemsQuery->where('task_number', null);
//         }

//         $itemsQuery = $itemsQuery->where('pr_id', $this->prequest->id);

//         if ($this->task && $this->task->start_date) {
//             $estimationDate = Carbon::parse($this->task->start_date)->subDays($this->task->earliest_start)->format('Y-m-d');
//         } else {
//             $estimationDate = null;
//         }

//         $this->qty = [];
//         $this->notes = [];

//         foreach ($itemsQuery as $index => $item) {
//             $this->qty[$index] = (float)$item->qty;

//             $purchaseRequestDetailForItem = PurchaseRequestDetail::where('item_id', $item->item_id)->first();

//             $item->item_in_pr = $purchaseRequestDetailForItem->total_qty ?? 0;
//             $item->estimation_date = $estimationDate;

//             $item->notes = $this->notes[$index] = $purchaseRequestDetailForItem->notes ?? '';
//         }

//         return view('livewire.purchase-request-item-edit', [
//             'items' => $itemsQuery,
//         ]);
//     }