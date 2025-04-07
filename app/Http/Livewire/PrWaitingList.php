<?php

namespace App\Http\Livewire;

use App\Helpers\GeneratePrNo;
use App\Helpers\WarehouseHelper;
use App\Models\BOQ;
use App\Models\Inventory;
use App\Models\InventoryDetail;
use App\Models\InventoryHistory;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestDetail;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PrWaitingList extends Component
{
    public $isEditing;
    public $qtyUpdate;
    public $setting;

    public $checkedStocks = [];

    protected $listeners = ['storeChecked'];

    public function mount()
    {
        $this->setting = Setting::first();
    }

    public function render()
    {
        $prData = $this->getData();
        $stockData = $this->getStock();

        return view('livewire.pr-waiting-list', [
            'prData' => $prData,
            'stockData' => $stockData,
        ]);
    }

    public function getData()
    {
        $data = PurchaseRequest::where('status', 'Wait for approval')->get();
        return $data;
    }

    public function getStock()
    {
        $inventoryData = Inventory::all();
        $allowedWarehouse = WarehouseHelper::getFilteredWarehouses(null)->pluck('name');

        $prDetailData = $this->getData()->flatmap(function ($item) {
            return $item->prdetail;
        });

        $itemId = $prDetailData->pluck('item_id')->unique();

        $existingStock = $inventoryData->whereIn('item_id', $itemId);

        $stockData = $existingStock->flatMap(function ($item) use ($allowedWarehouse) {
            return $item->details
                ->where('project_id', null)
                ->whereIn('warehouse_type', $allowedWarehouse)
                ->map(function ($detail) use ($item) {
                    $detail->item_id = $item->item_id;
                    return $detail;
                });
        });

        return $stockData;
    }

    public function editQty($id, $currentQty)
    {
        $this->isEditing = $id;
        $this->qtyUpdate[$id] = $currentQty;
    }

    public function cancelEditQty()
    {
        $this->isEditing = false;
    }

    public function saveEditQty($detailId, $prevQty)
    {
        DB::beginTransaction();
        try {
            $updatedQty = $this->qtyUpdate[$detailId] ?? null;

            if ($updatedQty === null || $updatedQty === '' || $updatedQty == 0) {
                session()->flash('danger', 'Quantity tidak boleh kosong.');
                return;
            }

            if ($updatedQty > $prevQty) {
                session()->flash('danger', 'Quantity tidak boleh melebihi quantity awal.');
                return;
            }

            $prDetail = PurchaseRequestDetail::find($detailId);
            $prDetail->qty = $updatedQty;

            if ($prDetail->is_raw_materials !== 1) {
                $boqData = BOQ::where('project_id', $prDetail->purchaseRequest->project_id)->where('item_id', $prDetail->item_id)->latest()->first();

                if ($prDetail->purchaseRequest->partof == 'capex') {
                    $boqData->qty = $boqData->qty - ($prevQty - $updatedQty);

                    $boqData->save();
                } else {
                    $boqData->qty = $updatedQty;

                    $boqData->save();
                }
            }

            $prDetail->save();

            DB::commit();

            $this->isEditing = false;
            $this->qtyUpdate[$detailId] = null;
            session()->flash('success', 'Quantity berhasil diubah.');
        } catch (\Exception $e) {
            session()->flash('danger', 'Quantity gagal diubah.');
        }
    }

    public function approvePR($id)
    {
        DB::beginTransaction();
        try {
            $pr = PurchaseRequest::find($id);
            $isCompleteApproved = null;
            $generatedPrNo = null;

            $checkProjectId = $pr->project_id;
            if ($checkProjectId !== null && $pr->partof == 'capex') {
                $generatedPrNo = GeneratePrNo::get();
            } elseif ($checkProjectId !== null) {
                $generatedPrNo = GeneratePrNo::newPR($id);
            } else {
                $generatedPrNo = GeneratePrNo::get();
            }

            if ((bool) $this->setting->multiple_pr_approval) {
                if (is_null($pr->approved_by)) {
                    $pr->update([
                        'pr_no' => $generatedPrNo,
                        'approved_by' => auth()->user()->id,
                    ]);
                    $isCompleteApproved = false;
                } elseif (!is_null($pr->approved_by) && is_null($pr->approved_by_2)) {
                    if ($pr->approved_by !== auth()->user()->id) {
                        $pr->update([
                            'approved_by_2' => auth()->user()->id,
                            'status' => 'Approved',
                        ]);
                        $isCompleteApproved = true;
                    } else {
                        session()->flash('error', 'Anda tidak dapat memberikan persetujuan kedua karena Anda sudah melakukan persetujuan pertama.');
                        DB::rollBack();
                        return;
                    }
                }
            } else {
                // $prDetails = $pr->prdetail;
                // foreach ($prDetails as $prDetail) {
                //     if ($prDetail->is_bulk) {
                //         $memoNumber = GeneratePrNo::GenerateMemo();
                //         $prDetail->memo_number = $memoNumber;
                //         $prDetail->save();
                //     }
                // }
                $pr->update([
                    'pr_no' => $generatedPrNo,
                    'status' => 'Approved',
                    'approved_by' => auth()->user()->id,
                    'approved_by_2' => auth()->user()->id,
                ]);
                $isCompleteApproved = true;
            }

            if ($isCompleteApproved) {
                if (count($this->checkedStocks) > 0) {
                    $this->handleStock($id, $pr);
                }
            }

            $pr->save();
            DB::commit();

            session()->flash('success', 'PR berhasil diapprove.');
            $this->reset('checkedStocks');
        } catch (\Exception $e) {
            dd($e->getMessage());
            session()->flash('danger', 'PR gagal diapprove.');
        }
    }

    public function storeChecked($data)
    {
        $itemId = explode(', ', $data)[0];

        $this->checkedStocks = array_filter($this->checkedStocks, function ($item) use ($itemId) {
            return explode(', ', $item)[0] !== $itemId;
        });

        $this->checkedStocks[] = $data;
    }

    public function deleteItem($id)
    {
        DB::beginTransaction();
        try {
            $prDetail = PurchaseRequestDetail::find($id);
            $prDetail->delete();
            DB::commit();

            session()->flash('success', 'Item berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('danger', 'Item gagal dihapus.');
        }
    }

    public function handleStock($id, $pr)
    {
        $prDetailsData = $pr->prdetail;
        $boqData = BOQ::where('project_id', $pr->project_id)->where('task_number', $pr->partof)->get();

        $formattedChecked = array_map(function ($item) {
            $parts = explode(',', $item);
            return [
                'prdetail_id' => $parts[0],
                'item_id' => $parts[1],
                'available_stock' => $parts[2],
                'pr_id' => $parts[3],
                'warehouse_type' => $parts[4],
            ];
        }, $this->checkedStocks);

        $filterByPr = array_filter($formattedChecked, function ($item) use ($id) {
            return $item['pr_id'] == $id;
        });

        if (count($filterByPr) > 0) {
            $updatedRecord = [];
            $updatedHistory = [];

            $isConsummable = str_contains($pr->partof, '/00/00') || $pr->task->task == 'Indent';
            foreach ($filterByPr as $filteredItem) {
                $historyData = [];
                foreach ($prDetailsData as $detail) {
                    if ($detail->id == $filteredItem['prdetail_id']) {
                        $inventoryData = Inventory::where('item_id', $filteredItem['item_id'])
                            ->whereHas('details', function ($query) {
                                $query->where('project_id', null);
                            })
                            ->with([
                                'details' => function ($query) {
                                    $query->where('project_id', null);
                                },
                            ])
                            ->first();

                        $totalQty = 0;
                        $totalStock = 0;
                        $stockAfter = 0;

                        if ($filteredItem['available_stock'] >= $detail->qty) {
                            $totalQty = 0;
                            $totalStock = $detail->qty;

                            $stockAfter = $filteredItem['available_stock'] - $detail->qty;
                        } else {
                            $totalQty = $detail->qty - $filteredItem['available_stock'];
                            $totalStock = $filteredItem['available_stock'];
                            $stockAfter = 0;
                        }

                        $detail->qty = $totalQty;
                        $detail->include_stock = $totalStock;

                        $filteredItem['warehouse_type'] = trim($filteredItem['warehouse_type']);
                        $inventoryDetailsData = $inventoryData->details->where('warehouse_type', $filteredItem['warehouse_type'])->first();

                        $memoNumber = GeneratePrNo::GenerateMemo();
                        $detail->memo_number = $memoNumber;
                        $detail->stock_from = $filteredItem['warehouse_type'];

                        $historyData = [
                            'inventory_detail_id' => $inventoryDetailsData->id,
                            'stock_before' => $inventoryDetailsData->stock,
                            'stock_after' => $stockAfter,
                            'stock_change' => $totalStock,
                            'user_id' => auth()->user()->id,
                            'prdetail_id' => $detail->id,
                            'type' => 'OUT',
                        ];

                        $inventoryData->details->first()->stock = $stockAfter;

                        // Update BOQ
                        if ($isConsummable) {
                            $boqStock = $boqData->where('item_id', $filteredItem['item_id']);
                            $boqStock->first()->qty = $boqStock->first()->qty - $totalStock;

                            $boqStock->first()->save();
                        } else {
                            $boqStock = $boqData->where('item_id', $filteredItem['item_id']);
                            $maxSection = $boqStock->max('section');

                            $boqStock->where('section', $maxSection)->first()->qty = $boqStock->where('section', $maxSection)->first()->qty - $totalStock;

                            $boqStock->where('section', $maxSection)->first()->save();
                        }

                        InventoryHistory::create($historyData);
                        $detail->save();
                        $inventoryData->details->first()->save();

                        $updatedHistory[] = $historyData;

                        $updatedRecord[] = $detail;
                    }
                }
            }
        }
    }
}
