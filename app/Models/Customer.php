<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customer';

    protected $fillable = ['name', 'npwp', 'shipping_address', 'ktp', 'pic_name', 'pic_phone', 'pic_email', 'recipient_name', 'recipient_phone', 'billing_address', 'billing_phone', 'billing_email'];
}
