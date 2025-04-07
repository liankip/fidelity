<?php

namespace App\Models;

use App\Constants\PurchaseOrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PurchaseOrderDetail extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, "item_id", "id");
    }

    public function po(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class,"purchase_order_id","id");
    }

    public function prdetail(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequestDetail::class, "purchase_request_detail_id", "id");
    }

    public static function getItemQuantity($itemIds): Collection
    {
        if (!is_array($itemIds)) {
            $itemIds = [$itemIds];
        }

        return self::whereIn('item_id', $itemIds)
            ->whereIn('purchase_order_id', function ($query) {
                $query->from('purchase_orders')
                    ->whereIn('status', [PurchaseOrderStatus::CANCEL, PurchaseOrderStatus::REJECTED])
                    ->select('id');
            })
            ->groupBy('item_id')
            ->select('item_id', DB::raw('SUM(qty) as total_qty'))
            ->get()
            ->keyBy('item_id');
    }

    public function actualField(): HasOne
    {
        return $this->hasOne(ActualFieldModel::class, "po_detail_id", "id");
    }

    public function pivotBulkPO(): BelongsToMany
    {
        return $this->belongsToMany(PurchaseRequestDetail::class, 'bulk_po_pivot', 'po_detail_id', 'pr_detail_id');
    }
}
