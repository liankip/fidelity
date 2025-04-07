<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkPOPivot extends Model
{
    use HasFactory;

    protected $table = 'bulk_po_pivot';

    protected $fillable = ['pr_detail_id', 'po_detail_id'];

    public function po()
    {
        return $this->belongsTo(PurchaseOrderDetail::class, 'po_detail_id', 'id');
    }
}
