<?php

namespace App\Http\Livewire\PurchaseOrder;

use App\Models\DeliveryService;
use App\Models\HistoryPurchase;
use App\Models\PurchaseOrder as ModelsPurchaseOrder;
use Carbon\Carbon;
use Livewire\Component;

class ShippingCost extends Component
{
    public $po_id;
    public $purchase_order;
    public $shipping_cost,$delivery_service;
    public $ds;

    public function mount($po_id, $ds)
    {
        $this->po_id = $po_id;
        $this->ds = $ds;
        $this->purchase_order = ModelsPurchaseOrder::where("id", $po_id)->first();
        if ($this->purchase_order->deliver_status == 2) {
            ModelsPurchaseOrder::where("id", $po_id)->update([
                "status" => "Draft With Delivery Services",
            ]);
        }
        $this->delivery_service = $this->purchase_order->ds_id;
        $this->shipping_cost = $this->purchase_order->tarif_ds;
    }

    public function render()
    {
        return view('livewire.purchase-order.shipping-cost');
    }

    public function updatedDeliveryService()
    {
        $this->validate([
            'delivery_service' => 'required'
        ],[
            "delivery_service" => "jasa pengiriman"
        ]);

        $currentuser = auth()->user();
        $old_status = ModelsPurchaseOrder::where("id", $this->po_id)->first();
        $history = new HistoryPurchase();
        $history->action_start = $old_status->status;
        $history->action_end = 'Draft With Delivery Services';
        $history->referensi = $old_status->po_no;
        $history->action_by = $currentuser->id;
        $history->created_by = $currentuser->id;
        $history->action_date = Carbon::now();
        $history->created_at = Carbon::now();
        $history->save();
        ModelsPurchaseOrder::where("id", $this->po_id)->update([
            "status" => "Draft With Delivery Services",
            "ds_id" => $this->delivery_service
        ]);
    }
    public function updatedShippingCost()
    {

        $this->validate([
            'shipping_cost' => 'required'
        ],[
            "shipping_cost" => "ongkos kirim"
        ]);


        ModelsPurchaseOrder::where("id", $this->po_id)->update([
            "tarif_ds" => $this->shipping_cost
        ]);
    }
}
