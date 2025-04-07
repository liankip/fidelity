<?php

namespace App\Http\Livewire;

use App\Exports\BOQExport;
use App\Models\BOQ;
use App\Models\BOQAccess;
use App\Models\BOQEdit;
use App\Models\BOQSpreadsheet;
use App\Models\Project;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestDetail;
use App\Models\PurchaseOrderDetail;
use App\Models\Setting;
use App\Models\Task;
use App\Models\Unit;
use App\Models\User;
use App\Notifications\BOQAccessApproval;
use App\Notifications\BOQApproval;
use App\Notifications\BoqApproved;
use App\Notifications\BOQRejected;
use App\Roles\Role;
use App\Traits\AuthorizesRequests;
use App\Traits\NotificationManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class BOQController extends Component
{
    use AuthorizesRequests, NotificationManager;

    public $project, $boqs, $version = [], $show_version, $max_version, $check_approval = [];
    public $adendum;

    public $showModal = false;

    public $boqTable;

    public Setting $setting;
    public $select_all;
    public $sortBy, $filter;
    public $needToApprove = false;
    public $boqsArray = [];

    public $loading = false;

    public $requestAccessCount = 0;
    public $boqRequestsCount = 0;
    public $search = '';
    public $task;

    public function mount(Project $project, $wbs)
    {
        $max_revision = $project->maxEditRevision();
        $this->setting = Setting::first();

        $this->project = $project;
        $this->max_version = (int)$max_revision;
        $this->task = Task::find($wbs);

        if ($this->show_version == Null) {
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

            $this->boqs = $project->boqs_list();

            $this->boqsArray = collect($this->boqs->toArray());
            $this->needToApprove = $project->isBoqApproved($this->boqTable, $this->show_version);
        }

        $this->requestAccessCount = $project->boq_access()->where('status', 'pending')->count();
        $this->boqRequestsCount = $project->boq_requests()->where('status', 'Submitted')->where('task_id', $this->task->id)->count();
    }

    public function render()
    {
        if ($this->project->status_boq) {
            $this->adendum = true;
        }

        $boqList = $this->boqs;

        if ($this->project->boq_verification === 1 && $this->sortBy === null) {
            $boqList = $boqList->sortBy(function ($item) {
                $hasNull = is_null($item['approved_by']) || is_null($item['approved_by_2']);
                return [$hasNull, $item['approved_by'], $item['approved_by_2']];
            });
        } else if ($this->sortBy == 'created_at') {
            $boqList = $this->boqs->sortBy(function ($boq, $key) {
                return $boq->created_at;
            });
        } else {
            $boqList = $this->boqs->sortBy(function ($boq, $key) {
                if ($boq->item) {
                    return $boq->item->name;
                }
                return $boq->id;
            });
        }

        switch ($this->filter) {
            case 'approved':
                $boqList = $this->boqs->filter(function ($boq, $key) {
                    if ($this->setting->multiple_approval)
                        return $boq->approved_by != null && $boq->approved_by_2 != null;
                    else
                        return $boq->approved_by != null;
                });
                break;
            case 'waiting_for_approval':
                $boqList = $this->boqs->filter(function ($boq, $key) {
                    if ($boq->rejected_by != null)
                        return false;

                    if ($this->setting->multiple_approval)
                        return $boq->approved_by == null || $boq->approved_by_2 == null;
                    else
                        return $boq->approved_by == null;
                });
                break;
            case 'all':
                $boqList = $this->boqs;
                break;
            case 'rejected':
                $boqList = $this->boqs->filter(function ($boq, $key) {
                    return $boq->rejected_by != null;
                });
                break;
            case 'unpurchased':
                $dataArray = $this->boqsArray;
                $filteredData = collect($dataArray)->filter(function ($item) {
                    return data_get($item, 'po_status') === null;
                })->pluck('item_id');

                $boqList = $this->boqs->filter(function ($boq) use ($filteredData) {
                    return in_array($boq->item_id, $filteredData->toArray());
                });
                break;
            case 'purchased':
                $dataArray = $this->boqsArray;
                $filteredData = collect($dataArray)->filter(function ($item) {
                    return data_get($item, 'po_status') !== null;
                })->pluck('item_id');

                $boqList = $this->boqs->filter(function ($boq) use ($filteredData) {
                    return in_array($boq->item_id, $filteredData->toArray());
                });
                break;
            default:
                // Sort alphabetically by item name
                $boqList = $boqList->sortBy(function ($boq) {
                    if ($boq->item) {
                        return $boq->item->name;
                    }
                    return $boq->id;
                });
                break;
        }

        $search = $this->search;
        if ($search) {
            $findProject = Project::find($this->project->id);
            $boqsIn = $findProject->boqs_list();

            $boqList = $boqsIn->filter(function ($boq) use ($search) {
                return stripos($boq->item->name, $search) !== false;
            });
        }

        $filteredBoqList = $boqList->where('task_number', $this->task->task_number);

        $sortedBoqList = $filteredBoqList->sortByDesc(function ($boq) {
            $waitingForApproval = $boq->approved_by === null && $boq->approved_by_2 === null && $boq->rejected_by === null;
            $secondApproval = $boq->approved_by_2 === null && $boq->rejected_by === null;
            return [$waitingForApproval, $secondApproval];
        });

        $projectId = $this->project->id;

        if (auth()->user()->hasTopLevelAccess() || auth()->user()->hasK3LevelAccess()) {
            $boqSpreadsheet = BOQSpreadsheet::where('project_id', $projectId)->where('status', '!=', 'Draft')->get();
        } else {
            $boqSpreadsheet = BOQSpreadsheet::where('project_id', $projectId)->where('user_id', auth()->user()->id)->where('status', '!=', 'Draft')->get();
        }

        return view('masterdata.boqs.index', [
            'boqList' => $sortedBoqList,
            'dataSpreadsheet' => $boqSpreadsheet
        ]);
    }

    public function change_version()
    {
        if ($this->show_version == 0) {
            $this->boqs = $this->project->boqs_not_approved()->where('deleted_at', null)->get();
        } else {
            $this->boqs = $this->project->boqs_edit_not_approved()->where('revision', $this->show_version)->where('deleted_at', null)->get();
        }
    }

    public function export_boq()
    {
        $invalidCharacters = ['/', '\\', ':', '*', '?', '"', '\'', '|', '<', '>'];
        $projectName = str_replace($invalidCharacters, '-', $this->project->name);
        $fileName = 'BOQ - ' . $projectName . '.xlsx';

        return Excel::download(new BOQExport($this->boqs), $fileName);
    }

    public function boq()
    {
        if ($this->show_version == Null) {
            if ($this->project->boqs_edit_not_approved()->count() > 0) {

                $max_revision = $this->project->boqs_edit_not_approved()->max('revision');
                $this->boqs = $this->project->boqs_edit_not_approved()->where('revision', $max_revision)->where('deleted_at', null)->get();
            } else {
                $this->boqs = $this->project->boqs_not_approved()->where('deleted_at', null)->get();
            }
        } else {
            if ($this->show_version == 0) {
                $this->boqs = $this->project->boqs_not_approved()->where('deleted_at', null)->get();
            } else {
                $this->boqs = $this->project->boqs_edit_not_approved()->where('revision', $this->show_version)->where('deleted_at', null)->get();
            }
        }
    }

    public function checkAll()
    {
        foreach ($this->boqs as $key => $value) {
            if ($this->setting->multiple_approval) {
                if (($value->approved_by != null && $value->approved_by_2 != null) || $value->rejected_by != null) {
                    $this->check_approval[$value->id] = false;
                } else {
                    $this->check_approval[$value->id] = $this->select_all;
                }
            } else {
                if (($value->approved_by != null || $value->approved_by_2 != null) || $value->rejected_by != null) {
                    $this->check_approval[$value->id] = false;
                } else {
                    $this->check_approval[$value->id] = $this->select_all;
                }
            }
        }
    }

    public function request_access()
    {
        $access = BOQAccess::where('project_id', $this->project->id)->where('user_id', auth()->user()->id)->where('status', 'approved')->first();

        if (!auth()->user()->hasTopLevelAccess() && !$access) {
            $this->emit('openModal', [
                'name' => 'boq.request-access-modal',
                'arguments' => [
                    'project_id' => $this->project->id,
                    'title' => 'Request Access',
                    'content' => 'Apakah anda yakin ingin request access untuk project ini?',
                ]
            ]);
        }
    }

    public function show_adendum($id)
    {
        $access = BOQAccess::where('project_id', $this->project->id)->where('user_id', auth()->user()->id)->where('status', 'approved')->first();

        if (!auth()->user()->hasTopLevelAccess() && !$access) {
            $this->emit('openModal', [
                'name' => 'boq.request-access-modal',
                'arguments' => [
                    'project_id' => $this->project->id,
                ]
            ]);
            return;
        }

        $cek_revision = $this->project->maxEditRevision();

        if ($cek_revision == null || $cek_revision == 0) {
            $cek_revision = 1;
        } else {
            $cek_revision = $cek_revision + 1;
        }

        if ($cek_revision == 1) {
            $boqs = $this->project->boqs_not_approved()->where('deleted_at', null)->get();
        } else {
            $boqs = $this->project->boqs_edit_not_approved()->where('revision', $cek_revision - 1)->where('deleted_at', null)->get();
        }

        if ($id == 1) {
            $category = "Adendum";
        } elseif ($id == 2) {
            $category = "Edit";
        } else {
            return abort(404);
        }

        foreach ($boqs as $boq) {
            BOQEdit::create([
                'no_boq' => $boq->no_boq,
                'project_id' => $boq->project_id,
                'item_id' => $boq->item_id,
                'unit_id' => $boq->unit_id,
                'qty' => $boq->qty,
                'price_estimation' => $boq->price_estimation,
                'shipping_cost' => $boq->shipping_cost,
                'origin' => $boq->origin,
                'destination' => $boq->destination,
                'note' => $boq->note,
                'revision' => $cek_revision,
                'category' => $category,
                'rejected_by' => $boq->rejected_by,
                'approved_by' => $boq->approved_by,
                'date_approved' => $boq->date_approved,
                'approved_by_2' => $boq->approved_by_2,
                'date_approved_2' => $boq->date_approved_2,
                'created_by' => auth()->user()->id,
            ]);
        }

        $this->adendum = true;
        $this->project->status_boq = 1;
        $this->project->save();

        return redirect(request()->header('Referer'));
    }

    public function hide_adendum()
    {
        $cek_revision = $this->project->maxEditRevision();
        $boqCount = $this->project->boqCountWaitingApproval($this->setting->multiple_approval);

        if ($boqCount > 0) {
            $this->project->boq_verification = 1;
        }

        $this->adendum = false;
        $this->project->status_boq = 0;
        $this->project->save();

        $datauser = User::role([Role::MANAGER, Role::IT])->get();

        $category = null;
        if ($cek_revision == null || $cek_revision == 0) {
            $category = BOQEdit::where('project_id', $this->project->id)->where('revision', $cek_revision)->first();
        }

        $data = [
            "project_name" => $this->project->name,
            "created_by" => auth()->user()->name,
            "location" => $this->project->id,
            "editor" => auth()->user()->name,
            "category" => $category ? $category->category : '',
        ];
        $this->sendNotification($data, $datauser, BOQApproval::class);

        if (!auth()->user()->hasTopLevelAccess()) {
            $this->project->removeBoqAccess();
        }

        return redirect()->route('boq.index', ['project' => $this->project->id, 'wbs' => $this->wbs->id]);
    }

    public function approve($boq)
    {
        $cek_revision = BOQEdit::where('project_id', $this->project->id)->max('revision');

        if ($cek_revision == null || $cek_revision == 0) {
            $boq = BOQ::findOrfail($boq);
        } else {
            $boq = BOQEdit::findOrfail($boq);
        }

        $itemname = [$boq->item];
        $linkdetail = url("boq/" . $this->project->id);
        $msg = "*" . config('app.company', 'SNE') . " ERP* \n\nItem BOQ telah Di approve\nProject: " . $this->project->name . "\nItem: " . $boq->item->name . "\nAction by: " . auth()->user()->name . "\nCheck detail: " . $linkdetail . "\n\n_Ini adalah pesan otomatis. Simpan nomor ini sebagai contact agar URL pada pesan dapat di-klik._";

        $recerver = User::withoutRole(Role::MANAGER);
        $data = [
            "project_name" => $this->project->name,
            "items" => $itemname,
            "created_by" => auth()->user()->name,
            "location" => $this->project->id,
        ];

        if ($boq->approved_by == null) {

            $boq->approved_by = auth()->user()->id;
            $boq->save();

            $this->sendNotification($data, $recerver, BOQApproved::class);
            return back()->with('success', 'The Item has been approved.');
        } else {
            $this->sendNotification($data, $recerver, BOQApproved::class);
            return back()->with('danger', 'The Item has been approved.');
        }
    }

    public function approve_all()
    {
        $cek_revision = $this->project->maxEditRevision();

        $this->validate([
            'check_approval' => ['required', 'array'],
        ], [], [
            'check_approval' => 'Approval',
        ]);

        foreach ($this->check_approval as $key => $value) {
            if ($value) {

                if ($cek_revision == null || $cek_revision == 0) {
                    $boq = BOQ::findOrfail($key);
                } else {
                    $boq = BOQEdit::findOrfail($key);
                }

                $currentUserId = auth()->user()->id;

                // Skip if the user has already approved
                if ($boq->approved_by == $currentUserId || $boq->approved_by_2 == $currentUserId) {
                    continue;
                }

                if ($boq->approved_by == null) {
                    $boq->approved_by = auth()->user()->id;
                    $boq->date_approved = date('Y-m-d H:i:s');
                } else if ($boq->approved_by_2 == null) {
                    $boq->approved_by_2 = auth()->user()->id;
                    $boq->date_approved_2 = date('Y-m-d H:i:s');
                }

                $boq->save();
            }
        }

        $recerver = User::withoutRole(Role::MANAGER);
        $data = [
            "project_name" => $this->project->name,
            "created_by" => auth()->user()->name,
            "location" => $this->project->id,
            "category" => $boq->category,
        ];

        $this->sendNotification($data, $recerver, BOQApproved::class);
        $this->updateBoqVerificationStatus();

        return redirect()->route('boq.index', ['project' => $this->project->id, 'wbs' => $this->task->id])->with('success', 'All Item has beed approved.');
    }

    public function reject_all()
    {
        $latest_data = BOQSpreadsheet::where('project_id', $this->project->id)->latest()->first();
        if ($latest_data) {
            $latest = $latest_data->toArray();
            $latest = json_decode($latest['data'], true);
        } else {
            $latest = [];
        }

        $cek_revision = $this->project->maxEditRevision();

        $this->validate([
            'check_approval' => ['required', 'array'],
        ], [], [
            'check_approval' => 'Approval',
        ]);

        foreach ($this->check_approval as $key => $value) {
            if ($value) {

                if ($cek_revision == null || $cek_revision == 0) {
                    $boq = BOQ::findOrfail($key);
                } else {
                    $boq = BOQEdit::findOrfail($key);
                }

                $item_qty = null;
                foreach ($latest as $item) {
                    if ($item[0] == $boq->item_id) {
                        $item_qty = $item[3];
                        break;
                    }
                }

                if ($boq->approved_by == null || $boq->approved_by_2 == null) {
                    $boq->qty = $item_qty != null ? $boq->qty - $item_qty : $boq->qty;
                    $boq->rejected_by = auth()->user()->id;
                    $boq->approved_by = null;
                    $boq->approved_by_2 = null;
                    $boq->save();
                }
            }
        }

        $recerver = User::withoutRole(Role::MANAGER);
        $data = [
            "project_name" => $this->project->name,
            "created_by" => auth()->user()->name,
            "location" => $this->project->id,
            "category" => 'Edit',
        ];

        $this->sendNotification($data, $recerver, BOQRejected::class);
        $this->updateBoqVerificationStatus();

        return redirect()->route('boq.index', ['project' => $this->project->id, 'wbs' => $this->wbs->id])->with('danger', 'All Item has beed rejected.');
    }

    public function create(Project $project)
    {
        $this->authorizeBOQ($project);

        return view('masterdata.boqs.create', compact(['project']));
    }

    public function store(Request $request, Project $project)
    {
        $request->validate([
            'item_id' => ['required', 'numeric', 'exists:items,id'],
            'unit_id' => ['required', 'numeric', 'exists:units,id'],
            'qty' => ['required', 'numeric'],
            'note' => ['nullable', 'string'],
            'price_estimation' => ['required', 'numeric', 'min:1'],
            'shipping_cost' => ['nullable', 'numeric', 'min:0'],
            'origin' => ['nullable', 'string'],
            'destination' => ['nullable', 'string']
        ], [], [
            'item_id' => 'Item',
            'unit_id' => 'Unit',
            'qty' => 'Quantity',
            'note' => 'Note',
            'price_estimation' => 'Price Estimation',
            'shipping_cost' => 'Shipping Cost',
            'origin' => 'Origin',
            'destination' => 'Destination'
        ]);

        $max_revision = (int) $project->boqs_edit_not_approved()->max('revision');

        // If manager or IT, then auto approve
        $approved_by = null;
        $date_approved = null;

        if (auth()->user()->hasTopLevelAccess()) {
            $approved_by = auth()->user()->id;
            $date_approved = date('Y-m-d H:i:s');
        }

        if ($max_revision == 0 || $max_revision == null) {
            $itemExist = $project->boqs_not_approved()->where('item_id', $request->item_id)->where('deleted_at', null)->first();

            if ($itemExist) {
                return redirect()->back()->with('danger', 'Item already exist.');
            }

            BOQ::create([
                'no_boq' => $project->id,
                'project_id' => $project->id,
                'item_id' => $request->item_id,
                'unit_id' => $request->unit_id,
                'qty' => $request->qty,
                'note' => $request->note,
                'price_estimation' => $request->price_estimation,
                'shipping_cost' => $request->shipping_cost,
                'origin' => $request->origin,
                'destination' => $request->destination,
                'revision' => 0,
                'created_by' => auth()->user()->id,
                'approved_by' => $approved_by,
                'date_approved' => $date_approved,
            ]);
        } else {
            $itemExist = $project->boqs_edit_not_approved()->where('item_id', $request->item_id)->where('deleted_at', null)->where('revision', $max_revision)->first();

            if ($itemExist) {
                return redirect()->back()->with('danger', 'Item already exist.');
            }

            BOQEdit::create([
                'no_boq' => $project->id,
                'project_id' => $project->id,
                'item_id' => $request->item_id,
                'unit_id' => $request->unit_id,
                'qty' => $request->qty,
                'note' => $request->note,
                'price_estimation' => $request->price_estimation,
                'shipping_cost' => $request->shipping_cost,
                'origin' => $request->origin,
                'destination' => $request->destination,
                'revision' => $max_revision,
                'created_by' => auth()->user()->id,
                'approved_by' => $approved_by,
                'date_approved' => $date_approved,
            ]);
        }

        return redirect()->back()->with('success', 'The Item has beed added to BOQ successfully.');
    }

    public function edit(Request $request, $item, $project_id)
    {
        $project = Project::findOrfail($project_id);
        $this->authorizeBOQ($project);

        $isDirectEdit = $request->get('direct-edit');
        if ($isDirectEdit && !auth()->user()->hasTopLevelAccess()) {
            abort(403);
        }

        if (BOQEdit::where('id', $item)->where('project_id', $project_id)->count() == 0) {
            $item = BOQ::where('id', $item)->first();
        } else {
            $item = BOQEdit::where('id', $item)->first();
        }

        $itemInPR = 0;
        $get_pr = PurchaseRequest::where('project_id', $item->project_id)
            ->where('status', '!=', 'Cancel')
            ->get();

        foreach ($get_pr as $pr) {
            $itemInPR += PurchaseRequestDetail::where('pr_id', $pr->id)
                ->where('item_id', $item->item_id)
                ->where('unit', $item->unit->name)
                ->pluck('qty')
                ->first();
        }

        $units = Unit::all();
        return view('masterdata.boqs.edit', compact('item', 'project', 'itemInPR', 'units'));
    }

    public function update(Request $request, $item, $project_id)
    {
        $project = Project::where('id', $project_id)->first();
        $isDirectEdit = $request->direct_edit;

        if ($isDirectEdit && !auth()->user()->hasTopLevelAccess()) {
            abort(403);
        }

        if (BOQEdit::where('id', $item)->where('project_id', $project_id)->count() == 0) {
            $item = BOQ::where('id', $item)->first();
        } else {
            $item = BOQEdit::where('id', $item)->first();
        }

        $request->validate([
            'note' => ['nullable', 'string'],
            'price_estimation' => ['required', 'numeric', 'min:1'],
            'shipping_cost' => ['nullable', 'numeric', 'min:0'],
            'origin' => ['nullable', 'string'],
            'destination' => ['nullable', 'string']
        ], [], [
            'qty' => 'Quantity',
            'note' => 'Note',
            'price_estimation' => 'Price Estimation',
            'shipping_cost' => 'Shipping Cost',
            'origin' => 'Origin',
            'destination' => 'Destination'
        ]);

        $jumlah_item = 0;
        $get_pr = PurchaseRequest::where('project_id', $item->project_id)->where('status', '!=', 'cancel')->get();

        foreach ($get_pr as $pr) {
            $jumlah_item += PurchaseRequestDetail::where('pr_id', $pr->id)->where('item_id', $item->item_id)->where('unit', $item->unit->name)->pluck('qty')->first();
        }


        $qty = $item->qty;

        if ($request->qty_plus) {
            $qty = $qty + (int) $request->qty_plus;
        }

        if ($request->qty_min) {
            $qty = $qty - (int) $request->qty_min;

            if ($qty < $jumlah_item) {
                return back()->withInput()->with("danger", "Quantity cannot be less than the quantity of the purchase request");
            }

            if ($qty <= 0) {
                return back()->withInput()->with("danger", "Quantity cannot be less than or equal to 0");
            }
        }

        if ($request->unit_id) {
            $item->update([
                'unit_id' => $request->unit_id,
            ]);
        }


        if ($isDirectEdit) {
            $item->update([
                'qty' => $qty,
                'note' => $request->note,
                'price_estimation' => $request->price_estimation,
                'shipping_cost' => $request->shipping_cost,
                'origin' => $request->origin,
                'destination' => $request->destination,
                'updated_by' => auth()->user()->id,
            ]);
        } else {
            $item->update([
                'qty' => $qty,
                'note' => $request->note,
                'price_estimation' => $request->price_estimation,
                'shipping_cost' => $request->shipping_cost,
                'origin' => $request->origin,
                'destination' => $request->destination,
                'approved_by' => Null,
                'approved_by_2' => Null,
                'updated_by' => auth()->user()->id,
                'date_approved_2' => Null,
                'rejected_by' => Null,
            ]);
        }

        return redirect()->route('boq.index', $item->project)->with('success', 'The Item has been updated successfully.');
    }

    public function destroy($item, $project_id)
    {
        $project = Project::where('id', $project_id)->first();

        if (BOQEdit::where('id', $item)->where('project_id', $project_id)->count() == 0) {
            $item = BOQ::where('id', $item)->first();
        } else {
            $item = BOQEdit::where('id', $item)->first();
        }

        $item->delete();

        return redirect()->route('boq.index', $item->project)->with('success', 'The Item has beed deleted successfully.');
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function requestAccessAdendum()
    {
        $datauser = User::role([Role::MANAGER, Role::IT])->get();
        $this->showModal = false;

        $access = BOQAccess::where("project_id", $this->project->id)->where("user_id", auth()->user()->id)->first();

        $data = [
            "project_name" => $this->project->name,
            "location" => $this->project->id,
            "editor" => auth()->user()->name,
            "category" => "",
            'action' => 'create an adendum',
        ];

        if ($access) {
            if ($access->status == 'rejected') {
                return redirect()->back()->with('danger', 'Access request has been rejected.');
            }

            $data['url'] = url('boq/' . $this->project->id . '/access' . '/' . $access->id);

            $this->sendNotification($data, $datauser, BOQAccessApproval::class);
            $this->sendNotification($data, auth()->user(), BOQAccessApproval::class);

            return redirect()->back()->with('danger', 'Access request already sent.');
        }

        $boqAccess = BOQAccess::create([
            "project_id" => $this->project->id,
            'user_id' => auth()->user()->id,
            'action' => 'create an adendum'
        ]);
        $data['url'] = url('boq/' . $this->project->id . '/access' . '/' . $boqAccess->id);

        $this->sendNotification($data, $datauser, BOQAccessApproval::class);
        $this->sendNotification($data, auth()->user(), BOQAccessApproval::class);

        return redirect()->back()->with('success', 'Access request has been sent.');
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
                    $query->orWhereNull('approved_by_2');
                }
            })
            ->count();

        if ($hasPendingApproval == 0) {
            $project->boq = 1;
            $project->boq_verification = 0;
            $project->save();
        }
    }
}
