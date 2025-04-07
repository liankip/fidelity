<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = ['po_id', 'foto_invoice', 'tax_invoice_photo', 'penerima', 'created_by', 'updated_by', 'deleted_by', 'date_received'];

    public function purchaseorder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
