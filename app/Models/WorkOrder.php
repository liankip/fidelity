<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;

    protected $table = 'work_order';

    protected $fillable = [
        'number',
        'deadline_date',
        'product',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }
}
