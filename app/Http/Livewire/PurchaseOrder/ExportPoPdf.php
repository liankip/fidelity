<?php

namespace App\Http\Livewire\PurchaseOrder;

use App\Exports\PurchaseOrderTerimaBarangExport;
use App\Models\PurchaseOrder;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ExportPoPdf extends Component
{
    public $date_from;
    public $date_to;

    public function generateExcel()
    {
        $this->validate(
            [
                'date_from' => 'required',
                'date_to' => 'required|after_or_equal:date_from',
            ],
            [
                'date_from.required' => 'The Date From field is required.',
                'date_to.required' => 'The Date To field is required.',
                'date_to.after_or_equal' => 'The Date To must be a date after or equal to Date From.',
            ],
        );

        $purchase_orders = PurchaseOrder::purchaseOrderComplete($this->date_from, $this->date_to);

        if ($purchase_orders->isEmpty()) {
            session()->flash('danger', 'No data found.');
            return;
        }

        $fileName = 'Purchase Orders - ' . Carbon::now()->format('Y-m-d');

        return Excel::download(new PurchaseOrderTerimaBarangExport($purchase_orders), $fileName . '.xlsx');
    }

    public function render()
    {
        return view('livewire.purchase-order.export-po-pdf');
    }
}
