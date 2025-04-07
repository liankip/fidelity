<?php

namespace App\Http\Livewire\Payment;

use App\Constants\PurchaseOrderStatus;
use App\Models\PurchaseOrder;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentListDirection extends Component
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
            $ntp = PurchaseOrder::with("project", "warehouse", "podetail")
                ->where(function ($query) {
                    $query->where("po_no", "like", "%" . $this->search . "%")
                        ->orWhere("term_of_payment", "like", "%" . $this->search . "%")
                        ->orWhere("remark", "like", "%" . $this->search . "%")
                        ->orWhere("status", "like", "%" . $this->search . "%");
                })->where(function ($query) {
                    $query->where('status', PurchaseOrderStatus::NEED_TO_PAY)->orWhere('status', PurchaseOrderStatus::PARTIALLY_PAID);
                })
                ->orderBy("updated_at", "DESC")->paginate(15);
        } else {
            $ntp = PurchaseOrder::with("project", "warehouse", "podetail")
                ->where('status', PurchaseOrderStatus::NEED_TO_PAY)
                ->orWhere('status', PurchaseOrderStatus::PARTIALLY_PAID)
                ->orderBy("updated_at", "DESC")->paginate(15);
        }


        return view('livewire.payment.payment-list-direction', compact("ntp"));
    }
}
