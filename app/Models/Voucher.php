<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'voucher_no',
        'payment_submission_id',
        'created_at',
        'additional_informations',
    ];

    public function voucher_details()
    {
        return $this->hasMany(VoucherDetail::class, 'voucher_id');
    }

    public function approved()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function payment_submission()
    {
        return $this->belongsTo(PaymentSubmissionModel::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'voucher_id');
    }

    public function rejected()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function hasPayments()
    {
        return $this->payments->count() > 0;
    }

    public function hasDetails()
    {
        return $this->voucher_details->count() > 0;
    }
}
