<?php

namespace App\Http\Livewire\OfficeExpense;

use App\Models\OfficeExpenseItem;
use Livewire\Component;

class EditOfficeExpenseItem extends Component
{
    public $office_expense_item_id;
    public $office;
    public $purchase;
    public $purchase_date;
    public $total_expense;
    public $receiver_name;
    public $vendor;
    public $account_number;

    public $notes;

    public function mount($office, $purchase, $id)
    {
        $this->office_expense_item_id = $id;
        $this->office = $office;
        $this->purchase = $purchase;

        $data = OfficeExpenseItem::findOrFail($id);
        $this->purchase_date = $data->purchase_date;
        $this->total_expense = $data->total_expense;
        $this->notes = $data->notes;
    }

    public function edit()
    {
        $this->validate([
            'purchase_date' => 'date',
            'total_expense' => 'numeric',
            'receiver_name' => 'required|string',
            'vendor' => 'required|string',
            'account_number' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        OfficeExpenseItem::find($this->office_expense_item_id)->update([
            'purchase_date' => $this->purchase_date,
            'total_expense' => $this->total_expense,
            'receiver_name' => $this->receiver_name,
            'vendor' => $this->vendor,
            'account_number' => $this->account_number,
            'notes' => $this->notes,
        ]);

        session()->flash('success', 'Office expense updated successfully.');
        return redirect()->route('office-expense.item', ['office' => $this->office, 'purchase' => $this->purchase]);
    }

    public function render()
    {
        return view('livewire.office-expense.edit-office-expense-item');
    }
}
