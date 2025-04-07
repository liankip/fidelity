<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sku extends Model
{
    use HasFactory;

    protected $table = 'sku';

    protected $fillable = ['name', 'boq', 'grosir_price', 'distributor_price', 'msrp_price', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'boq' => 'array',
    ];

    public function items()
    {
        $itemIds = array_column($this->boq ?? [], 0);

        return Item::whereIn('id', $itemIds);
    }
}
