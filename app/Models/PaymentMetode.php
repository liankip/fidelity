<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMetode extends Model
{
    use HasFactory;

    protected $fillable = [
        'metode',
        'term_of_payment',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public const D7 = ["NET7", "Cicilan7", "7 hari"];
    public const D30 = ["NET30", "Cicilan30", "30 hari"];
    public const D60 = ["NET60", "Cicilan60", "60 hari"];


}
