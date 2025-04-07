<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'order';

    protected $fillable = ['number_order', 'project_id', 'product_id', 'quantity', 'created_at', 'updated_at'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
