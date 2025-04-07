<?php

namespace App\Http\Livewire\CapexExpense;

use App\Exports\BOQExport;
use App\Models\BOQ;
use App\Models\BOQEdit;
use App\Models\BOQSpreadsheet;
use App\Models\HistoryPurchase;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Project;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestDetail;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\BoqApproved;
use App\Notifications\BOQRejected;
use App\Notifications\PurchaseRequestCreated;
use App\Roles\Role;
use Auth;
use Carbon\Carbon;
use DB;
use Exception;
use Livewire\Component;
use Notification;
use Maatwebsite\Excel\Facades\Excel;
use App\Traits\NotificationManager;
use Livewire\WithPagination;

class CapexExpenseBoq extends Component
{
    use WithPagination;
    use NotificationManager;

    public $project_id;
    public $capexExpense;
    public $countPurchaseRequestDetail;
    public $countPurchaseRequestPrNo;
    public $countBoqIsApprovedFirst;
    public $countBoqIsApprovedSecond;
    public $countBoqIsApprovedThird;
    public $countBoqList;
    public $boqList;
    public $allBoqList;
    public $task_boq;
    public $adendum = false;
    public $show_version;
    public $boqTable;
    public $version;
    public $prDetails;
    public $boqs;
    public $boqsArray;
    public $dataSpreadsheet;
    public $max_version;
    public $setting;
    public $section;
    protected $paginationTheme = 'bootstrap';
    public $selectedBoqIds = [];
    public $type;
    public $requester;
    public $remark;
    public $sortBy, $filter;
    public $perPage = 10;

    protected $listeners = [
        'refresh' => '$refresh',
        'updateSelectedBoqIds',
        'loadMore',
        'saveHandlerCapexExpense',
    ];

    public function updateSelectedBoqIds($ids, $action)
    {
        $this->selectedBoqIds = $ids;

        if ($action == 'approve') {
            $this->approve();
        } elseif ($action == 'reject') {
            $this->reject();
        }
    }

    public function mount($project_id)
    {
        $this->project_id = $project_id;
        $this->project = \App\Models\Project::find($project_id);

        $max_revision = $this->project->maxEditRevision();
        $this->setting = Setting::first();

        $this->max_version = (int) $max_revision;

        if ($this->show_version == null) {
            if (!is_null($max_revision)) {
                $this->boqTable = 'b_o_q_edits';

                $this->version[] = 0;
                for ($i = 1; $i <= $max_revision; $i++) {
                    $this->version[] = $i;
                }

                $this->show_version = (int) $max_revision;
            } else {
                $this->boqTable = 'b_o_q_s';
                $this->version[] = 0;
                $this->show_version = 0;
            }

            $this->boqs = $this->project->boqs_list()->load('item');

            $this->allBoqList = $this->getFilteredAndSortedBoqList();
            $this->boqList = $this->allBoqList->slice(0, $this->perPage);
            $this->countBoqList = $this->allBoqList->count();

            $this->boqsArray = collect($this->boqs->toArray());
            $this->needToApprove = $this->project->isBoqApprovedWithoutTaskNumber($this->boqTable, $this->show_version);
        }

        // $this->countPurchaseRequestDetail = PurchaseRequest::where('project_id', $this->project_id)->doesntHave('prdetail')->count();

        // $this->countPurchaseRequestPrNo = PurchaseRequest::where('project_id', $this->project_id)->whereNull('pr_no')->count();
    }

    public function export_boq()
    {
        $invalidCharacters = ['/', '\\', ':', '*', '?', '"', '\'', '|', '<', '>'];
        $projectName = str_replace($invalidCharacters, '-', $this->project->name);
        $fileName = 'BOQ - ' . $projectName . '.xlsx';

        return Excel::download(new BOQExport($this->boqs), $fileName);
    }

    protected function getFilteredAndSortedBoqList()
    {
        $filteredBoqList = $this->boqs->where('project_id', $this->project_id);

        $sortedBoqList = $filteredBoqList->sortBy(function ($boq) {
            $waitingForApproval = ($boq->approved_by === null || $boq->approved_by_2 === null || $boq->approved_by_3 === null) && $boq->rejected_by === null;
            $secondApproval = $boq->approved_by_2 === null && $boq->rejected_by === null;

            return [$boq->rejected_by !== null ? 1 : 0, !$waitingForApproval ? 1 : 0, !$secondApproval ? 1 : 0];
        });

        return $sortedBoqList;
    }

    public function dehydrate()
    {
        $this->boqList = $this->boqList->toArray();
    }

    public function hydrate()
    {
        $this->boqList = collect($this->boqList);
    }

