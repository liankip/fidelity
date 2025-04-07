<?php

namespace App\Http\Livewire\Project;

use App\Exports\ProjectMonitoring;
use App\Models\BOQEdit;
use App\Models\Project;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseRequestDetail;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Monitoring extends Component
{
    public $datas, $items, $project, $idproject;
    public function mount($project_id)
    {
        $this->idproject = $project_id;
    }

    public function render()
    {
        $projectid = $this->idproject;
        $this->project = Project::where("id", $projectid)->first();
        $getboqterakhir = BOQEdit::where('project_id', $projectid)->max('revision');

        if ($getboqterakhir && $getboqterakhir != null) {
            $this->items = DB::table('b_o_q_edits')
                ->select('items.id AS item_id', 'items.name', 'b_o_q_edits.qty', 'units.name AS unit_name')
                ->join('items', 'items.id', '=', 'b_o_q_edits.item_id')
                ->leftJoin('units', 'b_o_q_edits.unit_id', '=', 'units.id')
                ->whereNull('b_o_q_edits.deleted_at')
                ->where('b_o_q_edits.project_id', 10)
                ->whereNotNull('b_o_q_edits.approved_by')
                ->where('b_o_q_edits.revision', $getboqterakhir)
                ->whereNull('items.deleted_at')
                ->get();
        } else {
            $this->items = DB::table('b_o_q_s')
                ->select('items.id AS item_id', 'items.name', 'b_o_q_s.qty', 'units.name AS unit_name')
                ->join('items', 'items.id', '=', 'b_o_q_s.item_id')
                ->leftJoin('units', 'b_o_q_s.unit_id', '=', 'units.id')
                ->whereNull('b_o_q_s.deleted_at')
                ->where('b_o_q_s.project_id', 10)
                ->whereNotNull('b_o_q_s.approved_by')
                ->whereNull('items.deleted_at')
                ->get();
        }
        foreach ($this->items as $key => $value) {
            $qty_pr_query = PurchaseRequestDetail::where("item_id", $value->item_id)->whereHas("purchaseRequest", function ($query) use ($projectid) {
                $query->where("project_id", $projectid)
                    ->whereNotIn("status", ["Cancel", "Reject", "Draft"]);
            });
            $qty_pr_data = $qty_pr_query->get();
            $idprdetail = [];
            foreach ($qty_pr_data as $value1) {
                array_push($idprdetail, $value1->id);
            }

            $qty_pr = $qty_pr_query->sum("qty");
            $qty_po_query = PurchaseOrderDetail::whereIn("purchase_request_detail_id", $idprdetail)->whereHas("po", function ($query) {
                $query->whereNotIn("status", ["Cacel", "Reject", "Draft"]);
            });
            $qty_po = $qty_po_query->sum("qty");
            $amount_po = $qty_po_query->sum("amount");
            $this->items[$key]->qty_pr = floatval($qty_pr);
            $this->items[$key]->qty_po = $qty_po;
            $this->items[$key]->amount_po = $amount_po;
            $this->items[$key]->qty = floatval($this->items[$key]->qty);
            $this->items[$key]->no = $key + 1;
        }
        return view('livewire.project.monitoring');
    }
    public function downloadexcel()
    {
        // dd($this->items);
        $newitems = $this->items;

        return Excel::download(new ProjectMonitoring($newitems), "project_monitoring.xlsx");
    }
}
