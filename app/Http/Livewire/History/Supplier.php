<?php

namespace App\Http\Livewire\History;

use App\Exports\SupplierHistoryExport;
use App\Models\PurchaseOrder;
use App\Models\Supplier as ModelsSupplier;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Supplier extends Component
{
    public $supplierdata, $search, $datas, $supplier_id;
    public function mount($id)
    {
        $this->supplier_id = $id;
        $this->supplierdata = ModelsSupplier::where("id", $id)->first();
    }
    public function render()
    {
        if ($this->search) {
            $this->datas = PurchaseOrder::with("pr")
                ->where(function ($query) {
                    $query->where("po_no", "like", "%" . $this->search . "%")
                        ->orWhere("pr_no", "like", "%" . $this->search . "%");
                })
                ->whereHas("pr", function ($query) {
                    $query->where("supplier_id", $this->supplierdata->id);
                })->where("status", "!=", "Cancel")
                ->where("status", "!=", "Rejected")
                ->where("status", "!=", "Wait For Approval")
                ->get();
        } else {
            $this->datas = PurchaseOrder::whereHas("pr", function ($query) {
                $query->where("supplier_id", $this->supplierdata->id);
            })->where(function ($query) {
                $query->where("status", "Approved")
                    ->orWhere("status", "Paid")
                    ->orWhere("status", "Partially Paid");
            })->get();
        }
        return view('livewire.history.supplier');
    }
    public function export()
    {
        $getsupplier = ModelsSupplier::where("id", $this->supplier_id)->first();
        return Excel::download(new SupplierHistoryExport($this->supplier_id), "Histoty by supplier " . $getsupplier->name . ".xlsx");
    }
}
