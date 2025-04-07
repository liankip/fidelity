<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class RequestForQuotation extends Model
{
    use HasUuids;
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'period',
        'expired_at',
        'supplier_id',
        'purchase_request_id',
        'is_submitted'
    ];

    protected $casts = [
        'period' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class);
    }

    public function itemDetail()
    {
        return $this->hasMany(RequestForQuotationDetail::class, 'request_for_quotation_id', 'id');
    }
}
