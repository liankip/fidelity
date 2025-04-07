<?php

namespace App\Http\Livewire\Payment;

use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;

class History extends Component
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
            $payments = Payment::with("purchaseorder")
                ->where(function ($query) {
                    $query->where("status", "like", "%" . $this->search . "%")
                        ->orWhere("notes", "like", "%" . $this->search . "%");
                })
                ->orWhereHas("purchaseorder", function ($query) {
                    $query->where("po_no","like","%".$this->search."%")
                    ->orWhereHas("project", function ($query2){
                        $query2->where("name","like","%".$this->search."%");
                    })->orWhereHas("warehouse", function($query2){
                        $query2->where("name","like","%".$this->search."%");
                    });
                })
                ->orderBy('id', 'desc')->paginate(15);
        } else {
            $payments = Payment::with("purchaseorder")->orderBy('id', 'desc')->paginate(15);
        }

        return view('livewire.payment.history', ["payments" => $payments]);
    }
}
