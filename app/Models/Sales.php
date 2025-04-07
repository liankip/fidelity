<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $table = 'sales';

    protected $fillable = [
        'id_customer',
        'address',
        'product',
        'payment_method',
        'notes',
        'finish_date',
        'sales_person'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }
}
