<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestForQuotationDetail extends Model
{
    protected $fillable = [
        'request_for_quotation_id',
        'item_id',
        'price',
        'notes',
        'unit',
        'qty'
    ];

    public function requestForQuotation()
    {
        return $this->belongsTo(RequestForQuotation::class, 'request_for_quotation_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
