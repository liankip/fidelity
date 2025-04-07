<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function histories()
    {
        return $this->hasMany(InventoryHistory::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function details()
    {
        return $this->hasMany(InventoryDetail::class);
    }

    public function newTask()
    {
        return $this->belongsTo(Task::class, 'new_task_id');
    }

    public function oldTask()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function poDetail()
    {
        return $this->belongsTo(PurchaseOrderDetail::class, 'podetail_id');
    }

    public function prDetail()
    {
        return $this->belongsTo(PurchaseRequestDetail::class, 'prdetail_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
