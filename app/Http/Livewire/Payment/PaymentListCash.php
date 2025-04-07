<?php

namespace App\Http\Livewire\Payment;

use App\Models\PurchaseOrder;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentListCash extends Component
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
            $cash = PurchaseOrder::with("project", "warehouse")
            ->where('status', 'Approved')
            ->where('term_of_payment', 'Cash')
            ->where(function ($query){
                $query->where("po_no","like","%".$this->search."%")
                ->orWhereHas("project", function($query){
                    $query->where("name","like","%".$this->search."%");
                });
            })
            ->orderBy("date_approved")->paginate(15);
        } else {

            $cash = PurchaseOrder::with("project", "warehouse","podetail")->where('status', 'Approved')->where('term_of_payment', 'Cash')->orderBy("date_approved")->paginate(15);
        }

        return view('livewire.payment.payment-list-cash', ['cash' => $cash]);
    }
}
