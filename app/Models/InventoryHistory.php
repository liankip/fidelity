<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryHistory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function taskRel()
    {
        return $this->belongsTo(Task::class, 'task', 'task_number');
    }

    public function poDetailRel()
    {
        return $this->belongsTo(PurchaseOrderDetail::class, 'podetail_id', 'id');
    }

    public function prDetailRel()
    {
        return $this->belongsTo(PurchaseRequestDetail::class, 'prdetail_id', 'id');
    }

    public function workOrderRel()
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id', 'id');
    }

    public function salesRel()
    {
        return $this->belongsTo(Sales::class, 'sales_id', 'id');
    }
}
