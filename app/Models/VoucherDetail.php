<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherDetail extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'voucher_id',
        'project_id',
        'supplier_id',
        'total',
        'amount_to_pay',
        'faktur_pajak'
    ];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'purchase_order_id', 'po_id');
    }

    public function purchase_order()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function voucherPayment()
    {
        return $this->belongsTo(Payment::class, 'voucher_id', 'voucher_id');
    }

    public function hasPaymentRelation()
    {
        return $this->belongsTo(Payment::class, 'voucher_id', 'id');
    }
}
