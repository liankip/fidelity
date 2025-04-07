<?php

namespace App\Http\Livewire;

use App\Models\Item;
use App\Models\GudangTransferRequest;
use App\Models\GudangTransferRequestDetail;

use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use Livewire\Component;

class ChooseItemGtr extends Component
{
    use WithPagination;
    public $search;
    protected $updatesQueryString = ['search'];
    protected $paginationTheme = 'bootstrap';

    public $prid, $prequest;
    public $itemsarray = [];
    public $itemsarray1;
    public $cartmodal = false;

    public function mount($id)
    {
        $this->prid = $id;
        // dd($id);
        $this->prequest = GudangTransferRequest::where("id", $id)->first();
        // $this->itemsarray1 = Item::all()->toArray();
        $prd = GudangTransferRequestDetail::where("pr_id", $id)->get();
        foreach ($prd as $key => $value) {
            $item = Item::where("id",$value->item_id)->first();
            array_push($this->itemsarray, [
                "id" => $value->item_id,
                "item_code" => $item->item_code,
                "name" => $value->item_name,
                "type" => $value->type,
                "unit" => $value->unit,
                "image" => $item->image,
                "created_by" => null,
                "updated_by" => null,
                "deleted_by" => null,
                "count" => number_format($value->qty,0,"","") ,
                "note" => $value->notes,
                // "estimation_date" => $value->estimation_date,
            ]);
        }
    }

    public function render()
    {
        if ($this->search) {
            $items = Item::where('name',"like","%".$this->search."%")->paginate(9);
        }else{
            $items = Item::paginate(9);
        }

        $userId = \Auth::id();
        $carts = \Cart::session($userId)->getContent();
        if (count($this->itemsarray)) {
            foreach ($this->itemsarray as $key => $value) {
                if ($value["count"] == 0) {
                    unset($this->itemsarray[$key]);
                }
            }
        }
        // dd($items);

        // return view('livewire.item-pr-index', [
        //     'items' => $this->search === null ?
        //         Item::paginate(12) :
        //         Item::where('name', 'like', '%'.$this->search.'%')->paginate(12),
        //      'carts'
        // ]);

        // dd($items);



        return view('livewire.choose-item-gtr', compact(
            'items',
            'carts'
        ));

    }
    public function additem($id)
    {
        $udahada = 0;
        $today = Carbon::now()->format('Y-m-d');
        $default_est_date = date('Y-m-d', strtotime('+7 days', strtotime($today)));
        foreach ($this->itemsarray as $key => $value) {
            if ($value["id"] == $id) {
                $udahada += 1;
            }
        }
        if ($udahada) {
            # code...
        } else {

            $item = Item::where('id', $id)->first();
            array_push($this->itemsarray, $item);
        }

        foreach ($this->itemsarray as $key => $value) {
            if ($value["id"] == $id) {
                // dd($value["count"]);
                if ($value["count"]) {
                    $this->itemsarray[$key]['count'] = $value["count"] + 1;
                    GudangTransferRequestDetail::where("pr_id",$this->prid)->where("item_id",$value["id"])->update([
                        "qty"=> $this->itemsarray[$key]['count']

                    ]);
                } else {
                    $this->itemsarray[$key]['count'] = 1;
                    // dd($this->prid);
                    GudangTransferRequestDetail::create([
                        "pr_id" => $this->prid,
                        "item_id" => $this->itemsarray[$key]["id"],
                        "item_name" => $this->itemsarray[$key]["name"],
                        "type" => $this->itemsarray[$key]["type"],
                        "unit" => $this->itemsarray[$key]["unit"],
                        "qty" => $this->itemsarray[$key]["count"],
                        "status" => "new",
                        "note" => $this->itemsarray[$key]["note"],
                        // "estimation_date" => $this->itemsarray[$key]["estimation_date"],
                    ]);
                }
            }
        }
        // foreach ($this->itemsarray as $key => $val) {
        //     // dd($val["note"]);
        //     if (!$val["note"]) {
        //         dd($val);
        //     }
        // }
    }
    public function subtractitem($id)
    {
        // dd($id);
        foreach ($this->itemsarray as $key => $value) {
            if ($value["id"] == $id) {
                // dd($this->itemsarray);

                if ($value["count"] > 1) {
                    $this->itemsarray[$key]["count"] = $value["count"] - 1;
                    GudangTransferRequestDetail::where('pr_id',$this->prid)->where("item_id",$value["id"])->update([
                        "qty" => $this->itemsarray[$key]["count"]
                    ]);
                } else {
                    unset($this->itemsarray[$key]);
                    GudangTransferRequestDetail::where('pr_id',$this->prid)->where("item_id",$value["id"])->delete();
                }
            }
        }
    }
    public function removeitem($key)
    {
        GudangTransferRequestDetail::where('pr_id',$this->prid)->where('item_id',$this->itemsarray[$key]["id"])->delete();
        unset($this->itemsarray[$key]);
    }
    public function updateqty($key)
    {
        $item = $this->itemsarray[$key];
        GudangTransferRequestDetail::where("pr_id",$this->prid)->where("item_id",$item["id"])->update([
            "qty" => $item["count"],
        ]);
    }
    public function updatenote($key)
    {
        $item = $this->itemsarray[$key];
        if(isset($item["note"])){
            $note = $item["note"];
        }else{
            $note = "";
        }
        GudangTransferRequestDetail::where("pr_id",$this->prid)->where("item_id",$item["id"])->update([
            "notes" => $note,
        ]);
    }
    // public function updateestimationdate($key)
    // {
    //     $item = $this->itemsarray[$key];
    //     if (isset($item["estimation_date"])) {
    //         $estimation_date = $item["estimation_date"];
    //     }else {
    //         $estimation_date =null;
    //     }
    //     GudangTransferRequestDetail::where("pr_id",$this->prid)->where("item_id",$item["id"])->update([
    //         "estimation_date" => $estimation_date,
    //     ]);
    // }
    public function showdata()
    {
        dd($this->itemsarray);
    }
    public function showcart()
    {
        dd($this->itemsarray);
    }
    public function closecart()
    {
        $this->cartmodal = false;
    }
    public function refresh()
    {

    }

    // public function render()
    // {
    //     return view('livewire.choose-item-gtrr');
    // }
}
