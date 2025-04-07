<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorRegistrantDocument extends Model
{
    protected $fillable = [
        'path',
        'vendor_registrant_id',
        'file_type',
        'file_name',
    ];
}
