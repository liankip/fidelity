<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmitionHistory extends Model
{
    use HasFactory;

    public function purchaseorder()
    {
        return $this->belongsTo(PurchaseOrder::class,"po_id","id");
    }
    public function item()
    {
        return $this->belongsTo(Item::class,"item_id","id");
    }
    public function do()
    {
        return $this->belongsTo(DeliveryOrder::class,"do_id","id");
    }
}
