<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeExpenseItem extends Model
{
    use HasFactory;

    protected $fillable = ['office_expense_purchase_id', 'purchase_date', 'total_expense', 'receiver_name', 'vendor', 'account_number', 'notes', 'status', 'approved_by', 'approved_date', 'rejected_by', 'rejected_date', 'is_purchase'];

    public function officeExpensePurchase()
    {
        return $this->belongsTo(OfficeExpensePurchase::class, 'office_expense_purchase_id');
    }
}
