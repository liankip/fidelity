<?php

namespace App\Http\Livewire\PurchaseOrder;

use App\Exports\PurchaseOrderExport;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ExportPo extends Component
{
    public $date_from;
    public $date_to;
    public $supplier;

    public function render()
    {
        $supplierId = PurchaseOrder::select('supplier_id')->distinct()->get();
        $supplierData = Supplier::whereIn('id', $supplierId)->orderBy('name', 'ASC')->get();
        return view('livewire.purchase-order.export-po', compact('supplierData'));
    }

    public function export() {
        $this->validate([
            'date_from' => 'required',
            'date_to' => 'required',
        ]);
        return Excel::download(new PurchaseOrderExport($this->date_from, $this->date_to, $this->supplier), 'PurchaseOrder.xlsx');
    }
}