    public function getGrandTotal()
    {
        $sortedBoqList = $this->boqList->where('project_id', $this->project_id);

        $total = 0;
        foreach ($sortedBoqList as $boq) {
            $price = $boq->price_estimation;
            $total += $price === 0 ? 0 : $price * $boq->qty;

            $matchFound = false;

            foreach ($this->prDetails as $prDetailItem) {
                if ($boq->item_id == $prDetailItem->item_id && $boq->qty == $prDetailItem->qty) {
                    $matchFound = true;
                    break;
                }
            }

            if ($matchFound) {
                $matchFound = true;
            }
        }

        return $total;
    }

    public function approve()
    {
        $cek_revision = $this->project->maxEditRevision();

        if ((bool) $this->setting->multiple_approval) {
            foreach ($this->selectedBoqIds as $key => $value) {
                if ($value) {
                    if ($cek_revision == null || $cek_revision == 0) {
                        $boq = BOQ::findOrfail($value);
                    } else {
                        $boq = BOQEdit::findOrfail($value);
                    }

                    $currentUserId = auth()->user()->id;

                    if ($boq->approved_by == $currentUserId || $boq->approved_by_2 == $currentUserId || $boq->approved_by_3 == $currentUserId) {
                        continue;
                    }

                    if ($boq->approved_by == null) {
                        $boq->approved_by = auth()->user()->id;
                        $boq->date_approved = date('Y-m-d H:i:s');
                    } elseif ($boq->approved_by_2 == null) {
                        $boq->approved_by_2 = auth()->user()->id;
                        $boq->date_approved_2 = date('Y-m-d H:i:s');
                    } else {
                        $boq->approved_by_3 = auth()->user()->id;
                        $boq->date_approved_3 = date('Y-m-d H:i:s');

                        $this->updateBoqVerificationStatusMultiple();
                    }

                    $boq->save();
                }
            }
        } else {
            foreach ($this->selectedBoqIds as $key => $value) {
                if ($value) {
                    if ($cek_revision == null || $cek_revision == 0) {
                        $boq = BOQ::findOrfail($value);
                    } else {
                        $boq = BOQEdit::findOrfail($value);
                    }

                    $boq->approved_by = auth()->user()->id;
                    $boq->date_approved = date('Y-m-d H:i:s');
                    $boq->approved_by_2 = auth()->user()->id;
                    $boq->date_approved_2 = date('Y-m-d H:i:s');

                    $this->updateBoqVerificationStatus();

                    $boq->save();
                }
            }
        }

        $recerver = User::withoutRole(Role::MANAGER);
        $data = [
            'project_name' => $this->project->name,
            'created_by' => auth()->user()->name,
            'location' => $this->project->id,
        ];

        $this->sendNotification($data, $recerver, BoqApproved::class);

        return redirect()->route('capex-expense.boq', $this->project_id)->with('success', 'All Item has been approved.');
    }

    public function updateBoqVerificationStatusMultiple(): void
    {
        $project = Project::findOrfail($this->project->id);

        $hasPendingApproval = DB::table($this->boqTable)
            ->where('project_id', $this->project->id)
            ->whereNull('deleted_at')
            ->whereNull('rejected_by')
            ->where(function ($query) {
                $query->whereNull('approved_by');
                if ($this->setting->multiple_approval) {
                    $query->orWhereNull('approved_by_2')->orWhere(function ($subQuery) {
                        $subQuery->whereNotNull('approved_by_2')->whereNull('approved_by_3');
                    });
                }
            })
            ->count();

        if ($hasPendingApproval == 0) {
            $project->boq = 1;
            $project->boq_verification = 0;
            $project->save();
        }
    }

    public function updateBoqVerificationStatus(): void
    {
        $project = Project::findOrfail($this->project->id);

        $hasPendingApproval = DB::table($this->boqTable)
            ->where('project_id', $this->project->id)
            ->whereNull('deleted_at')
            ->whereNull('rejected_by')
            ->where('revision', $this->max_version)
            ->where(function ($query) {
                $query->whereNull('approved_by');
                if ($this->setting->multiple_approval) {
                    $query->WhereNull('approved_by_2');
                }
            })
            ->count();

        if ($hasPendingApproval == 0) {
            $project->boq = 1;
            $project->boq_verification = 0;
            $project->save();
        }
    }

