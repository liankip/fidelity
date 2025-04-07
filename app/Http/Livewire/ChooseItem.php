<?php

namespace App\Http\Livewire;

use App\Models\BOQ;
use App\Models\BOQSpreadsheet;
use App\Models\BulkPOPivot;
use App\Models\Item;
use App\Models\Project;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestDetail;
use App\Models\Setting;
use App\Models\Task;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ChooseItem extends Component
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
    public $itfirst = false;

    public $setting;

    public Project $project;

    public $task;
    public $qty = [];
    public $notes = [];

    public function mount(Request $request, $id): void
    {
        $this->setting = Setting::first();

        $this->itfirst = $request->firstcreate;
        $this->prid = $id;

        $this->prequest = PurchaseRequest::where('id', $id)->first();

        $this->task = Task::where('task_number', $this->prequest->partof)->first();

        $this->project = $this->prequest->project;
    }

    public function updatedItemname()
    {
        if ($this->itemname) {
            $this->matchitem = Item::where('name', 'like', '%' . $this->itemname . '%')
                ->take(3)
                ->get();
        }
    }

    public function UpdatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $itemsQuery = null;

        if ($this->setting->boq || (!$this->setting->boq && $this->project->boq)) {
            $itemsQuery = $this->project->purchaseRequestItems($this->prequest->id);
        } else {
            $itemsQuery = Item::all();
        }

        $itemsQuery = $this->prequest->is_task == 1 ? $itemsQuery->where('task_number', $this->prequest->partof) : $itemsQuery->where('task_number', null);

        $isTaskConsumbales = str_contains($this->prequest->partof, '/00/00');

        $checkSection = $itemsQuery->contains('section', null);
        if ($isTaskConsumbales || $this->task->section == 'Consumables' || $this->task->is_consumables == 1) {
            if ($checkSection || !$checkSection) {
                $existingItemIds = PurchaseRequestDetail::whereHas('purchaseRequest', function ($query) {
                    $query->where('partof', $this->prequest->partof);
                })->get(['item_id', 'qty']);

                $allMatch = true;

                foreach ($itemsQuery as $index => $item) {
                    $existingItem = $existingItemIds->firstWhere('item_id', $item['item_id']);

                    if (!$existingItem || $existingItem->qty != $item['qty']) {
                        $allMatch = false;
                        break;
                    }
                }

                if ($allMatch) {
                    $itemsQuery = collect();
                }

                $purchaseRequestDetail = PurchaseRequestDetail::where('pr_id', $this->prid)->get();

                if ($this->task && $this->task->start_date) {
                    $estimationDate = Carbon::parse($this->task->start_date)
                        ->subDays($this->task->earliest_start)
                        ->format('Y-m-d');
                } else {
                    $estimationDate = null;
                }

                $this->qty = [];
                $this->notes = [];

                foreach ($itemsQuery as $index => $item) {
                    $existingPRDetails = $existingItemIds->where('item_id', $item->item_id);
                    $totalExistingQty = $existingPRDetails->sum('qty');

                    $quantity = (float) $item->qty - $totalExistingQty;

                    if ($quantity <= 0) {
                        continue;
                    }

                    $this->qty[$index] = $quantity;

                    $purchaseRequestDetailForItem = $purchaseRequestDetail->firstWhere('item_id', $item->item_id);
                    $this->notes[$index] = $purchaseRequestDetailForItem->notes ?? '';

                    $item->estimation_date = $estimationDate;
                }

                $itemsQuery = $itemsQuery->filter(function ($item, $key) {
                    return isset($this->qty[$key]);
                });
            }
        } else {
            $latestSection = BOQ::where('task_number', $this->prequest->partof)
                ->whereNotNull('section')
                ->max('section');

            if ($latestSection == 0) {
                $itemsQuery = BOQ::where('task_number', $this->prequest->partof)
                    ->where('section', $latestSection)
                    ->whereNotNull('approved_by_3')
                    ->when($this->search, function ($query) {
                        $query->whereHas('item', function ($subQuery) {
                            $subQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                    })
                    ->get();

                    if(count($itemsQuery) > 0){
                        $extractedItems = $itemsQuery->map(function ($item) {
                            return [
                                'item_id' => $item->item_id,
                                'qty' => $item->qty
                            ];
                        })->toArray();
    
                        $taskNumber = $itemsQuery->first()->task_number;
    
                        $isPRexist = PurchaseRequest::where('partof', $taskNumber)->whereNotNull('pr_no')->exists();
                        
                        if($isPRexist){
                            $prData = PurchaseRequest::with('prdetail')->where('partof', $taskNumber)->whereNotNull('pr_no')->get();
                            
    
                            $results = [];
    
                            foreach ($prData as $pr) {
                                if ($pr->prdetail->count() === count($extractedItems)) {
                                    $prDetails = $pr->prdetail->map(function ($detail) {
                                        return [
                                            'item_id' => $detail->item_id,
                                            'qty' => $detail->qty,
                                        ];
                                    })->toArray();
    
                                    $hasExactMatch = collect($prDetails)->sort()->values()->toArray() === collect($extractedItems)->sort()->values()->toArray();
    
                                    $results[] = [
                                        'pr_no' => $pr->pr_no,
                                        'hasExactMatch' => $hasExactMatch,
                                        'detailsCount' => $pr->prdetail->count(),
                                    ];
                                }
                            }
    
                            if(count($results) > 0 && collect($results)->contains('hasExactMatch', true)){
                                $itemsQuery = collect();
                            }
                            
                        }
                    }
            } elseif ($latestSection > 0) {
                $itemsQuery = BOQ::where('task_number', $this->prequest->partof)
                    ->where('section', $latestSection)
                    ->whereNotNull('approved_by_3')
                    ->when($this->search, function ($query) {
                        $query->whereHas('item', function ($subQuery) {
                            $subQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                    })
                    ->get();

                    if(count($itemsQuery) > 0){
                        $extractedItems = $itemsQuery->map(function ($item) {
                            return [
                                'item_id' => $item->item_id,
                                'qty' => $item->qty
                            ];
                        })->toArray();
    
                        $taskNumber = $itemsQuery->first()->task_number;
    
                        $isPRexist = PurchaseRequest::where('partof', $taskNumber)->whereNotNull('pr_no')->exists();
                        
                        if($isPRexist){
                            $prData = PurchaseRequest::with('prdetail')->where('partof', $taskNumber)->whereNotNull('pr_no')->get();
                            
    
                            $results = [];
    
                            foreach ($prData as $pr) {
                                if ($pr->prdetail->count() === count($extractedItems)) {
                                    $prDetails = $pr->prdetail->map(function ($detail) {
                                        return [
                                            'item_id' => $detail->item_id,
                                            'qty' => $detail->qty,
                                        ];
                                    })->toArray();
    
                                    $hasExactMatch = collect($prDetails)->sort()->values()->toArray() === collect($extractedItems)->sort()->values()->toArray();
    
                                    $results[] = [
                                        'pr_no' => $pr->pr_no,
                                        'hasExactMatch' => $hasExactMatch,
                                        'detailsCount' => $pr->prdetail->count(),
                                    ];
                                }
                            }
    
                            if(count($results) > 0 && collect($results)->contains('hasExactMatch', true)){
                                $itemsQuery = collect();
                            }
                            
                        }
                    }
                    
            } else {
                $itemsQuery = BOQ::where('task_number', $this->prequest->partof)
                    ->where('section', $latestSection)
                    ->whereNotNull('approved_by_2')
                    ->when($this->search, function ($query) {
                        $query->whereHas('item', function ($subQuery) {
                            $subQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                    })
                    ->get();

                $purchaseRequest = PurchaseRequest::where('partof', $this->prequest->partof)
                    ->with(['purchaseRequestDetails'])
                    ->whereNotNull('pr_no')
                    ->orderBy('id', 'desc')
                    ->first();

                $purchaseRequestPrNoNull = PurchaseRequest::where('partof', $this->prequest->partof)
                    ->with(['purchaseRequestDetails'])
                    ->whereNull('pr_no')
                    ->orderBy('id', 'desc')
                    ->first();

                $itemsQuery = $itemsQuery->filter(function ($boqItem) use ($purchaseRequest, $purchaseRequestPrNoNull) {
                    $prDetail = $purchaseRequest->purchaseRequestDetails->firstWhere('item_id', $boqItem->item_id);
                    $prDetailPrNull = $purchaseRequestPrNoNull->purchaseRequestDetails->firstWhere('item_id', $boqItem->item_id);

                    $qtyPr = $prDetail ? $prDetail->qty : 0;
                    $qtyPrNoNull = $prDetailPrNull ? $prDetailPrNull->qty : 0;

                    if ($boqItem->qty == 1) {
                        $boq = $boqItem->qty != $qtyPrNoNull;
                    } else {
                        $boq = $boqItem->qty != $qtyPr;
                    }

                    return $boq;
                })->values();
            }

            $purchaseRequest = PurchaseRequest::where('partof', $this->prequest->partof)
                ->with(['purchaseRequestDetails'])
                ->whereNotNull('pr_no')
                ->orderBy('id', 'desc')
                ->first();

            if($purchaseRequest !== null) {
                $itemsQuery = $itemsQuery->filter(function ($boqItem) use ($purchaseRequest) {
                    $prDetail = $purchaseRequest->purchaseRequestDetails->firstWhere('item_id', $boqItem->item_id);
    
                    $qtyPr = $prDetail ? $prDetail->qty : 0;
                    return $boqItem->qty != $qtyPr;
                })->values();
            }

            $prData = PurchaseRequestDetail::whereHas('purchaseRequest', function ($query) {
                $query->where('partof', $this->prequest->partof);
            })->get();

            $itemId = $itemsQuery->pluck('item_id')->unique();
            $existPrItemIds = $prData->whereIn('item_id', $itemId)->pluck('item_id')->unique()->toArray();

            $boqData = BOQ::where('task_number', $this->prequest->partof)->get();

            $multipleItems = $boqData->groupBy('item_id')->filter(function ($group) {
                return $group->count() > 1;
            });

            if ($multipleItems->isEmpty()) {
                $itemsQuery = $itemsQuery
                    ->filter(function ($item) use ($existPrItemIds) {
                        return !in_array($item->item_id, $existPrItemIds);
                    })
                    ->values()
                    ->all();
            }

            foreach ($itemsQuery as $index => $item) {
                $this->qty[$index] = (int) $item->qty ?? 0;
                $item->estimation_date =
                    $this->task && $this->task->start_date
                        ? Carbon::parse($this->task->start_date)
                            ->subDays($this->task->earliest_start)
                            ->format('Y-m-d')
                        : null;
            }
        }

        $bulkItems = PurchaseOrderDetail::whereHas('po', function ($query) {
            $query->where('project_id', $this->project->id);
        })
            ->where('purchase_request_detail_id', null)
            ->where('is_bulk', 1)
            ->pluck('item_id')
            ->unique();

        $itemsQuery = collect($itemsQuery)->map(function ($item) use ($bulkItems) {
            $item->is_bulk = $bulkItems->contains($item->item_id);
            return $item;
        });

        return view('livewire.choose-item', [
            'items' => $itemsQuery,
        ]);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'itemsarray.*.count' => [
                'numeric',
                function ($attribute, $value, $fail) {
                    $key = explode('.', $attribute)[1];
                    if ($value > $this->itemsarray[$key]['max_item']) {
                        $fail('Qty must not be greater than the max qty in boq.');
                    }
                },
            ],
        ]);
    }

    public function addItem($items)
    {
        DB::beginTransaction();
        try {
            $existingItemIds = PurchaseRequestDetail::whereHas('purchaseRequest', function ($query) {
                $query->where('partof', $this->prequest->partof);
            })->get();

            $isTaskConsumbales = str_contains($this->prequest->partof, '/00/00') || $this->task->is_consumables == 1;

            $projectId = $this->project->id;
            $pivotData = [];
            foreach ($items as $index => $item) {
                $existingPRDetails = $existingItemIds->where('item_id', $item['item']['id']);

                if ($isTaskConsumbales) {
                    $totalExistingQty = $existingPRDetails->sum('qty');
                    $remainingQty = $item['qty'] - $totalExistingQty;

                    if ($this->qty[$index] > $remainingQty) {
                        throw new \Exception("Quantity untuk item {$item['item']['name']} tidak boleh melebihi {$remainingQty}.");
                    }
                } else {
                    $totalExistingQty = $existingPRDetails->sum('qty');
                    $boq = BOQ::where('item_id', $item['item_id'])
                        ->where('task_number', $this->task->task_number)
                        ->get();

                    foreach ($boq as $b) {
                        $remainingQty = $item['qty'] + $b->qty;
                    }

                    if ($this->qty[$index] > $remainingQty) {
                        throw new \Exception("Quantity untuk item {$item['item']['name']} tidak boleh melebihi {$remainingQty}.");
                    }
                }

                $existingDetail = PurchaseRequestDetail::where('pr_id', $this->prequest->id)
                    ->where('item_id', $item['item']['id'])
                    ->first();

                if ($existingDetail) {
                    $existingDetail->update([
                        'qty' => (float) $this->qty[$index],
                        'notes' => $this->notes[$index],
                        'updated_by' => auth()->user()->id,
                    ]);
                } else {
                    $newPrDetail = PurchaseRequestDetail::create([
                        'pr_id' => $this->prequest->id,
                        'item_id' => $item['item']['id'],
                        'item_name' => $item['item']['name'],
                        'type' => $item['item']['type'],
                        'unit' => $item['unit']['name'],
                        'qty' => $this->qty[$index],
                        'created_by' => auth()->user()->id,
                        'updated_by' => auth()->user()->id,
                        'status' => 'baru',
                        'notes' => $this->notes[$index] ?? '',
                        'estimation_date' => Carbon::parse($item['estimation_date'])->format('Y-m-d H:i:s'),
                        'is_bulk' => $item['is_bulk'],
                    ]);

                    if ($item['is_bulk'] == 1) {
                        $poDetailBulk = PurchaseOrderDetail::where('item_id', $item['item']['id'])
                            ->whereHas('po', function ($query) use ($projectId) {
                                $query->where('project_id', $projectId);
                            })
                            ->where('is_bulk', 1)
                            ->first();

                        if ($poDetailBulk) {
                            $pivotData[] = BulkPOPivot::create([
                                'pr_detail_id' => $newPrDetail->id,
                                'po_detail_id' => $poDetailBulk->id,
                            ]);
                        }
                    }
                }

                if ($isTaskConsumbales) {
                    if ($totalExistingQty === 0) {
                        BOQ::updateOrCreate(
                            [
                                'project_id' => $this->project->id,
                                'item_id' => $item['item']['id'],
                                'task_number' => $this->task->task_number,
                            ],
                            [
                                'qty' => (float) $this->qty[$index],
                                'updated_by' => auth()->user()->id,
                            ],
                        );
                    } else {
                        $existingBOQ = BOQ::where('project_id', $this->project->id)
                            ->where('item_id', $item['item']['id'])
                            ->where('task_number', $this->task->task_number)
                            ->first();

                        if ($this->qty[$index] < $remainingQty) {
                            $existingBOQ->update([
                                'qty' => $totalExistingQty + $this->qty[$index],
                                'updated_by' => auth()->user()->id,
                            ]);
                        } else {
                            $existingBOQ->update([
                                'qty' => $item['qty'],
                                'updated_by' => auth()->user()->id,
                            ]);
                        }
                    }
                } else {
                    // jika tidak consummables
                    if ($totalExistingQty === 0) {
                        BOQ::updateOrCreate(
                            [
                                'project_id' => $this->project->id,
                                'item_id' => $item['item']['id'],
                                'task_number' => $this->task->task_number,
                            ],
                            [
                                'qty' => (float) $this->qty[$index],
                                'updated_by' => auth()->user()->id,
                            ],
                        );
                    } else {
                        $existingBOQ = BOQ::where('project_id', $this->project->id)
                            ->where('item_id', $item['item']['id'])
                            ->where('task_number', $this->task->task_number)
                            ->get()
                            ->last();

                        if ($this->qty[$index] < $remainingQty) {
                            $existingBOQ->update([
                                'qty' => $this->qty[$index],
                                'updated_by' => auth()->user()->id,
                            ]);
                        } else {
                            $existingBOQ->update([
                                'qty' => $item['qty'],
                                'updated_by' => auth()->user()->id,
                            ]);
                        }
                    }
                }
            }
            // dd($pivotData);
            DB::commit();

            return redirect()
                ->to('/task-monitoring/' . $this->task->id)
                ->with('success', 'Item successfully added or updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }

    public function subtractitem($id)
    {
        // dd($id);
        foreach ($this->itemsarray as $key => $value) {
            if ($value['id'] == $id) {
                // dd($this->itemsarray);

                if ($value['count'] > 1) {
                    $this->itemsarray[$key]['count'] = $value['count'] - 1;
                    PurchaseRequestDetail::where('pr_id', $this->prid)
                        ->where('item_id', $value['id'])
                        ->update([
                            'qty' => $this->itemsarray[$key]['count'],
                        ]);
                } else {
                    unset($this->itemsarray[$key]);
                    PurchaseRequestDetail::where('pr_id', $this->prid)
                        ->where('item_id', $value['id'])
                        ->delete();
                    $this->changeStatuspr();
                }
            }
        }
    }

    public function removeitem($key)
    {
        PurchaseRequestDetail::where('pr_id', $this->prid)
            ->where('item_id', $this->itemsarray[$key]['id'])
            ->delete();
        unset($this->itemsarray[$key]);
        $this->changeStatuspr();
    }

    private function changeStatuspr()
    {
        if ($this->prequest->status == 'Partially') {
            if (count($this->itemsarray) == 0) {
                PurchaseRequest::where('id', $this->prid)->update([
                    'status' => 'Processed',
                ]);
            }
        } elseif ($this->prequest->status == 'Processed') {
            if (count($this->itemsarray) > 0) {
                PurchaseRequest::where('id', $this->prid)->update([
                    'status' => 'Partially',
                ]);
            }
        }
    }

    public function updateunit($key, $unit)
    {
        if (!isset($this->itemsarray[$key])) {
            return;
        }

        $item = $this->itemsarray[$key];

        PurchaseRequestDetail::where('pr_id', $this->prid)
            ->where('item_id', $item['id'])
            ->update([
                'unit' => $item['unit'],
            ]);
    }

    public function updateqty($key, $item_id)
    {
        if (!isset($this->itemsarray[$key])) {
            return;
        }

        $item = $this->itemsarray[$key];

        if ($this->setting->boq || (!$this->setting->boq && $this->project->boq)) {
            $item_in_pr = 0;

            $item_in_boq = $this->project->itemQuantity($item_id);
            $item_in_boq = $item_in_boq ? (float) $item_in_boq->qty : 0;

            $prQuantities = PurchaseRequestDetail::getItemQuantity($item['id'], $this->project->id);
            $item_in_pr = $prQuantities[$item['id']]->total_qty ?? 0;

            $max_item = $item_in_boq - $item_in_pr;
            $saved_item = PurchaseRequestDetail::where('pr_id', $this->prid)
                ->where('item_id', $item['id'])
                ->pluck('qty')
                ->first();
            $max_saved_item = $item_in_boq - $item_in_pr + $saved_item;

            if ($max_item < 0 || $item['count'] > $max_saved_item) {
                return;
            }
        }

        if ($item['count'] == 0) {
            $resutl = PurchaseRequestDetail::where('pr_id', $this->prid)
                ->where('item_id', $item['id'])
                ->delete();
            unset($this->itemsarray[$key]);
            $this->changeStatuspr();
        } elseif ($item['count'] >= 1000000) {
            $resutl = PurchaseRequestDetail::where('pr_id', $this->prid)
                ->where('item_id', $item['id'])
                ->first();
            $this->itemsarray[$key]['count'] = number_format($resutl->qty, 2, '', '');
        } else {
            PurchaseRequestDetail::where('pr_id', $this->prid)
                ->where('item_id', $item['id'])
                ->update([
                    'qty' => $item['count'],
                    'updated_by' => auth()->user()->id,
                ]);
        }
    }

    public function update_unit($key)
    {
        if (!isset($this->itemsarray[$key])) {
            return;
        }

        $item = $this->itemsarray[$key];

        if (Unit::where('name', $item['unit'])->first() == null) {
            return back()->with('danger', 'Unit not found');
        }

        PurchaseRequestDetail::where('pr_id', $this->prid)
            ->where('item_id', $item['id'])
            ->update([
                'unit' => $item['unit'],
            ]);
    }

    public function updatenote($key)
    {
        if (!isset($this->itemsarray[$key])) {
            return;
        }

        $item = $this->itemsarray[$key];
        if (isset($item['note'])) {
            $note = $item['note'];
        } else {
            $note = '';
        }
        PurchaseRequestDetail::where('pr_id', $this->prid)
            ->where('item_id', $item['id'])
            ->update([
                'notes' => $note,
            ]);
    }

    public function updateestimationdate($key)
    {
        if (!isset($this->itemsarray[$key])) {
            return;
        }

        $item = $this->itemsarray[$key];

        if (isset($item['estimation_date'])) {
            if ($item['estimation_date']) {
                $estimation_date = $item['estimation_date'];
            } else {
                $estimation_date = null;
            }
        } else {
            $estimation_date = null;
        }
        PurchaseRequestDetail::where('pr_id', $this->prid)
            ->where('item_id', $item['id'])
            ->update([
                'estimation_date' => $estimation_date,
            ]);
    }

    protected $listeners = ['emitselesai' => 'selesai'];

    public function selesai()
    {
        return redirect()->to('/purchase_requests');
    }

    public function storeitem()
    {
        $this->validate(
            [
                'itemname' => 'required|unique:items,name',
                'itemunit' => 'required',
            ],
            [
                'itemname' => 'Name of item',
                'itemunit' => 'Unit Item',
            ],
        );

        Item::create([
            'item_code' => 'NA',
            'name' => $this->itemname,
            'type' => 'NA',
            'unit' => $this->itemunit,
            'create_by' => auth()->user()->id,
            'image' => 'images/no_image.png',
        ]);

        $this->closeshowai();

        return Redirect(request()->header('Referer'))
            ->with('message', 'Item successfully added.')
            ->with('oldsearch', $this->search);
    }

    public function closeshowai()
    {
        $this->showadditem = false;
    }
}
