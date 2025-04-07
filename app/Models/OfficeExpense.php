<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeExpense extends Model
{
    use HasFactory;
    protected $table = 'office_expense';

    protected $fillable = ['office'];

    public function officeExpensePurchase()
    {
        return $this->hasMany(OfficeExpensePurchase::class);
    }

    public static function collectionOfficeExpense()
    {
        return OfficeExpense::with(['officeExpensePurchase.officeExpenseItem'])
            ->selectRaw(
                '
                office_expense.id,
                office_expense.office,
                MAX(office_expense_items.status) as status,
                COALESCE(SUM(CASE WHEN office_expense_items.status = "approved" THEN office_expense_items.total_expense ELSE 0 END), 0) as total_expense
            ',
            )
            ->leftJoin('office_expense_purchases', 'office_expense.id', '=', 'office_expense_purchases.office_expense_id')
            ->leftJoin('office_expense_items', 'office_expense_purchases.id', '=', 'office_expense_items.office_expense_purchase_id')
            ->groupBy('office_expense.id', 'office_expense.office',)
            ->paginate(10);
    }
}