    public function print($taskId)
    {
        $getPr = PurchaseRequest::with('project')->where('is_task', 0)->get();

        $this->prData = collect($getPr)
            ->sortByDesc('created_at')
            ->groupBy(function ($item) {
                return $item['pr_no'];
            });

        foreach ($this->prData as $prNo => $pr) {
            foreach ($pr as $prItem) {
                foreach ($prItem->prdetail as $prDetail) {
                    if (!is_null($prDetail->item->rfa)) {
                        $rfaData = json_decode($prDetail->item->rfa, true);
                        foreach ($rfaData as $rfa) {
                            if ($rfa['id'] == $prItem->project->id) {
                                $prDetail->is_rfa_exist = true;
                            }
                        }
                    }

                    foreach ($prDetail->podetail as $poDetail) {
                        $existInventory = Inventory::where('project_id', $poDetail->prdetail->purchaseRequest->project_id)
                            ->where('item_id', $poDetail->item_id)
                            ->where('task_id', $poDetail->prdetail->purchaseRequest->task->id)
                            ->first();

                        if (!isset($this->actualInput[$poDetail->id]) && !is_null($existInventory)) {
                            $this->actualInput[$poDetail->id] = $existInventory->actual_qty;
                        }
                    }
                }
            }
        }

        return view('livewire.task-monitoring-print', [
            'prData' => $this->prData,
        ]);
    }

