<?php

namespace App\Http\Livewire\OfficeExpense;

use App\Models\OfficeExpense;
use App\Models\OfficeExpensePurchase;
use Livewire\Component;
use Livewire\WithPagination;

class OfficeExpenseItem extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $office;
    public $purchase;
    public $purchaseModel;
    public $items;

    public function mount($office, $purchase)
    {
        $this->office = OfficeExpense::where('id', $office)->firstOrFail();
        $this->purchase = OfficeExpensePurchase::where('id', $purchase)->firstOrFail();
    }

    public function render()
    {
        return view('livewire.office-expense.office-expense-item', [
            'data' => $this->purchase->officeExpenseItem()->paginate(10),
        ]);
    }
}
