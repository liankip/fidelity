<?php

namespace App\Http\Livewire\OfficeExpense;

use App\Models\OfficeExpense;
use Livewire\Component;

class InsertOfficeExpense extends Component
{
    public $office;
    public $purchase_name;
    public $purchase_date;
    public $total_expense;
    public $notes;

    public function insert()
    {
        $this->validate([
            'office' => 'required',
        ], [
            'office.required' => 'Kolom kantor wajib diisi.',
        ]);

        OfficeExpense::create([
            'office' => $this->office,
        ]);

        session()->flash('success', 'Office Expense Created Successfully.');

        return redirect()->route('office-expense.index');
    }

    public function render()
    {
        return view('livewire.office-expense.insert-office-expense');
    }
}
