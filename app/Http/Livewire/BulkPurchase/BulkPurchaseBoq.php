<?php

namespace App\Http\Livewire\BulkPurchase;

use App\Exports\BOQExport;
use App\Models\BOQSpreadsheet;
use App\Models\Project;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseRequestDetail;
use App\Models\Setting;
use App\Models\SupplierItemPrice;
use Livewire\Component;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class BulkPurchaseBoq extends Component
{
    use WithPagination;

    public $project,
        $boqs,
        $version = [],
        $show_version,
        $max_version,
        $check_approval = [];
    public $adendum;

    public $showModal = false;

    public $boqTable;
    public $boqFilteredList;
    public Setting $setting;
    public $select_all;
    public $sortBy, $filter;
    public $needToApprove = false;
    public $boqsArray = [];
    public $search;
    public $selectedItems = [];
    protected $paginationTheme = 'bootstrap';

    protected $queryString = ['search'];

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public function mount(Project $project)
    {
        $max_revision = $project->maxEditRevision();
        $this->setting = Setting::first();

        $this->project = $project;
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

            $this->boqs = $project->boqs_list();
            $ItemInPoDetail = PurchaseOrderDetail::whereHas('po', function ($query) use ($project) { $query->where('project_id', $project->id)->where('status', '!=', 'Cancel'); })->pluck('item_id')->unique();
            $ItemInPrDetail = PurchaseRequestDetail::whereHas('purchaseRequest', function($query) use($project) { $query->where('project_id', $project->id); })->pluck('item_id')->unique();

            $mergeItems = $ItemInPoDetail->merge($ItemInPrDetail)->unique();

            $this->boqs = $this->boqs->whereNotIn('item_id', $mergeItems);

            $this->boqsArray = collect($this->boqs->toArray());
            $this->needToApprove = $project->isBoqApproved($this->boqTable, $this->show_version);
        }

        $dataSession = Session::get('selectedItems');
        if (!empty($dataSession)) {
            $projectId = $dataSession->first()->project_id;

            if ($projectId == $this->project->id) {
                return redirect()->route('bulk-purchase-order.create', $this->project->id);
            }
        }
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
        } elseif ($this->sortBy == 'created_at') {
            $boqList = $this->boqs->sortBy('created_at');
        } else {
            $boqList = $this->boqs->sortBy(function ($boq) {
                return $boq->item ? $boq->item->name : $boq->id;
            });
        }

        if ($this->search) {
            $search = strtolower($this->search);
            $boqList = $boqList->filter(function ($boq) use ($search) {
                return stripos(strtolower($boq->item->name), $search) !== false || stripos(strtolower($boq->wbs), $search) !== false;
            });
        }

        $filteredBoq = $boqList->whereNull('rejected_by')->whereNotNull('approved_by');


        $sortBoqlist = $filteredBoq->sortByDesc(function ($boq) {
            $waitingForApproval = $boq->approved_by === null && $boq->approved_by_2 === null && $boq->rejected_by === null;
            $secondApproval = $boq->approved_by_2 === null && $boq->rejected_by === null;
            return [$waitingForApproval, $secondApproval];
        });


        $boqList = $sortBoqlist->groupBy('item_id')->map(function ($group) {
            $latestItem = $group->first();

            $latestItem->qty = $group->sum(function ($item) {
                return (float) $item->qty;
            });

            return $latestItem;
        });

        $this->boqFilteredList = $boqList;

        $paginatedBoqList = $this->paginateCollection($boqList);

        $this->boqFilteredList = $boqList;

        return view('livewire.bulk-purchase.bulk-purchase-boq', [
            'boqList' => $paginatedBoqList,
        ]);
    }

    public function paginateCollection(\Illuminate\Support\Collection $items, int $perPage = 10, int $page = null): LengthAwarePaginator
    {
        $page = $page ?: $this->page;
        $items = $items instanceof Collection ? $items : Collection::make($items);
        $total = $items->count();

        return new LengthAwarePaginator($items->forPage($page, $perPage), $total, $perPage, $page, ['path' => Paginator::resolveCurrentPath()]);
    }

    public function export_boq()
    {
        $invalidCharacters = ['/', '\\', ':', '*', '?', '"', '\'', '|', '<', '>'];
        $projectName = str_replace($invalidCharacters, '-', $this->project->name);
        $fileName = 'BOQ - ' . $projectName . '.xlsx';

        return Excel::download(new BOQExport($this->boqFilteredList), $fileName);
    }

    public function checkItems($id, $name, $qty)
    {
        if (isset($this->selectedItems[$id])) {
            unset($this->selectedItems[$id]);
        } else {
            $this->selectedItems[$id] = ['id' => $id, 'name' => $name, 'qty' => $qty];
        }
    }

    public function createBulk()
    {
        try {
            $sessionData = Session::get('selectedItems');

            if (empty($sessionData)) {
                $selectedItems = collect($this->selectedItems);
                $boqData = $this->boqs->whereIn('id', $selectedItems->pluck('id'));

                $boqData = $boqData->map(function ($boq) use ($selectedItems) {
                    $selectedItem = $selectedItems->firstWhere('id', $boq->id); 
                    if ($selectedItem) {
                        $boq->qty = $selectedItem['qty']; 
                        $boq->is_bulk = 1;
                    }
                    return $boq; 
                });

                Session::put('selectedItems', $boqData);
                return redirect()->route('bulk-purchase-order.create', $this->project->id);
            }

            return redirect()->route('bulk-purchase-order.create', $this->project->id);
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
