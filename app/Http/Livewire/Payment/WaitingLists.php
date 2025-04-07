<?php

namespace App\Http\Livewire\Payment;

use Livewire\Component;
use Illuminate\Http\Request;
use Livewire\WithPagination;
use App\Models\PurchaseOrder;

class WaitingLists extends Component
{
    public $search;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function mount(Request $request)
    {
        $this->search = $request->input('po');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        if ($this->search) {
            $po = PurchaseOrder::with("project", "warehouse", "podetail")
                ->where(function ($query) {
                    $query->where("po_no", "like", "%" . $this->search . "%")
                        ->orWhere("status", "like", "%" . $this->search . "%")
                        ->orWhere("term_of_payment", "like", "%" . $this->search . "%")
                        ->orWhereHas("project", function ($query) {
                            $query->where("name", "like", "%" . $this->search . "%");
                        })->orWhereHas("warehouse", function ($query) {
                            $query->where("name", "like", "%" . $this->search . "%");
                        });
                })
                ->Where(function ($query) {
                    $query->where(function ($q1) {
                        $q1->whereHas("invoices")
                            ->where("status", "Approved");
                    })->orWhere(function ($q1) {
                        $q1->where("status_barang", "Arrived")
                            ->where("term_of_payment", "CoD")
                            ->where("status", "!=", "Paid")
                            ->where("status", "!=", "Completed")
                            ->where("status", "!=", "partialy Paid");
                    });
                })->orderByDesc('updated_at')->paginate(15);
        } else {
            $po = PurchaseOrder::with("project", "warehouse", "podetail")
                ->Where(function ($query) {
                    $query->where(function ($q1) {
                        $q1->whereHas("invoices")
                            ->where("status", "Approved");
                    })->orWhere(function ($q1) {
                        $q1->where("status_barang", "Arrived")
                            ->where("term_of_payment", "CoD")
                            ->where("status", "!=", "Paid")
                            ->where("status", "!=", "Completed")
                            ->where("status", "!=", "partialy Paid");
                    });
                })->orderByDesc('updated_at')->paginate(15);
        }

        return view('livewire.payment.waiting-lists', ["po" => $po]);
    }
}
