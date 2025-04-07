<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSend extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function po()
    {
        return $this->belongsTo(PurchaseOrder::class, "po_id", "id");
    }
    public function createdby()
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, "invoice_id", "id");
    }
    public function sh()
    {
        return $this->belongsTo(SubmitionHistory::class, "sh_id", "id");
    }
    public function do()
    {
        return $this->belongsTo(DeliveryOrder::class, "do_id", "id");
    }
    public function payment()
    {
        return $this->belongsTo(Payment::class, "payment_id","id");
    }
}
