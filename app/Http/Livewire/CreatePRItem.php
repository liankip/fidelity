<?php

namespace App\Http\Livewire;

use Livewire\Component;


use App\Models\Item;
use App\Models\Project;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestDetail;
use App\Models\Setting;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Livewire\WithPagination;

class CreatePRItem extends Component
{
    use WithPagination;

    public $search;
    protected $updatesQueryString = ['search'];
    protected $paginationTheme = 'bootstrap';

    public $prid, $prequest;
    public $itemsarray = [];
    public $itemsarray1;
    public $cartmodal = false;

    public $showadditem = false;
    public $itemname, $itemunit, $matchitem;
    public $itfirst = false;

    public $setting;

    public Project $project;

    public function mount(Request $request, $id)
    {
        $this->setting = Setting::first();

        if (session()->has('oldsearch')) {
            $this->search = session("oldsearch");
        }

        $this->itfirst = $request->firstcreate;
        $this->prid = $id;

        $this->prequest = PurchaseRequest::where("id", $id)->first();

        $this->project = $this->prequest->project;

        $prd = PurchaseRequestDetail::with('item', 'podetail')->where("pr_id", $id)
            ->get();

        $itemIds = $prd->pluck('item_id')->toArray();
        $items_in_boq = $this->project->itemQuantity($itemIds);
        $prQty = PurchaseRequestDetail::getItemQuantityWithPrFilter($this->prequest->id, $itemIds, $this->project->id);

        // $prd = PurchaseRequestDetail::where("pr_id", $id)->get();
        foreach ($prd as $key => $value) {
            $item_in_boq = $items_in_boq[$value->item_id]->qty ?? 0;
            $item_in_pr = $prQty[$value->item_id]->total_qty ?? 0;
            $max_item = $item_in_boq - $item_in_pr;
            $po = $value->podetail->where('item_id', $value->item_id);
            $min_item = 0;

            if ($po->sum('qty') > 0) {
                $min_item = $po->sum('qty');
            }

            $this->itemsarray[] = [
                "id" => $value->item_id,
                "item_code" => $value->item ? $value->item->item_code : '',
                "name" => $value->item_name,
                "type" => $value->type,
                "unit" => $value->unit,
                "image" => $value->item ? $value->item->image : '',
                "created_by" => null,
                "updated_by" => null,
                "deleted_by" => null,
                "count" => $value->qty,
                "note" => $value->notes,
                "estimation_date" => $value->estimation_date,
                'min_item' => $min_item,
                'max_item' => $max_item,
            ];
        }
    }
    
    public function render()
    {
        if ($this->setting->boq || (!$this->setting->boq && $this->project->boq)) {
            if ($this->search) {
                $items = $this->project->purchaseRequestItems($this->prequest->id, $this->search);
            } else {
                $items = $this->project->purchaseRequestItems($this->prequest->id);
            }
        } else {
            if ($this->search) {
                $items = Item::where('name', "like", "%" . $this->search . "%")->paginate(10);
            } else {
                $items = Item::paginate(10);
            }
        }

        $itemIds = $items->pluck('item_id')->toArray();
        $prQuantities = PurchaseRequestDetail::getItemQuantity($itemIds, $this->project->id);

        foreach ($items as $item) {
            $item->item_in_pr = $prQuantities[$item->item_id]->total_qty ?? 0;
        }

        return view('livewire.create-p-r-item', compact('items'));
    }

    public function updatenote($key)
    {
        if (!isset($this->itemsarray[$key])) {
            return;
        }

        $item = $this->itemsarray[$key];
        if (isset($item["note"])) {
            $note = $item["note"];
        } else {
            $note = "";
        }
        PurchaseRequestDetail::where("pr_id", $this->prid)->where("item_id", $item["id"])->update([
            "notes" => $note,
            "updated_by" => auth()->user()->id,
        ]);
    }

    public function updateestimationdate($key)
    {
        if (!isset($this->itemsarray[$key])) {
            return;
        }

        $item = $this->itemsarray[$key];
        if (isset($item["estimation_date"])) {
            if ($item["estimation_date"]) {
                $estimation_date = $item["estimation_date"];
            } else {
                $estimation_date = null;
            }
        } else {
            $estimation_date = null;
        }
        PurchaseRequestDetail::where("pr_id", $this->prid)->where("item_id", $item["id"])->update([
            "estimation_date" => $estimation_date,
            "updated_by" => auth()->user()->id,
        ]);
    }

    public function updateqty($key, $item_id)
    {
        if (!isset($this->itemsarray[$key])) {
            return;
        }
    
        $item = $this->itemsarray[$key];

        if ($item["count"] == 0) {
            $resutl = PurchaseRequestDetail::where("pr_id", $this->prid)->where("item_id", $item["id"])->delete();
            unset($this->itemsarray[$key]);
            $this->changeStatuspr();
            
        } elseif ($item["count"] >= 1000000) {
            $resutl = PurchaseRequestDetail::where("pr_id", $this->prid)->where("item_id", $item["id"])->first();
            $this->itemsarray[$key]["count"] = number_format($resutl->qty, 0, "", "");
            
        } else {
            PurchaseRequestDetail::where("pr_id", $this->prid)->where("item_id", $item["id"])->update([
                "qty" => $item["count"],
                "updated_by" => auth()->user()->id,
            ]);

        }
    }

    private function changeStatuspr()
    {
        if ($this->prequest->status == "Partially") {
            if (count($this->itemsarray) == 0) {
                PurchaseRequest::where("id", $this->prid)->update([
                    "status" => "Processed"
                ]);
            }
        } elseif ($this->prequest->status == "Processed") {
            if (count($this->itemsarray) > 0) {
                PurchaseRequest::where("id", $this->prid)->update([
                    "status" => "Partially"
                ]);
            }
        }
    }

    public function removeitem($key)
    {
        PurchaseRequestDetail::where('pr_id', $this->prid)->where('item_id', $this->itemsarray[$key]["id"])->delete();
        unset($this->itemsarray[$key]);
        $this->changeStatuspr();
    }

    
}
