<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierAdditionalFile extends Model
{
    protected $fillable = [
        'path',
        'supplier_id',
    ];
}
