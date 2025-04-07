<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSubmissionModel extends Model
{
    use HasFactory;

    protected $table = 'payment_submission';
    protected $fillable = [
        'no_payment_submission',
        'type',
        'status',
        'approved_by',
        'date_approved',
    ];

    public function vouchers()
    {
        return $this->hasMany(Voucher::class, 'payment_submission_id', 'id');
    }
}
