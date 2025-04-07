<?php

namespace App\Http\Livewire;

use App\Models\OfficeExpenseItem;
use App\Models\OfficeExpensePurchase;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OfficeExpenseApproval extends Component
{
    public function approve($id)
    {
        OfficeExpenseItem::findOrFail($id)->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_date' => Carbon::now(),
        ]);

        return redirect()->route('office-expense-approval.index')->with('success', 'Office Expense has been approved.');
    }

    public function reject($id)
    {
        OfficeExpenseItem::findOrFail($id)->update([
            'status' => 'rejected',
            'rejected_by' => Auth::id(),
            'rejected_date' => Carbon::now(),
        ]);

        return redirect()->route('office-expense-approval.index')->with('success', 'Office Expense has been rejected.');
    }

    public function render()
    {
        return view('livewire.office-expense-approval', [
            'data' => OfficeExpenseItem::whereHas('officeExpensePurchase.officeExpense')
                ->with(['officeExpensePurchase.officeExpense'])
                ->where('status', 'pending')
                ->paginate(10),
        ]);
    }
}
