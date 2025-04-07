<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BOQ extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['no_boq', 'project_id', 'capex_expense_id', 'item_id', 'unit_id', 'qty', 'price_estimation', 'shipping_cost', 'origin', 'destination', 'note', 'revision', 'approved_by', 'date_approved', 'approved_by_2', 'date_approved_2', 'approved_by_3', 'date_approved_3', 'rejected_by', 'created_by', 'updated_by', 'deleted_by', 'task_number', 'comment', 'number_engineering', 'pr_id', 'order_id', 'section'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($item) {
            if (auth()->check()) {
                $item->deleted_by = auth()->user()->id;
                $item->save();
            }
        });
    }

    public function scopeApproved($query)
    {
        return $query->whereNotNull('approved_by');
    }

    public function approved()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function approved2()
    {
        return $this->belongsTo(User::class, 'approved_by_2');
    }

    public function approved3(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_3');
    }

    public function rejected()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function delete()
    {
        $this->deleted_by = auth()->user()->id;
        parent::delete();
    }

    public function purchase_order()
    {
        return $this->hasMany(PurchaseOrder::class, 'project_id', 'project_id');
    }

    public function purchaseRequest()
    {
        return $this->hasMany(PurchaseRequest::class, 'partof', 'task_number');
    }
}
