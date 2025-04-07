<?php

namespace App\Http\Livewire\OfficeExpense;

use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class OfficeExpense extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    function export($office)
    {
        $this->emit('openModal', [
            'name' => 'office-expense.office-expense-export',
            'arguments' => [
                'office' => $office,
            ],
        ]);
    }

    public function render()
    {
        return view('livewire.office-expense.office-expense', [
            'data' => \App\Models\OfficeExpense::collectionOfficeExpense(),
        ]);
    }
}
