<?php

namespace App\Http\Livewire;

use App\Models\IdxPurchaseOrder;
use App\Models\PaymentMetode;
use App\Models\Price;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestDetail;
use App\Models\User;
use App\Notifications\PurchaseOrderCreated;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class PurchaseOrderEdit extends Component
{
    public $purchaserequestdetail,$statuspr,$brand_partner,$payment_method,$purchaserequest;
    public $mainarray;
    public $supplier, $harga,$tax,$jumlah;
    public $test,$suppliergroup,$prid;


    protected $rules = [
        'mainarray.*.supplier' => 'required',
        'mainarray.*.price' => 'required',
        'mainarray.*.tax' => 'required',
        'mainarray.*.paymnet' => 'required',
    ];

    public function mount($id)
    {
        $this->prid = $id;
        $this->purchaserequest = PurchaseRequest::where("id",$id)->first();
        if ($this->purchaserequest->status == "Draft") {
            return redirect()->to('/purchase_requests');
        }
        $this->purchaserequestdetail = PurchaseRequestDetail::where('pr_id',$id)->get();
        $this->statuspr = PurchaseRequest::all()->where('id',$id);
        $this->brand_partner = Price::orderBy('price','ASC')->get();
        // dd($this->brand_partner);
        $this->payment_method = PaymentMetode::all();
        $this->mainarray = $this->purchaserequestdetail->toArray();

        foreach ($this->mainarray as $key => $value) {
            $this->mainarray[$key]["supplier"] ="";
            $this->mainarray[$key]["price"] ="";
            $this->mainarray[$key]["tax"] ="";
            $this->mainarray[$key]["jumlah"] ="";
            $this->mainarray[$key]["payment"] ="";
            $this->mainarray[$key]["supplier_id"] ="";
        }
    }

    public function render()
    {
        foreach ($this->mainarray as $key => $value) {
            if ($value["supplier"] != "") {
                // dd($value["supplier"]);

                $price = Price::where("id",$value["supplier"])->first();
                // dd($price);
                $this->mainarray[$key]["price"] = $price->price;
                $this->mainarray[$key]["tax"] = $price->tax;
                // harga * qty - ((hrga*qty)*(tax/100))
                $this->mainarray[$key]["jumlah"] = $value['qty'] * $price->price - (($value['qty'] * $price->price)*($price->tax / 100));
                $this->mainarray[$key]["payment"] = $price->supplier->term_of_payment;
                $this->mainarray[$key]['supplier_id'] = $price->supplier->id;
            }
        }

        $no=0;
        foreach ($this->mainarray as $key => $value) {
            if ($value["supplier"] != "") {
                $data = Price::where('id',$value["supplier"])->first();
                if ($no == 0) {
                    $this->suppliergroup = [$data->supplier->id];
                }else {
                    array_push($this->suppliergroup,$data->supplier->id);
                }
                // $this->mainarray[$key]['supplier'] = $data->supplier->id;
                $no += 1;
            }
        }

        return view('livewire.purchase-order-edit');
    }
    public function showdata()
    {
        // dd(array_unique($this->suppliergroup));
        dd($this->mainarray);
    }

    public function savedata()
    {
        // dd(Auth::user());
        $this->suppliergroup = array_unique($this->suppliergroup);
        // $this->validate();

        $idxpo = IdxPurchaseOrder::orderBy('idx','desc')->first();

        $year = Carbon::now()->format('Y');
        $month = Carbon::now()->format('m');
        // dd($idx);
        $map = [
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        ];

        $returnValueRoman = '';
        while ($month > 0) {
            foreach ($map as $roman => $int) {
                if ($month >= $int) {
                    $month -= $int;
                    $returnValueRoman .= $roman;
                    break;
                }
            }
        }

        $dataarrray = [];
        $idx1 = $idxpo->idx;
        $idx = $idx1;
        foreach ($this->suppliergroup as $key => $value) {
            $po = PurchaseOrder::create([
                "po_no" => $idx."/PO/".env("NO_PREFIX")."/".$returnValueRoman."/".$year,
                "pr_no" => $this->purchaserequest->pr_no,
                "project_id" => $this->purchaserequest->project_id,
                "warehouse_id" => $this->purchaserequest->warehouse_id,
                "date_request" => date("Y/m/d"),
                "payment_id" => 0,
                "term_of_payment" => "CoD",
                "do_id" => 0,
                "company_id" => 1,
                "delivery_service_id" => 0,
                "top_date" => null,
                "supplier_id" => $value,
                "status" => "New",
                "created_by" => Auth::user()->id
            ]);
            $idx += 1 ;
            array_push($dataarrray,$po);
        }
        IdxPurchaseOrder::where('id',1)->update([
            "idx" => $idx
        ]);
        // dd($dataarrray->groupBy('suplier'));

        foreach ($dataarrray as $key => $val) {
            foreach ($this->mainarray as $key => $value) {
                // dd($value);

                if ($val->supplier_id == $value["supplier_id"]) {
                    PurchaseOrderDetail::create([
                        "purchase_order_id" => $val->id,
                        "purchase_request_detail_id" => $value["id"],
                        "item_id" => $value["item_id"],
                        "qty" => $value["qty"],
                        "price" => $value["price"],
                        "tax" => $value["tax"],
                        "amount" => $value["jumlah"],
                    ]);
                }
            }
        }
        PurchaseRequest::where('id',$this->prid)->update([
            "status" => "Draft"
        ]);

        // dd($dataarrray);
        // for created
        foreach ($dataarrray as $key => $value) {
            $podata = [
                'po_no' => $value->po_no,
                'po_detail' => $value->id,
                'project_name' => $this->purchaserequest->project->name,
                "created_by" => Auth::user()->name,
                'podetail' => $value->podetail,
                'supplier_name' => $value->supplier->name,
            ];
            Notification::send(Auth::user(), new PurchaseOrderCreated($podata));
        }

        //for maneger & purchesing
        $datauser = User::where("type",2)->orWhere("type",3)->orWhere("type",5)->get();
        foreach ($datauser as $key => $user) {
            # code...
            foreach ($dataarrray as $key => $value) {
                $podata = [
                    'po_no' => $value->po_no,
                    'po_detail' => $value->id,
                    "created_by" => Auth::user()->name
                ];
                Notification::send($user, new PurchaseOrderCreated($podata));
            }
        }
        return redirect()->to('/purchase-orders');
    }
}
