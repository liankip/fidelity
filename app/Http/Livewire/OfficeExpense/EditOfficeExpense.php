<?php

namespace App\Http\Livewire\OfficeExpense;

use App\Models\OfficeExpense;
use Livewire\Component;

class EditOfficeExpense extends Component
{
    public $id_office_expense;
    public $office;

    public function mount($id)
    {
        $this->id_office_expense = $id;
        $this->office = OfficeExpense::findOrfail($id)->office;
    }

    public function edit()
    {
        OfficeExpense::find($this->id_office_expense)->update([
            'office' => $this->office,
        ]);

        session()->flash('success', 'Office Expense Updated Successfully.');

        return redirect()->route('office-expense.index');
    }

    public function render()
    {
        return view('livewire.office-expense.edit-office-expense');
    }
}
