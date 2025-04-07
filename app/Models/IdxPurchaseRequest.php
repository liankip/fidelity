<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IdxPurchaseRequest extends Model
{
    // use HasFactory;
    use SoftDeletes;
    protected $table = 'idx_purchase_requests';
    protected $fillable = [
        'idx',
    ];
}