    public function reject()
    {
        try {
            $latest_data = BOQSpreadsheet::where('project_id', $this->project->id)->latest()->first();

            if ($latest_data) {
                $latest = $latest_data->toArray();
                $latest = json_decode($latest['data'], true);
            } else {
                $latest = [];
            }

            $cek_revision = $this->project->maxEditRevision();

            foreach ($this->selectedBoqIds as $key => $value) {
                if ($value) {
                    if ($cek_revision == null || $cek_revision == 0) {
                        $boq = BOQ::findOrfail($value);
                    } else {
                        $boq = BOQEdit::findOrfail($value);
                    }

                    $item_qty = null;
                    foreach ($latest as $item) {
                        if ($item[0] == $boq->item_id) {
                            $item_qty = $item[3];
                            break;
                        }
                    }

                    if ($boq->approved_by == null || $boq->approved_by_2 == null || $boq->approved_by_3 == null) {
                        $boq->qty = $item_qty != null ? $boq->qty - $item_qty : $boq->qty;
                        $boq->rejected_by = auth()->user()->id;
                        $boq->approved_by = null;
                        $boq->approved_by_2 = null;
                        $boq->approved_by_3 = null;
                        $boq->save();
                    }
                }
            }

            $recerver = User::withoutRole(Role::MANAGER);
            $data = [
                'project_name' => $this->project->name,
                'created_by' => auth()->user()->name,
                'location' => $this->project->id,
                'category' => 'Edit',
            ];

            $this->sendNotification($data, $recerver, BOQRejected::class);

            $this->emitSelf('refresh');
            return redirect()->route('capex-expense.boq', $this->project_id);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function createPR()
    {
        try {
            $currentuser = Auth::user();

            $purchaserequest = PurchaseRequest::create([
                'pr_no' => null,
                'pr_type' => $this->type,
                'project_id' => $this->project_id,
                'is_task' => 0,
                'warehouse_id' => 0,
                'requester' => $this->requester,
                'partof' => $this->project->project_type,
                'status' => 'Draft',
                'remark' => $this->remark,
                'city' => 'Jakarta',
                'created_by' => $currentuser->id,
            ]);

            HistoryPurchase::create([
                'action_start' => 'New Draft PR',
                'action_end' => 'New Draft PR',
                'referensi' => null,
                'action_by' => $currentuser->id,
                'created_by' => $currentuser->id,
                'action_date' => Carbon::now(),
                'created_at' => Carbon::now(),
            ]);

            $purches = User::role([Role::PURCHASING, Role::IT])->get();

            foreach ($purches as $pur) {
                Notification::send(
                    $pur,
                    new PurchaseRequestCreated([
                        'pr_no' => $purchaserequest->pr_no,
                        'pr_detail' => $purchaserequest->id,
                        'created_by' => $currentuser->name,
                    ]),
                );
            }

            return redirect()->route('capex-expense-pr.index', $purchaserequest->id)->with('success', 'Purchase Request Destination has been created successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function filterByPurchaseStatus($boqList, $purchased)
    {
        $dataArray = $this->boqsArray;

        $filteredData = collect($dataArray)
            ->filter(function ($item) use ($purchased) {
                $poStatusExists = data_get($item, 'po_status') !== null;
                return $purchased ? $poStatusExists : !$poStatusExists;
            })
            ->pluck('item_id');

        return $boqList->filter(function ($boq) use ($filteredData) {
            return $filteredData->contains($boq->item_id);
        });
    }

    public function loadMore()
    {
        $this->perPage += 10;
        $this->boqList = $this->getFilteredAndSortedBoqList()->slice(0, $this->perPage);
        $this->emit('itemsUpdated', count($this->boqList));
    }

    public function render()
    {
        if ($this->project->status_boq) {
            $this->adendum = true;
        }

        $boqList = $this->boqs;

        if ($this->project->boq_verification === 1 && $this->sortBy === null) {
            $boqList = $boqList->sortBy(function ($item) {
                $hasNull = is_null($item['approved_by']) || is_null($item['approved_by_2']) || is_null($item['approved_by_2']);

                return [$hasNull, $item['approved_by'], $item['approved_by_2'], $item['approved_by_3']];
            });
        } elseif ($this->sortBy == 'created_at') {
            $boqList = $this->boqs->sortBy('created_at');
        } else {
            $boqList = $this->boqs->sortBy(function ($boq) {
                return $boq->item ? $boq->item->name : $boq->id;
            });
        }

        switch ($this->filter) {
            case 'approved':
                $boqList = $boqList->filter(function ($boq) {
                    if ($this->setting->multiple_approval) {
                        return $boq->approved_by !== null && $boq->approved_by_2 !== null;
                    } else {
                        return $boq->approved_by !== null;
                    }
                });
                break;

            case 'waiting_for_approval':
                $boqList = $boqList->filter(function ($boq) {
                    if ($boq->rejected_by !== null) {
                        return false;
                    }

                    if ($this->setting->multiple_approval) {
                        return $boq->approved_by === null || $boq->approved_by_2 === null;
                    } else {
                        return $boq->approved_by === null;
                    }
                });
                break;

            case 'rejected':
                $boqList = $boqList->filter(function ($boq) {
                    return $boq->rejected_by !== null;
                });
                dd($boqList);
                break;

            case 'unpurchased':
                $boqList = $this->filterByPurchaseStatus($boqList, false);
                break;

            case 'purchased':
                $boqList = $this->filterByPurchaseStatus($boqList, true);
                break;

            case 'all':
            default:
                break;
        }

        $filteredBoqList = $boqList->where('project_id', $this->project_id);

        $this->countBoqIsApprovedFirst = $filteredBoqList->whereNull('approved_by')->whereNull('date_approved')->whereNull('rejected_by')->count();

        $this->countBoqIsApprovedSecond = $filteredBoqList->whereNull('approved_by_2')->whereNull('date_approved_2')->whereNull('rejected_by')->count();

        $this->countBoqIsApprovedThird = $filteredBoqList->whereNull('approved_by_3')->whereNull('date_approved_3')->whereNull('rejected_by')->count();
        $this->task_boq = $filteredBoqList;

        $sortedBoqList = $filteredBoqList->sortBy(function ($boq) {
            $waitingForApproval = $boq->approved_by === null && $boq->approved_by_2 === null && $boq->rejected_by === null;
            $secondApproval = $boq->approved_by_2 === null && $boq->rejected_by === null;

            return [$boq->rejected_by !== null ? 1 : 0, !$waitingForApproval ? 1 : 0, !$secondApproval ? 1 : 0];
        });

        $countBoqList = count($sortedBoqList);
        $this->countBoqList = $countBoqList;

        $this->boqList = $sortedBoqList;

        $boqSpreadsheet = BOQSpreadsheet::where('project_id', $this->project_id)
            ->where('status', '!=', 'Draft')
            ->when(!auth()->user()->hasTopLevelAccess() && !auth()->user()->hasK3LevelAccess(), function ($query) {
                return $query->where('user_id', auth()->user()->id);
            })
            ->get();

        $this->dataSpreadsheet = $boqSpreadsheet;

        $prDetail = PurchaseRequest::where('project_id', $this->project_id)->get();

        $this->prDetails = collect();

        foreach ($prDetail as $pr) {
            $this->prDetails = $this->prDetails->merge($pr->purchaseRequestDetails);
        }

        $total = 0;
        foreach ($sortedBoqList as $boq) {
            $price = $boq->price_estimation;
            $total += $price === 0 ? 0 : $price * $boq->qty;

            $matchFound = false;

            foreach ($this->prDetails as $prDetailItem) {
                if ($boq->item_id == $prDetailItem->item_id && $boq->qty == $prDetailItem->qty) {
                    $matchFound = true;
                    break;
                }
            }

            if ($matchFound) {
                $matchFound = true;
            }
        }

        $this->total = $total;

        return view('livewire.capex-expense.capex-expense-boq', [
            'matchFound' => isset($matchFound),
            'countBoqList' => $countBoqList,
        ]);
    }
}
