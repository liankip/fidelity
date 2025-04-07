<?php

namespace App\Http\Livewire\OfficeExpense;

use App\Models\OfficeExpense;
use App\Models\OfficeExpensePurchase as ModelsOfficeExpensePurchase;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class OfficeExpensePurchase extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $office;

    public function mount($office)
    {
        $office = OfficeExpense::where('id', $office)->with('officeExpensePurchase')->first();

        if (!$office) {
            return Redirect::route('office-expense.index');
        }

        $this->office = $office;
    }

    function export($purchase)
    {
        $this->emit('openModal', [
            'name' => 'office-expense.office-expense-purchase-export',
            'arguments' => [
                'purchase' => $purchase,
            ],
        ]);
    }

    public function render()
    {
        return view('livewire.office-expense.office-expense-purchase', [
            'data' => \App\Models\OfficeExpensePurchase::collectionOfficeExpensePurchase($this->office->id),
        ]);
    }
}
