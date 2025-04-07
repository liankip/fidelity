<?php

namespace App\Models;

use App\Constants\PurchaseOrderStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function voucherDetail()
    {
        return $this->hasMany(VoucherDetail::class, 'purchase_order_id', 'id');
    }

    public function createdby()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function pr()
    {
        return $this->belongsTo(PurchaseRequest::class, 'pr_no', 'pr_no');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function submition()
    {
        return $this->hasMany(SubmitionHistory::class, 'po_id', 'id');
    }

    public function podetail(): HasMany
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'purchase_order_id', 'id');
    }

    public function price()
    {
        return $this->belongsTo(Price::class, 'supplier_id', 'id');
    }

    public function ds()
    {
        return $this->belongsTo(DeliveryService::class, 'ds_id', 'id');
    }

    public function approvedby()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    public function approvedby2()
    {
        return $this->belongsTo(User::class, 'approved_by_2', 'id');
    }

    public function do()
    {
        return $this->hasMany(DeliveryOrder::class, 'referensi', 'po_no');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'po_id', 'id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'id', 'po_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'po_id', 'id');
    }

    public function completeDocument()
    {
        return $this->hasOne(PurchaseOrderCompleteDocument::class, 'po_id', 'id');
    }

    public function isProcessed()
    {
        return in_array($this->status, self::processedStatus());
    }

    public static function getProcessed()
    {
        return self::whereIn('status', self::processedStatus());
    }

    public static function processedStatus(): array
    {
        return [PurchaseOrderStatus::APPROVED, PurchaseOrderStatus::PARTIALLY_PAID, PurchaseOrderStatus::PAID, PurchaseOrderStatus::NEED_TO_PAY];
    }

    public static function unprocessedStatus(): array
    {
        return [PurchaseOrderStatus::DRAFT, PurchaseOrderStatus::DRAFT_WITH_DS, PurchaseOrderStatus::REJECTED, PurchaseOrderStatus::CANCEL];
    }

    public function isApproved()
    {
        return $this->status == PurchaseOrderStatus::APPROVED;
    }

    public function isArrived()
    {
        return $this->status_barang == PurchaseOrderStatus::ARRIVED;
    }

    public function isDraft()
    {
        return $this->status == PurchaseOrderStatus::DRAFT;
    }

    public function isDraftWithDS()
    {
        return $this->status == PurchaseOrderStatus::DRAFT_WITH_DS;
    }

    public function isRejected()
    {
        return $this->status == PurchaseOrderStatus::REJECTED;
    }

    public function isReverted()
    {
        return $this->status == PurchaseOrderStatus::REVERTED;
    }

    public function isCancel()
    {
        return $this->status == PurchaseOrderStatus::CANCEL;
    }

    public function isNeedToPay()
    {
        return $this->status == PurchaseOrderStatus::NEED_TO_PAY;
    }

    public function isPartiallyPaid()
    {
        return $this->status == PurchaseOrderStatus::PARTIALLY_PAID;
    }

    public function isPaid()
    {
        return $this->status == PurchaseOrderStatus::PAID;
    }

    public function isWaitApproval()
    {
        return $this->status == PurchaseOrderStatus::WAIT_FOR_APPROVAL;
    }

    public function isNewWithDS()
    {
        return $this->status == PurchaseOrderStatus::NEW_WITH_DS;
    }

    public function isNew()
    {
        return $this->status == PurchaseOrderStatus::NEW;
    }

    public function isReview()
    {
        return $this->status == PurchaseOrderStatus::REVIEW;
    }

    public static function filter($keyword, $status)
    {
        $query = self::query();
        $query->with('supplier', 'warehouse', 'project', 'podetail');

        if ($keyword) {
            $query
                ->where('po_no', 'like', '%' . $keyword . '%')
                ->orWhere('pr_no', 'like', '%' . $keyword . '%')
                ->orWhere('status', 'like', '%' . $keyword . '%')
                ->orWhere('status_barang', 'like', '%' . $keyword . '%')
                ->orWhere('term_of_payment', 'like', '%' . $keyword . '%')
                ->orWhereHas('project', function ($query) use ($keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                })
                ->orWhereHas('warehouse', function ($query) use ($keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                })
                ->orWhereHas('supplier', function ($query) use ($keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                });
        }

        if ($status && $status !== 'All') {
            if ($status === PurchaseOrderStatus::ARRIVED) {
                $query->where('status_barang', $status);
            } else {
                $query->where('status', $status);
            }
        }

        return $query->orderBy('id', 'desc')->paginate(15);
    }

    public function canPrintReceipt()
    {
        $hasInvoice = $this->invoices->count() > 0;

        return $hasInvoice && $this->isArrived();
    }

    public function hasInvoice()
    {
        return $this->invoices->count() > 0;
    }

    public function hasVoucherDetails()
    {
        return $this->voucherDetail()->count() > 0;
    }

    public function totalInvoice()
    {
        return $this->invoices->count();
    }

    public function hasSubmition()
    {
        return $this->submition()->count() > 0;
    }

    public function totalSubmition()
    {
        return $this->submition()->count();
    }

    public function totalDo()
    {
        return $this->do()->count();
    }

    public function pivotPR()
    {
        return $this->belongsToMany(PurchaseRequest::class, 'po_pr_pivot', 'po_id', 'pr_id');
    }

    public static function purchaseOrderComplete($startDate, $endDate)
    {
        $purchaseOrders = self::whereIn('status', [PurchaseOrderStatus::APPROVED, PurchaseOrderStatus::NEED_TO_PAY, PurchaseOrderStatus::PAID])
            ->whereBetween('created_at', [Carbon::parse($startDate)->startOfDay(), Carbon::parse($endDate)->endOfDay()])
            ->with([
                'supplier',
                'podetail',
                'submition',
                'submition.item',
            ])
            ->get();

        return $purchaseOrders->groupBy('po_no')->map(function ($groupedOrders) {
            $order = $groupedOrders->first();
            return [
                'po_no' => $order->po_no,
                'supplier' => $order->supplier,
                'warehouse' => $order->warehouse,
                'project' => $order->project,
                'podetail' => $groupedOrders->pluck('podetail')->flatten(1),
                'submition' => $groupedOrders->pluck('submition')->flatten(1),
            ];
        });
    }
}
