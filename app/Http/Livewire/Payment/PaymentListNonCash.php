<?php

namespace App\Http\Livewire\Payment;

use App\Models\PurchaseOrder;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentListNonCash extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;


    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        if ($this->search) {
            $non_cash = PurchaseOrder::with("project", "warehouse")
            ->where(function ($query){
                $query->where("po_no","like","%".$this->search."%")
                ->orWhere("term_of_payment","like","%".$this->search."%")
                ->orWhereHas("project", function($q1){
                    $q1->where("name","like","%".$this->search."%");
                })->orWhereHas("warehouse", function($q1){
                    $q1->where("name","like","%".$this->search."%");
                });
            })
            ->where('status', 'Waiting For Payment')
            ->orderBy("updated_at", "DESC")->paginate(15);
        } else {
            $non_cash = PurchaseOrder::with("project", "warehouse")->where('status', 'Waiting For Payment')->orderBy("updated_at", "DESC")->paginate(15);
        }

        return view('livewire.payment.payment-list-non-cash', compact("non_cash"));
    }
}
