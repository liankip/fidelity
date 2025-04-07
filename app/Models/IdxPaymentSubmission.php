<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdxPaymentSubmission extends Model
{
    use HasFactory;

    protected  $table = 'idx_payment_submission';
    protected $fillable = [
        'idx'
    ];
}
