<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'project_id',
        'stock',
        'warehouse_type'
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function inventory_outs()
    {
        return $this->hasMany(InventoryOut::class,'inventory_detail_id','id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function inventoryOut()
    {
        return $this->hasMany(InventoryOut::class);
    }

    public function purchaseRequest()
    {
        return $this->hasMany(PurchaseRequest::class, 'project_id','project_id');
    }

    public function detailHistory()
    {
        return $this->hasMany(InventoryHistory::class, 'inventory_detail_id', 'id');
    }
}
