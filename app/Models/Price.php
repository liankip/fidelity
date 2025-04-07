<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Price extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'prices';
    protected $fillable = [
        'supplier_id',
        'item_id',
        'price',
        'tax',
        'created_by',
        'tax_status'
    ];
    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id', 'id');
    }
    public function item()
    {
        return $this->belongsTo(Item::class,"item_id", "id")->withTrashed();
    }
}
