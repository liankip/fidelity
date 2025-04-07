<?php

namespace App\Http\Livewire\History;

use App\Exports\ItemHistoryExport;
use App\Models\Item;
use App\Models\PurchaseOrder;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;

class Items extends Component
{
    public $datas;
    public $itemdata, $search, $item_id;
    public function mount($id)
    {
        $this->item_id = $id;
        $this->itemdata =  Item::where("id", $id)->first();
    }

    public function render()
    {
        if ($this->search) {
            $this->datas = PurchaseOrder::with("podetail", "project", "supplier")
                ->where(function ($query) {
                    $query->where("po_no", "like", "%" . $this->search . "%")
                        ->orWhereHas("project", function ($q1) {
                            $q1->where("name", "like", "%" . $this->search . "%");
                        })->orWhere("pr_no", "like", "%" . $this->search . "%")
                        ->orWhereHas("supplier", function ($q1) {
                            $q1->where("name", "like", "%" . $this->search . "%");
                        });
                })
                ->whereHas("podetail", function ($query) {
                    $query->where('item_id', $this->itemdata->id);
                })->where(function ($query) {
                    $query->where("status", "Approved")
                        ->orWhere("status", "Paid")
                        ->orWhere("status", "Partially Paid");
                })
                ->orderBy("created_at", "DESC")->get();
        } else {
            $this->datas = PurchaseOrder::with("podetail", "project", "supplier")
                ->whereHas("podetail", function ($query) {
                    $query->where('item_id', $this->itemdata->id);
                })->where(function ($query) {
                    $query->where("po_no", "!=", null)
                        ->where("status", "!=", "Cancel")
                        ->where("status", "!=", "Rejected")
                        ->where("status", "!=", "Wait For Approval");
                })
                ->orderBy("created_at", "DESC")->get();
        }

        return view('livewire.history.items');
    }
    public function export()
    {
        $itemname = Item::where("id", $this->item_id)->first();
        return FacadesExcel::download(new ItemHistoryExport($this->item_id), "History_by_item_" . $itemname->name . ".xlsx");
    }
}
