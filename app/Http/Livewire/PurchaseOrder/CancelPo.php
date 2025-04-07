<?php

namespace App\Http\Livewire\PurchaseOrder;

use Livewire\Component;

class CancelPo extends Component
{
    public $po_id;
    public $po_no;

    public function mount($po_id, $po_no)
    {
        $this->$po_id = $po_id;
        $this->po_no = $po_no;
    }

    public function render()
    {
        return view('livewire.purchase-order.cancel-po');
    }

}
