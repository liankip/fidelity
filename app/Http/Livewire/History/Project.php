<?php

namespace App\Http\Livewire\History;

use App\Exports\ProjectHistoryExport;
use App\Models\Project as ModelsProject;
use App\Models\PurchaseOrder;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Project extends Component
{
    public $projectdata, $datas, $search, $project_id;
    public function mount($id)
    {
        $this->project_id = $id;
        // $this->projectdata = ModelsProject::where("id", $id)->first();
        $this->projectdata = ModelsProject::findOrFail($id);
        // dd(ModelsProject::where("id", $id)->first());
    }
    
    public function render()
    {
        if ($this->search) {
            $this->datas = PurchaseOrder::with("pr", "podetail", "supplier")
                ->where(function ($query) {
                    $query->where("po_no", "like", "%" . $this->search . "%")
                        ->orWhere("pr_no", "like", "%" . $this->search . "%")
                        ->orWhereHas("supplier", function ($q1) {
                            $q1->where("name", "like", "%" . $this->search . "%");
                        });
                })
                ->whereHas("pr", function ($query) {
                    $query->where("project_id", $this->projectdata->id);
                })->where(function ($query) {
                    $query->where("status", "Approved")
                        ->orWhere("status", "Paid")
                        ->orWhere("status", "Partially Paid");
                })
                ->get();
        } else {
            $this->datas = PurchaseOrder::with("podetail", "supplier")
                ->whereHas("pr", function ($query) {
                    $query->where("project_id", $this->projectdata->id);
                })->where(function ($query) {
                    $query->where("status", "Approved")
                        ->orWhere("status", "Paid")
                        ->orWhere("status", "Partially Paid");
                })->get();
        }

        // Calculate Grand Total
        $grandTotal = 0;

        foreach ($this->datas as $po) {
            $totalAmount = collect($po->podetail)->sum('amount'); // Sum item amounts
            $ongkir = $po->deliver_status == 1 ? $po->tarif_ds : 0; // Shipping cost
            $ppn = isset($po->podetail->first()->tax_status) && $po->podetail->first()->tax_status == 2 ? 0 : round($totalAmount * 0.11);

            if ($po->tax_custom) {
                $ppn = $po->tax_custom;
            }

            $grandTotal += $totalAmount + $ppn + $ongkir;
        }

        return view('livewire.history.project', [
            'grandTotal' => $grandTotal, // Pass grand total to the view
        ]);
    }
    public function export()
    {
        $nameproject = ModelsProject::where("id", $this->project_id)->first();
        return Excel::download(new ProjectHistoryExport($this->project_id), "history_by_project_" . $nameproject->name . ".xlsx");
    }
}
