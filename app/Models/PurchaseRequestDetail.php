<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class PurchaseRequestDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'purchase_request_details';

    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'pr_id' => 'integer',
        'item_id' => 'integer'
    ];

    public function purchaseRequest(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequest::class, "pr_id", "id");
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function podetail(): Builder|HasMany
    {
        return $this->hasMany(PurchaseOrderDetail::class, "purchase_request_detail_id", "id")->whereHas("po", function ($query) {
            $query->where("status", "!=", "Cancel")->where("status", "!=", "Rejected");
        });
    }

    public function podetailall()
    {
        return $this->hasMany(PurchaseOrderDetail::class, "purchase_request_detail_id", "id");
    }

    public static function getItemQuantity($itemIds, $projectId)
    {
        if (!is_array($itemIds)) {
            $itemIds = [$itemIds];
        }
        return self::whereIn('item_id', $itemIds)
            ->whereHas('purchaseRequest', function ($query) use ($projectId) {
                $query->whereNotIn('status', ['Cancel', 'Duplicated'])
                    ->where('project_id', $projectId);
            })
            ->groupBy('item_id')
            ->select('item_id', DB::raw('SUM(qty) as total_qty'))
            ->get()
            ->keyBy('item_id');
    }

    public static function getItemQuantityWithPrFilter($prId, $itemIds, $projectId)
    {
        if (!is_array($itemIds)) {
            $itemIds = [$itemIds];
        }
        return self::whereIn('item_id', $itemIds)
            ->whereHas('purchaseRequest', function ($query) use ($prId, $projectId) {
                $query->whereNotIn('status', ['Cancel', 'Duplicated'])
                    ->where('project_id', $projectId)
                    ->where('id', '!=', $prId);
            })
            ->groupBy('item_id')
            ->select('item_id', DB::raw('SUM(qty) as total_qty'))
            ->get()
            ->keyBy('item_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function pivotBulkPR()
    {
        return $this->belongsToMany(PurchaseOrderDetail::class, 'bulk_po_pivot', 'pr_detail_id', 'po_detail_id');
    }
}
