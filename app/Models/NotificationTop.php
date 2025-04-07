<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationTop extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function purchaseorder()
    {
        return $this->belongsTo(PurchaseOrder::class, "purchase_order_id", "id");
    }
}
