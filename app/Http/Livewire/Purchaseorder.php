<?php

namespace App\Http\Livewire;

use App\Exports\PurchaseOrderExport;
use App\Models\DeliveryService;
use App\Models\HistoryPurchase;
use App\Models\PaymentMetode;
use App\Models\PurchaseOrder as ModelsPurchaseOrder;
use App\Models\User;
use App\Notifications\RequestPOLimit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Purchaseorder extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $po;
    public $search;
    public $show_modal_po_limit;
    public $termOfPayment;
    public $ds;
    public $currentFilter = 'All';

    public function show_modal_po_limit()
    {
        $this->show_modal_po_limit = true;
    }

    public function close_modal_po_limit()
    {
        $this->show_modal_po_limit = false;
    }

    public function request_po_limit()
    {
        $reserved = User::whereIn('type', [2, 3, 4, 5])->get();
        foreach ($reserved as $key => $pur) {
            $podata = [
                'yang_meminta' => auth()->user()->name,
            ];
            Notification::send($pur, new RequestPOLimit($podata));
        }

        $this->show_modal_po_limit = false;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->ds = DeliveryService::all();
        $this->termOfPayment = PaymentMetode::whereIn('metode', ['Cicilan30', 'NET30', 'NET7', 'NET60'])->get();
    }

    public function render()
    {
        $purchase_orders = ModelsPurchaseOrder::filter($this->search, $this->currentFilter);

        return view('livewire.purchaseorder', ['purchase_orders' => $purchase_orders]);
    }

    public function filterHandler($filter)
    {
        $this->currentFilter = $filter;
    }

    public function showmodalpengirman($poid)
    {
        $this->emit('openModal', [
            'name' => 'purchase-order.shipping-cost',
            'arguments' => [
                'po_id' => $poid,
                'ds' => $this->ds,
            ],
        ]);
    }

    public function showmodalexportpo()
    {
        $this->emit('openModal', [
            'name' => 'purchase-order.export-po',
            'arguments' => [],
        ]);
    }

    public function showconfirmcancel($id, $po_no)
    {
        $this->emit('openModal', [
            'name' => 'purchase-order.cancel-po',
            'arguments' => [
                'po_id' => $id,
                'po_no' => $po_no,
            ],
        ]);
    }

    public function exportPo10Latest()
    {
        return redirect()->route('printpolatest');
    }

    public function modalExportPurchaseOrderPdf(): void
    {
        $this->emit('openModal', [
            'name' => 'purchase-order.export-po-pdf',
            'arguments' => [],
        ]);
    }
}
