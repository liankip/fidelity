<?php

namespace App\Http\Livewire\OfficeExpense;

use App\Models\OfficeExpense;
use App\Models\OfficeExpensePurchase;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;

class InsertOfficeExpensePurchase extends Component
{
    public $office;
    public $purchase_name;

    protected $rules = [
        'purchase_name' => 'required',
    ];

    public function mount($office)
    {
        $office = OfficeExpense::where('id', $office)->with('officeExpensePurchase')->first();

        if (!$office) {
            return Redirect::route('office-expense.purchase', $office->office);
        }

        $this->office = $office;
    }

    public function insert()
    {
        $this->validate();

        OfficeExpensePurchase::create([
            'office_expense_id' => $this->office->id,
            'purchase_name' => $this->purchase_name,
        ]);

        session()->flash('success', 'Office Expense Purchase Created Successfully.');

        return redirect()->route('office-expense.purchase', $this->office->id);
    }

    public function render()
    {
        return view('livewire.office-expense.insert-office-expense-purchase');
    }
}
