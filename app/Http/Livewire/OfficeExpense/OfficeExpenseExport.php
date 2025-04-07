<?php

namespace App\Http\Livewire\OfficeExpense;

use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class OfficeExpenseExport extends Component
{
    public $office;
    public $start_date;
    public $end_date;

    public function mount($office)
    {
        $this->office = $office;
    }

    public function export()
    {
        $this->validate(
            [
                'start_date' => 'required',
                'end_date' => 'required',
            ],
            [
                'start_date.required' => 'The start date field is required.',
                'end_date.required' => 'The end date field is required.',
            ],
        );

        $this->dispatchBrowserEvent('close-modal');

        return Excel::download(new \App\Exports\OfficeExpenseExport($this->office, $this->start_date, $this->end_date), 'office-expense-' . date('d-m-y') . '.xlsx');
    }

    public function render()
    {
        return view('livewire.office-expense.office-expense-export');
    }
}
