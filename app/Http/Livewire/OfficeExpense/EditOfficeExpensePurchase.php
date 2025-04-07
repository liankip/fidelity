<?php

namespace App\Http\Livewire\OfficeExpense;

use App\Models\OfficeExpensePurchase;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;

class EditOfficeExpensePurchase extends Component
{
    public $office;
    public $id_office_expense;
    public $purchase_name;

    public function mount($office, $id)
    {
        $this->office = $office;
        $this->id_office_expense = $id;
        $purchase_name = OfficeExpensePurchase::findOrfail($id)->purchase_name;

        if (!$office) {
            return Redirect::route('office-expense.purchase', $office->office);
        }

        $this->purchase_name = $purchase_name;
    }

    public function edit()
    {
        OfficeExpensePurchase::find($this->id_office_expense)->update([
            'purchase_name' => $this->purchase_name,
        ]);

        session()->flash('success', 'Office Expense Purchase Updated Successfully.');

        return redirect()->route('office-expense.purchase', $this->office);
    }

    public function render()
    {
        return view('livewire.office-expense.edit-office-expense-purchase');
    }
}
