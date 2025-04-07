<?php

namespace App\Http\Livewire\OfficeExpense;

use App\Http\Livewire\Log\Purchase;
use App\Models\OfficeExpenseItem;
use App\Models\OfficeExpensePurchase;
use Livewire\Component;

class InsertOfficeExpenseItem extends Component
{
    public $office;
    public $purchase;
    public $office_expend_purchase_id;
    public $purchase_date;
    public $total_expense;
    public $receiver_name;
    public $vendor;
    public $account_number;
    public $notes;

    protected $rules = [
        'purchase_date' => 'required|date',
        'total_expense' => 'required|numeric',
        'receiver_name' => 'required|string',
        'vendor' => 'required|string',
        'account_number' => 'required|string',
        'notes' => 'nullable|string',
    ];

    public function mount($office, $purchase)
    {
        $this->office = $office;
        $this->purchase = $purchase;

        $this->office_expend_purchase_id = OfficeExpensePurchase::where('id', $purchase)->first()->id;
    }

    public function insert()
    {
        $this->validate();

        OfficeExpenseItem::create([
            'office_expense_purchase_id' => $this->office_expend_purchase_id,
            'purchase_date' => $this->purchase_date,
            'total_expense' => $this->total_expense,
            'receiver_name' => $this->receiver_name,
            'vendor' => $this->vendor,
            'account_number' => $this->account_number,
            'notes' => $this->notes,
        ]);

        session()->flash('success', 'Office expense item added successfully.');

        return redirect()->route('office-expense.item', ['office' => $this->office, 'purchase' => $this->purchase]);
    }

    public function render()
    {
        return view('livewire.office-expense.insert-office-expense-item');
    }
}
