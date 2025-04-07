<?php

namespace App\Http\Livewire;

use App\Exports\BOQExport;
use App\Models\BOQ;
use App\Models\BOQEdit;
use App\Models\BOQSpreadsheet;
use App\Models\HistoryPurchase;
use App\Models\Inventory;
use App\Models\Project;
use App\Models\PurchaseRequest;
use App\Models\Setting;
use App\Models\Task;
use App\Models\TaskEngineerDrawing;
use App\Models\User;
use App\Notifications\BoqApproved;
use App\Notifications\BOQRejected;
use App\Notifications\PurchaseRequestCreated;
use App\Roles\Role;
use App\Traits\NotificationManager;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class TaskMonitoringBoq extends Component
{
    use WithPagination;
    use WithFileUploads;
    use NotificationManager;

    public $project;
    public $taskData;
    public $drawingData;
    public $isUploaded;
    public $countPurchaseRequestDetail;
    public $boqList;
    public $allBoqList;
    public $countPurchaseRequestPrNo;
    public $version;
    public $adendum;
    public $section;
    public $needToApprove;
    public $countBoqIsApprovedFirst;
    public $countBoqIsApprovedSecond;
    public $countBoqIsApprovedThird;
    public $countBoqList;
    public $istaskconsumbales;
    public $sections;
    public $newTaskEngineerDrawing;
    public $boqsArray;
    public $dataSpreadsheet;
    public $setting;
    public $max_version;
    public $total;
    public $sortBy, $filter;
    public $search = '';
    public $show_version,
        $check_approval = [],
        $task,
        $task_boq;
    public $engineerDrawing;
    public $taskEngineerDrawing;
    public $subTaskEngineerDrawing;
    public $filterTaskEngineerDrawing;
    public $description;
    public $descriptionDrawing;
    public $prDetails;
    public $checkPurchaseRequest;
    public $boqTable;
    public $boqs;
    public $requestAccessCount;
    public $boqRequestsCount;
    public $prData;
    public $taskName;

    protected $paginationTheme = 'bootstrap';

    public $selectedBoqIds = [];

    public $perPage = 10;

    protected $listeners = [
        'refresh' => '$refresh',
        'updateSelectedBoqIds',
        'loadMore'
    ];

    public function updateSelectedBoqIds($ids, $action)
    {
        $this->selectedBoqIds = $ids;

        if ($action == 'approve') {
            $this->approve();
        } else if ($action == 'reject') {
            $this->reject();
        }
    }

    public function mount($taskData)
    {
        $this->taskData = $taskData;

        $this->project = Project::find($this->taskData->project_id);
        $project = $this->project;

        $this->checkPurchaseRequest = PurchaseRequest::where('partof', $this->taskData->task_number)->exists();

        $max_revision = $this->project->maxEditRevision();
        $this->setting = Setting::first();

        $this->max_version = (int)$max_revision;

        if ($this->show_version == null) {
            if (!is_null($max_revision)) {
                $this->boqTable = 'b_o_q_edits';

                $this->version[] = 0;
                for ($i = 1; $i <= $max_revision; $i++) {
                    $this->version[] = $i;
                }

                $this->show_version = (int)$max_revision;
            } else {
                $this->boqTable = 'b_o_q_s';
                $this->version[] = 0;
                $this->show_version = 0;
            }

            $this->boqs = $project->boqs_list()->load('item');

            $this->allBoqList = $this->getFilteredAndSortedBoqList();
            $this->boqList = $this->allBoqList->slice(0, $this->perPage);
            $this->countBoqList = $this->allBoqList->count();

            $this->boqsArray = collect($this->boqs->toArray());
            $this->needToApprove = $project->isBoqApprovedTaskNumber($this->boqTable, $this->show_version, $this->taskData->task_number);
        }

        $this->requestAccessCount = $project->boq_access()->where('status', 'pending')->count();
        $this->boqRequestsCount = $project
            ->boq_requests()
            ->where('status', 'Submitted')
            ->where('task_id', $this->taskData->id)
            ->count();

        $this->countPurchaseRequestDetail = PurchaseRequest::where('partof', $this->taskData->task_number)
            ->doesntHave('prdetail')
            ->count();

        $this->countPurchaseRequestPrNo = PurchaseRequest::where('partof', $this->taskData->task_number)
            ->whereNull('pr_no')
            ->count();

        $this->taskEngineerDrawing = TaskEngineerDrawing::where('task_id', $this->taskData->id)->get();

        $this->subTaskEngineerDrawing = TaskEngineerDrawing::where('task_id', $this->taskData->id)->get();
        $this->descriptionDrawing = $this->subTaskEngineerDrawing->first()->description ?? '';

        $this->filterTaskEngineerDrawing = $this->subTaskEngineerDrawing->filter(function ($item) {
            return is_null($item->section) && is_null($item->description);
        });

        $this->newTaskEngineerDrawing = $this->subTaskEngineerDrawing->filter(function ($item) {
            return !is_null($item->section) && !is_null($item->description);
        });

        $this->drawingData = TaskEngineerDrawing::where('task_id', $this->taskData->id)->orderBy('created_at', 'desc')->first();

        $this->isUploaded = $this->drawingData ? $this->drawingData->status_uploaded : false;
    }

    protected function getFilteredAndSortedBoqList()
    {
        $query = $this->boqs->where('task_number', $this->taskData->task_number);

        if (!empty($this->search)) {
            $query = $query->filter(function ($boq) {
                return stripos($boq->item->name, $this->search) !== false; 
            });
        }

        $sortedBoqList = $query->sortBy(function ($boq) {
            $waitingForApproval = ($boq->approved_by === null || $boq->approved_by_2 === null || $boq->approved_by_3 === null) && $boq->rejected_by === null;
            $secondApproval = $boq->approved_by_2 === null && $boq->rejected_by === null;

            return [
                $boq->rejected_by !== null ? 1 : 0,  // Rejected items last
                !$waitingForApproval ? 1 : 0,        // Waiting for approval next
                !$secondApproval ? 1 : 0             // Second approval last
            ];
        });

        return $sortedBoqList;
    }

    public function updatedSearch()
    {
        $this->allBoqList = $this->getFilteredAndSortedBoqList();
        $this->resetPage();
    }

    public function dehydrate()
    {
        $this->boqList = $this->boqList->toArray();
    }

    public function hydrate()
    {
        $this->boqList = collect($this->boqList);
    }

    public function updateSection($section)
    {
        $this->section = (int)$section;

        $this->taskEngineerDrawing = TaskEngineerDrawing::where('task_id', $this->taskData->id)
            ->where('section', $this->section)
            ->get();

        $this->descriptionDrawing = $this->taskEngineerDrawing->first()->description ?? '';

        $this->newTaskEngineerDrawing = $this->taskEngineerDrawing->filter(function ($item) {
            return !is_null($item->section) && !is_null($item->description);
        });

        $this->perPage = 10;

        $this->emitSelf('refresh');
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

    public function finishTask($taskId): void
    {
        DB::beginTransaction();
        try {
            $task = Task::find($taskId);

            if ($task) {
                $finishDate = Carbon::parse($task->finish_date);
                $completionDate = Carbon::now();
                $deviation = $completionDate->diffInDays($finishDate);

                $task->deviasi = $deviation;
                $task->date_of_completion = $completionDate;
                $task->status = 'Finish';
                $task->save();

                $this->emitSelf('refresh');
                session()->flash('success', 'Task finished and deviation updated.');
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    public function checkAll($isChecked)
    {
        $allIds = collect($this->task_boq)
            ->filter(function ($value) {
                if ($this->setting->multiple_approval) {
                    return ($value->approved_by == null || $value->approved_by_2 == null || $value->approved_by_3 == null) && $value->rejected_by == null;
                } else {
                    return ($value->approved_by == null || $value->approved_by_2 == null) && $value->rejected_by == null;
                }
            })
            ->pluck('id')
            ->toArray();

        if ($isChecked) {
            $this->selectedBoqIds = array_unique(array_merge($this->selectedBoqIds, $allIds));
        } else {
            $this->selectedBoqIds = array_diff($this->selectedBoqIds, $allIds);
        }

        $this->emit('updateSelectedBoqIds', $this->selectedBoqIds);
    }

    public function approve()
    {
        $cek_revision = $this->project->maxEditRevision();

        if ((bool)$this->setting->multiple_approval) {
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
            // 'category' => $boq->category,
        ];

        $this->sendNotification($data, $recerver, BOQApproved::class);

        return redirect()
            ->route('task-monitoring.index', $this->taskData->id)
            ->with('success', 'All Item has been approved.');
    }

    public function updateBoqVerificationStatusMultiple(): void
    {
        $project = Project::findOrfail($this->project->id);

        $hasPendingApproval = DB::table($this->boqTable)
            ->where('project_id', $this->project->id)
            ->where('task_number', $this->taskData->task_number)
            ->whereNull('deleted_at')
            ->whereNull('rejected_by')
            ->where(function ($query) {
                $query->whereNull('approved_by');
                if ($this->setting->multiple_approval) {
                    $query->orWhereNull('approved_by_2')
                        ->orWhere(function ($subQuery) {
                            $subQuery->whereNotNull('approved_by_2')
                                ->whereNull('approved_by_3');
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

    public function createPR(Request $request)
    {
        // $arrtodb = [];

        try {
            // foreach ($this->userarray as $value) {
            //     array_push($arrtodb, $value['id']);
            // }

            $currentuser = Auth::user();

            $purchaserequest = PurchaseRequest::create([
                'pr_no' => null,
                'pr_type' => $request->type,
                'project_id' => $request->project_id,
                'partof' => $request->task_number == 'retail' ? 'retail' : $request->task_number,
                'is_task' => $request->task_number == 'retail' || $request->task_number == null ? 0 : 1,
                'warehouse_id' => 0,
                'requester' => $request->requester,
                'status' => 'Draft',
                'remark' => $request->remark,
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

            if($request->project_id === null) {
                return redirect()->route('raw-material-pr.index', $purchaserequest->id);
            }
            return redirect()
                ->to('/itemprindex/' . $purchaserequest->id . '?firstcreate=yes')
                ->with('success', 'Purchase Request Destination has been created successfully.');
        } catch (Exception $e) {
            dd($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function print($taskId)
    {
        $task = Task::where('id', $taskId)->first();

        $getPr = PurchaseRequest::with('project')
            ->where('is_task', 1)
            ->where('partof', $task->task_number)
            ->get();

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
            'task' => $task,
        ]);
    }

    public function reject()
    {
        try {
            $latest_data = BOQSpreadsheet::where('project_id', $this->project->id)
                ->where('task_number', $this->taskData->task_number)
                ->latest()
                ->first();

            if ($latest_data) {
                $latest = $latest_data->toArray();
                $latest = json_decode($latest['data'], true);
            } else {
                $latest = [];
            }

            $cek_revision = $this->project->maxEditRevision();

            // $this->validate(
            //     [
            //         'check_approval' => ['required', 'array'],
            //     ],
            //     [],
            //     [
            //         'check_approval' => 'Approval',
            //     ],
            // );

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
            // $this->updateBoqVerificationStatusMultiple();

            $this->emitSelf('refresh');
            return redirect()->route('task-monitoring.index', $this->taskData->id);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function export_boq()
    {
        $invalidCharacters = ['/', '\\', ':', '*', '?', '"', '\'', '|', '<', '>'];
        $projectName = str_replace($invalidCharacters, '-', $this->project->name);
        $fileName = 'BOQ - ' . $projectName . '.xlsx';

        return Excel::download(new BOQExport($this->boqs), $fileName);
    }

    public function uploadFileEngineerDrawing()
    {
        $this->validate([
            'engineerDrawing' => 'required|file|mimes:pdf,jpg,png|max:10240',
            'description' => 'required',
        ]);

        $originalFileName = $this->engineerDrawing->getClientOriginalName();

        $file = $this->engineerDrawing->store('engineer-drawings', 'public');

        $countTaskEngineerDrawing = TaskEngineerDrawing::where('task_id', $this->taskData->id)->max('section');

        $taskExists = TaskEngineerDrawing::where('task_id', $this->taskData->id)->exists();

        if ($taskExists) {
            $newSection = $countTaskEngineerDrawing + 1;
        } else {
            $newSection = 0;
        }

        TaskEngineerDrawing::create([
            'task_id' => $this->taskData->id,
            'file' => $file,
            'original_filename' => $originalFileName,
            'status_uploaded' => false,
            'description' => $this->description,
            'section' => $newSection,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->reset(['engineerDrawing', 'description']);

        session()->flash('message', 'File Engineer Drawing berhasil diupload.');

        return redirect()->route('task-monitoring.index', $this->taskData->id);
    }

    public function loadMore()
    {
        $this->perPage += 10;
        $this->boqList = $this->getFilteredAndSortedBoqList()->slice(0, $this->perPage);
        $this->emit('itemsUpdated', count($this->boqList));
    }

    public function render()
    {
        $taskList = Task::where('project_id', $this->taskData->project_id)
            ->where('id', '!=', $this->taskData->id)
            ->where('status', '!=', 'Finish')
            ->get();

        $this->taskName = substr($this->taskData->task_number, -2) . ' - ' . $this->taskData->task;

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

        // if ($this->search) {
        //     $search = strtolower($this->search);
        //     $boqList = $boqList->filter(function ($boq) use ($search) {
        //         return stripos(strtolower($boq->item->name), $search) !== false || stripos(strtolower($boq->wbs), $search) !== false;
        //     });
        // }

        $this->sections = TaskEngineerDrawing::where('task_id', $this->taskData->id)
            ->select('section', 'updated_at')
            ->distinct()
            ->get();

        $filteredBoqList = $boqList->where('task_number', $this->taskData->task_number);

        $this->countBoqIsApprovedFirst = $filteredBoqList->whereNull('approved_by')->whereNull('date_approved')->whereNull('rejected_by')->count();

        $this->countBoqIsApprovedSecond = $filteredBoqList->whereNull('approved_by_2')->whereNull('date_approved_2')->whereNull('rejected_by')->count();

        $this->countBoqIsApprovedThird = $filteredBoqList->whereNull('approved_by_3')->whereNull('date_approved_3')->whereNull('rejected_by')->count();
        $this->task_boq = $filteredBoqList;

        $sortedBoqList = $filteredBoqList->sortBy(function ($boq) {
            $waitingForApproval = $boq->approved_by === null && $boq->approved_by_2 === null && $boq->rejected_by === null;
            $secondApproval = $boq->approved_by_2 === null && $boq->rejected_by === null;

            return [
                $boq->rejected_by !== null ? 1 : 0,  // Rejected items last
                !$waitingForApproval ? 1 : 0,        // Waiting for approval next
                !$secondApproval ? 1 : 0             // Second approval last
            ];
        });

        $countBoqList = count($sortedBoqList);
        $this->countBoqList = $countBoqList;

        $this->boqList = $sortedBoqList;

        $projectId = $this->project->id;

        $boqSpreadsheet = BOQSpreadsheet::where('project_id', $projectId)
            ->where('status', '!=', 'Draft')
            ->when(!auth()->user()->hasTopLevelAccess() && !auth()->user()->hasK3LevelAccess(), function ($query) {
                return $query->where('user_id', auth()->user()->id);
            })
            ->get();

        $this->dataSpreadsheet = $boqSpreadsheet;

        $prDetail = PurchaseRequest::where('partof', $this->taskData->task_number)->get();
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

        $isTaskConsumbales = str_contains($this->taskData->task_number, '/00/00') || str_contains($this->taskData->task, 'Indent');

        return view('livewire.task-monitoring-boq', [
            'istaskconsumbales' => $isTaskConsumbales,
            'matchFound' => isset($matchFound),
            'countBoqList' => $countBoqList,
            'taskList' => $taskList,
            'taskName' => $this->taskName,
        ]);
    }

    public function getGrandTotal($section)
    {
        $sortedBoqList = $this->boqList->where('section', $section);

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
}
