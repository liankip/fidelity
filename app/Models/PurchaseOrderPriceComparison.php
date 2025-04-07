<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderPriceComparison extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_request_id',
        'supplier_item_price_id',
        'item_id',
    ];

    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class, 'purchase_request_id');
    }

    public function supplierItemPrice()
    {
        return $this->belongsTo(SupplierItemPrice::class, 'supplier_item_price_id');
    }
}
