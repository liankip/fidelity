<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryOut extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_detail_id',
        'project_id',
        'partof',
        'item_pic',
        'out',
        'is_partial',
        'user_id',
        'owner_id',
        'desc',
        'reserved',
        'date_out'
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function inventoryDetail()
    {
        return $this->belongsTo(InventoryDetail::class, 'inventory_detail_id', 'id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class,'user_id', 'id');
    }

    public function userOwner()
    {
        return $this->belongsTo(User::class,'owner_id', 'id');
    }

    public function editHistory()
    {
        return $this->hasMany(InventoryOutEditHistoryModel::class, 'inventory_out_id', 'id');
    }

    public function hasEditHistory()
    {
        return $this->editHistory->count() > 0;
    }
}
