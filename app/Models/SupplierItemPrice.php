<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierItemPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'item_id',
        'unit_id',
        'price',
        'tax',
        'tax_status',
        'depend_user',
        'old_idr_by_usd',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
