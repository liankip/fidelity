<?php

namespace App\Http\Livewire\OfficeExpense;

use App\Models\OfficeExpense;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class OfficeExpensePurchaseExport extends Component
{
    public $purchase;
    public $start_date;
    public $end_date;

    public function mount($purchase)
    {
        $this->purchase = $purchase;
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

        return Excel::download(new \App\Exports\OfficeExpensePurchaseExport($this->purchase, $this->start_date, $this->end_date), 'office-expense-purchase-' . date('d-m-y') . '.xlsx');
    }

    public function render()
    {
        return view('livewire.office-expense.office-expense-purchase-export');
    }
}
