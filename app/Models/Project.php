<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    private $maxEditRevision = null;

    public function boqs()
    {
        return $this->hasMany(BOQ::class)->approved();
    }

    public function allBOQ()
    {
        return $this->hasMany(BOQ::class);
    }

    public function b_o_q_s()
    {
        $maxRevision = $this->maxEditRevision();

        if ($maxRevision) {
            $boqs = $this->boqs_edit_not_approved()->where('revision', $maxRevision)->whereNull('deleted_at')->get();
        } else {
            $boqs = $this->boqs_not_approved()->whereNull('deleted_at')->get();
        }

        return $boqs;
    }

    public function purchase_orders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function purchase_order_approver()
    {
        return $this->belongsToMany(User::class, 'purchase_order_approver', 'project_id', 'user_id');
    }

    public function canApprovePO()
    {
        $access = $this->purchase_order_approver->where('id', auth()->user()->id)->first();
        $hasAccess = !is_null($access);

        if ($this->purchase_order_approver()->count() == 0 || auth()->user()->hasTopManagerAccess()) {
            return true;
        }

        return $hasAccess;
    }

    public function boqs_not_approved()
    {
        return $this->hasMany(BOQ::class);
    }

    public function createdby()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function boqs_edit()
    {
        return $this->hasMany(BOQEdit::class)->approved();
    }

    public function boqs_edit_not_approved()
    {
        return $this->hasMany(BOQEdit::class);
    }

    public function boq_access()
    {
        return $this->hasMany(BOQAccess::class);
    }

    public function boq_requests()
    {
        return $this->hasMany(BOQSpreadsheet::class);
    }

    public function hasBoqAccess()
    {
        if (auth()->user()->hasTopLevelAccess()) {
            return true;
        }

        $access = $this->boq_access
            ->where('user_id', auth()->user()->id)
            ->where('status', 'approved')
            ->first();

        return !is_null($access);
    }

    public function removeBoqAccess()
    {
        $access = $this->boq_access()
            ->where('user_id', auth()->user()->id)
            ->where('status', 'approved')
            ->first();

        if ($access) {
            $access->delete();
        }
    }

    public function boqCountWaitingApproval($isMultipleApproval)
    {
        $maxRevision = $this->maxEditRevision();

        if ($maxRevision) {
            $boqsCount = $this->boqs_edit_not_approved()
                ->where('revision', $maxRevision)
                ->where(function ($query) use ($isMultipleApproval) {
                    $query->whereNull('approved_by');
                    if ($isMultipleApproval) {
                        $query->orWhereNull('approved_by_2');
                    }
                })
                ->whereNull('deleted_at')
                ->count();
        } else {
            $boqsCount = $this->boqs_not_approved()
                ->where(function ($query) use ($isMultipleApproval) {
                    $query->whereNull('approved_by');
                    if ($isMultipleApproval) {
                        $query->orWhereNull('approved_by_2');
                    }
                })
                ->whereNull('deleted_at')
                ->count();
        }

        return $boqsCount;
    }

    public function maxEditRevision()
    {
        if (is_null($this->maxEditRevision)) {
            $this->maxEditRevision = $this->boqs_edit_not_approved()->max('revision');
        }
        return $this->maxEditRevision;
    }

    public function purchase_requests()
    {
        return $this->hasMany(PurchaseRequest::class);
    }

    public function boqGrandTotal()
    {
        $maxRevision = $this->maxEditRevision();
        $total = 0;
        if ($maxRevision) {
            $total = $this->boqs_edit_not_approved()->where('revision', $maxRevision)->whereNull('deleted_at')->sum(DB::raw('price_estimation * qty'));
        } else {
            $total = $this->boqs_not_approved()->whereNull('deleted_at')->sum(DB::raw('price_estimation * qty'));
        }

        return $total;
    }

    public function boqs_list()
    {
        $maxRevision = $this->maxEditRevision();
        $lastBoqEdits = collect();

        // Use Lazy Collection for large datasets
        $boqs = collect();

        if (!is_null($maxRevision)) {
            $boqs = $this->boqs_edit_not_approved()
                ->with(['unit:id,name', 'approved', 'rejected', 'item:id,name', 'approved2'])
                ->where('revision', $maxRevision)
                ->whereNull('deleted_at')
                ->get();

            $lastBoqEdits = $maxRevision > 1
                ? $this->boqs_edit_not_approved()
                    ->where('revision', $maxRevision - 1)
                    ->pluck('qty', 'item_id')
                : $this->boqs_not_approved()
                    ->pluck('qty', 'item_id');
        } else {
            $boqs = $this->boqs_not_approved()
                ->with(['unit:id,name', 'approved', 'rejected', 'item:id,name', 'approved2'])
                ->whereNull('deleted_at')
                ->get();
                // dd(vars: $boqs[0]);
        }
        // dd($boqs[0]);

        // Use caching to optimize repeated queries
        $purchaseOrders = cache()->remember('purchase_orders_'.$this->id, now()->addMinutes(10), function() {
            return $this->purchase_orders()
                ->whereIn('status', PurchaseOrder::processedStatus())
                ->with('podetail:item_id,purchase_order_id,qty')
                ->select('id', 'po_no', 'status')
                ->get();
        });

        $purchaseRequests = cache()->remember('purchase_requests_'.$this->id, now()->addMinutes(10), function() {
            return PurchaseRequest::where('project_id', $this->id)
                ->with('purchaseRequestDetails:item_id,unit')
                ->get();
        });

        // Optimize historyPrices query
        $historyPrices = DB::table('purchase_order_details')
            ->join('purchase_orders', 'purchase_orders.id', '=', 'purchase_order_details.purchase_order_id')
            ->whereIn('purchase_orders.status', PurchaseOrder::processedStatus())
            ->whereIn('purchase_order_details.item_id', $boqs->pluck('item_id')->toArray())
            ->orderBy('purchase_order_details.created_at', 'desc')
            ->select('purchase_order_details.price', 'purchase_order_details.item_id', 'purchase_order_details.created_at')
            ->get()
            ->groupBy('item_id');

        // Optimize loop using Lazy Collection
        $boqs->each(function ($boq) use ($lastBoqEdits, $historyPrices, $purchaseOrders, $purchaseRequests) {
            $boq['po_status'] = null;

            // Get history price
            $price = $historyPrices->get($boq->item_id, collect())->first();
            $boq['history_price'] = $price ? collect($price) : null;

            // Get PO Status using Lazy Collection
            $po_list = [
                'list' => [],
                'qty_total' => 0,
            ];

            collect($purchaseOrders)->each(function ($po) use (&$po_list, $boq) {
                $po_details = collect($po->podetail)->where('item_id', $boq->item_id);
                if ($po_details->isNotEmpty()) {
                    $po_details->each(function ($p) use (&$po_list, $po) {
                        $po_list['list'][] = [
                            'po_id' => $p->purchase_order_id,
                            'po_no' => $po->po_no,
                        ];
                        $po_list['qty_total'] += $p->qty;
                    });
                }
            });

            $boq['po_status'] = !empty($po_list['list']) ? $po_list : null;

            // Get last BOQ qty item
            $boq['qty_before'] = $lastBoqEdits->get($boq->item_id);

            // Check if item is in purchase order
            $unitName = $boq->unit->name;
            $boq['canDelete'] = !collect($purchaseRequests)
                ->contains(function ($pr) use ($boq, $unitName) {
                    return collect($pr->purchaseRequestDetails)
                        ->contains(function ($prd) use ($boq, $unitName) {
                            return $prd->item_id == $boq->item_id && $prd->unit == $unitName;
                        });
                });
        });

        return $boqs;
    }


    public function isBoqApproved($boqTable, $maxVersion): bool
    {
        $userID = auth()->user()->id;
        $approvalCount = DB::table($boqTable)
            ->where('project_id', $this->id)
            ->where(function ($query) use ($userID) {
                $query->where('approved_by', '<>', $userID)->orWhereNull('approved_by');
            })
            ->where(function ($query) use ($userID) {
                $query->where('approved_by_2', '<>', $userID)->orWhereNull('approved_by_2');
            })
            ->where('deleted_at', null)
            ->where('revision', $maxVersion)
            ->count();

        return $approvalCount > 0;
    }

    public function isBoqApprovedTaskNumber($boqTable, $maxVersion, $taskNumber): bool
    {
        $userID = auth()->user()->id;
        $approvalCount = DB::table($boqTable)
            ->where('project_id', $this->id)
            ->where(function ($query) use ($userID) {
                $query->where('approved_by', '<>', $userID)->orWhereNull('approved_by');
            })
            ->where(function ($query) use ($userID) {
                $query->where('approved_by_2', '<>', $userID)->orWhereNull('approved_by_2');
            })
            ->where('deleted_at', null)
            ->where('rejected_by', null)
            ->where('task_number', '=', $taskNumber)
            ->where('revision', $maxVersion)
            ->count();

        return $approvalCount > 0;
    }

    public function isBoqApprovedWithoutTaskNumber($boqTable, $maxVersion): bool
    {
        $userID = auth()->user()->id;
        $approvalCount = DB::table($boqTable)
            ->where('project_id', $this->id)
            ->where(function ($query) use ($userID) {
                $query->where('approved_by', '<>', $userID)->orWhereNull('approved_by');
            })
            ->where(function ($query) use ($userID) {
                $query->where('approved_by_2', '<>', $userID)->orWhereNull('approved_by_2');
            })
            ->where('deleted_at', null)
            ->where('rejected_by', null)
            ->where('revision', $maxVersion)
            ->count();

        return $approvalCount > 0;
    }

    public function purchaseRequestItems(int $currentPR, $search = null)
    {
        $maxRevision = $this->maxEditRevision();
        $multipleApproval = Setting::first()->multiple_approval;
        $featureThirdApprovalRelease = Carbon::parse('2025-01-20');

        if ($maxRevision) {
            $boq = $this->boqs_edit_not_approved()
                ->with(['item', 'unit:id,name'])
                ->where('revision', $maxRevision);
            $items = $boq
                ->whereNull('deleted_at')
                ->where(function ($query) use ($multipleApproval, $search) {
                    $query->where('approved_by', '!=', null);
                    if ($multipleApproval) {
                        $query->where('approved_by_2', '!=', null);
                    }

                    if ($search) {
                        $query->whereHas('item', function ($itemQuery) use ($search) {
                            $itemQuery->where('name', 'like', '%' . $search . '%');
                        });
                    }
                })
                ->select('item_id', 'unit_id', 'qty')
                ->get();
        } else {
            $boq = $this->boqs_not_approved()->with(['item', 'unit:id,name']);
            $items = $boq
                ->whereNull('deleted_at')
                ->where(function ($query) use ($multipleApproval, $search) {
                    $query->where('approved_by', '!=', null);
                    if ($multipleApproval) {
                        $query->where('approved_by_2', '!=', null);
                    }

                    if ($search) {
                        $query->whereHas('item', function ($itemQuery) use ($search) {
                            $itemQuery->where('name', 'like', '%' . $search . '%');
                        });
                    }
                })
                ->when(
                    $featureThirdApprovalRelease,
                    function ($query) use ($featureThirdApprovalRelease) {
                        $query->whereDate('updated_at', '>=', $featureThirdApprovalRelease)
                        ->whereNotNull('approved_by_3');
                    }
                )
                ->select('id', 'item_id', 'unit_id', 'qty', 'task_number', 'section')->get();
        }

        return $items;
    }

    public function itemQuantity($itemId)
    {
        $isArray = is_array($itemId);
        if (!$isArray) {
            $itemId = [$itemId];
        }

        $maxRevision = $this->maxEditRevision();
        $multipleApproval = Setting::first()->multiple_approval;

        if ($maxRevision >= 1) {
            $item = $this->boqs_edit_not_approved()
                ->where('revision', $maxRevision)
                ->whereNull('deleted_at')
                ->where(function ($query) use ($multipleApproval) {
                    $query->where('approved_by', '!=', null);
                    if ($multipleApproval) {
                        $query->where('approved_by_2', '!=', null);
                    }
                })
                ->whereIn('item_id', $itemId)
                ->select('qty', 'item_id');
        } else {
            $item = $this->boqs_not_approved()
                ->whereNull('deleted_at')
                ->where(function ($query) use ($multipleApproval) {
                    $query->where('approved_by', '!=', null);
                    if ($multipleApproval) {
                        $query->where('approved_by_2', '!=', null);
                    }
                })
                ->whereIn('item_id', $itemId)
                ->select('qty', 'item_id');
        }

        if ($isArray) {
            return $item->get()->keyBy('item_id');
        }

        return $item->first();
    }

    public function purchaseRequests()
    {
        return $this->hasMany(PurchaseRequest::class);
    }

    public function scopePastOneMonth($query)
    {
        return $query
            ->whereHas('boqs', function ($q) {
                $q->where('updated_at', '<', Carbon::now()->subMonth());
            })
            ->whereDoesntHave('purchaseRequests');
    }

    public function project_documents()
    {
        return $this->hasMany(ProjectDocument::class);
    }

    public function task_file_path(): HasOne
    {
        return $this->hasOne(TaskFilePath::class);
    }
}
