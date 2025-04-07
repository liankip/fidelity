<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function purchaseorder()
    {
        return $this->belongsTo(PurchaseOrder::class,"po_id","id");
    }
}
