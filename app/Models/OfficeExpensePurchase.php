<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeExpensePurchase extends Model
{
    use HasFactory;

    protected $fillable = ['office_expense_id', 'purchase_name', 'status', 'approved_by', 'approved_date', 'rejected_by', 'rejected_date'];

    public function officeExpense()
    {
        return $this->belongsTo(OfficeExpense::class, 'office_expense_id');
    }

    public function officeExpenseItem()
    {
        return $this->hasMany(OfficeExpenseItem::class, 'office_expense_purchase_id');
    }

    public static function collectionOfficeExpensePurchase($id)
    {
        return OfficeExpensePurchase::with('officeExpenseItem')
            ->selectRaw(
                'office_expense_purchases.id,
                office_expense_purchases.purchase_name,
                MAX(office_expense_items.status) as status,
                COALESCE(SUM(CASE WHEN office_expense_items.status = "approved" THEN office_expense_items.total_expense ELSE 0 END), 0) as total_expense',
            )
            ->leftJoin('office_expense_items', 'office_expense_purchases.id', '=', 'office_expense_items.office_expense_purchase_id')
            ->where('office_expense_purchases.office_expense_id', $id)
            ->groupBy('office_expense_purchases.id', 'office_expense_purchases.purchase_name')
            ->paginate(10);
    }

    public function approved()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejected()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
}
