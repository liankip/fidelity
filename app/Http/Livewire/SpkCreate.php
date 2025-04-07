<?php

namespace App\Http\Livewire;

use App\Helpers\TermOfPayment\GenerateEstimate;
use App\Models\PaymentMetode;
use App\Models\Price;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestDetail;
use Livewire\Component;

class SpkCreate extends Component
{
    public $purchaserequestdetail, $statuspr, $brand_partner, $payment_method, $purchaserequest;
    public $mainarray;
    public $supplier, $harga, $tax, $jumlah;
    public $test, $suppliergroup, $prid;

    protected $rules = [
        'mainarray.*.supplier' => 'required',
        'mainarray.*.price' => 'required',
        'mainarray.*.tax' => 'required',
        'mainarray.*.paymnet' => 'required',
    ];

    public function mount($id)
    {
        $this->prid = $id;
        $this->purchaserequest = PurchaseRequest::where("id", $id)->first();
        if ($this->purchaserequest->status == "Draft") {
            return redirect()->to('/purchase_requests');
        }
        $this->purchaserequestdetail = PurchaseRequestDetail::where('pr_id', $id)->whereWhereDoesntHave('podetail')->get();
        $this->statuspr = PurchaseRequest::all()->where('id', $id);
        $this->brand_partner = Price::orderBy('price', 'ASC')->get();
        $this->payment_method = PaymentMetode::all();
        $this->mainarray = $this->purchaserequestdetail->toArray();

        foreach ($this->mainarray as $key => $value) {
            $this->mainarray[$key]["supplier"] = "";
            $this->mainarray[$key]["price"] = "";
            $this->mainarray[$key]["tax"] = "";
            $this->mainarray[$key]["jumlah"] = "";
            $this->mainarray[$key]["payment"] = "";
            $this->mainarray[$key]["supplier_id"] = "";
            $this->mainarray[$key]["tax_status"] = "";
        }
    }
    public function render()
    {
        foreach ($this->mainarray as $key => $value) {
            if ($value["supplier"] != "") {
                $price = Price::where("id", $value["supplier"])->first();
                $this->mainarray[$key]["price"] = $price->price;
                $this->mainarray[$key]["tax"] = $price->tax;
                // harga * qty - ((hrga*qty)*(tax/100))
                $this->mainarray[$key]["jumlah"] = $value['qty'] * $price->price - (($value['qty'] * $price->price) * ($price->tax / 100));
                $this->mainarray[$key]["payment"] = $price->supplier->term_of_payment;
                $this->mainarray[$key]['supplier_id'] = $price->supplier->id;
                $this->mainarray[$key]['tax_status'] = $price->tax_status;
            }
        }

        $no = 0;
        foreach ($this->mainarray as $key => $value) {
            if ($value["supplier"] != "") {
                $data = Price::where('id', $value["supplier"])->first();
                if ($no == 0) {
                    $this->suppliergroup = [$data->supplier->id];
                } else {
                    array_push($this->suppliergroup, $data->supplier->id);
                }
                // $this->mainarray[$key]['supplier'] = $data->supplier->id;
                $no += 1;
            }
        }

        return view('livewire.spk-create');
    }
    public function showdata()
    {
        // dd(array_unique($this->suppliergroup));
        dd($this->mainarray);
    }

    public function savedata()
    {

        $statuspr = "Processed";
        //check for po udah ada apa belom
        $pr = PurchaseRequest::with("po")->where("id", $this->prid)->first();

        $iteminpo = collect([]);
        if (count($pr->po)) {
            foreach ($pr->po as $value) {
                if ($value->status != "Cancel" && $value->status != "Rejected") {
                    foreach ($value->podetail as $pdettail) {
                        //cari id sudah ada apa belum
                        $countitemid = 0;
                        foreach ($iteminpo as $iteminpoval) {
                            if ($iteminpoval["item_id"] == $pdettail->item_id) {
                                $iteminpoval["qty"] = $iteminpoval["qty"] + $pdettail->qty;
                                $countitemid += 1;
                            }
                        }
                        if ($countitemid == 0) {
                            $iteminpo->push(collect($pdettail->ToArray()));
                        }
                    }
                }
            }
        }

        //merge item pr want to create po
        foreach ($this->purchaserequestdetail as $key => $value) {
            $countitemid = 0;
            foreach ($iteminpo as $iteminpoval) {
                if ($iteminpoval["item_id"] == $value->item_id) {
                    if ($value->reduce_qty) {
                        $iteminpoval["qty"] = $iteminpoval["qty"] + $value->reduce_qty;
                    } else {
                        $iteminpoval["qty"] = $iteminpoval["qty"] + $value->qty;
                    }
                    $countitemid += 1;
                }
            }
            if ($countitemid == 0) {
                $iteminpo->push(collect($value->ToArray()));
            }
        }

        //check qty is same
        $prdetailc = $pr->prdetail;
        if (count($iteminpo) == count($prdetailc)) {
            // dd($iteminpo);
            $wrongqty = 0;
            foreach ($iteminpo as $iteminpoval) {
                foreach ($prdetailc as $prdetailcval) {
                    if ($prdetailcval->item_id == $iteminpoval["item_id"] && $prdetailcval->qty != $iteminpoval["qty"]) {

                        $wrongqty += 1;
                    }
                }
            }
            if ($wrongqty == 0) {
            } else {
                $statuspr = "Partially";
            }
        } else {
            $statuspr = "Partially";
        }

        $this->suppliergroup = array_unique($this->suppliergroup);



        $dataarrray = [];

        foreach ($this->suppliergroup as $key => $value) {
            $PRICE = Price::where('supplier_id', $value)->get()->first();
            $po = PurchaseOrder::create([
                "pr_no" => $this->purchaserequest->pr_no,
                "project_id" => $this->purchaserequest->project_id,
                "warehouse_id" => $this->purchaserequest->warehouse_id,
                "date_request" => date("Y/m/d"),
                "payment_id" => 0,
                "term_of_payment" => $PRICE->supplier->term_of_payment,
                "do_id" => 0,
                "company_id" => 1,
                "delivery_service_id" => 0,
                "top_date" => null,
                "supplier_id" => $value,
                "status" => "Draft"
            ]);
            array_push($dataarrray, $po);
        }
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
                        "tax_status" => $value["tax_status"],
                    ]);
                }
            }
        }
        PurchaseRequest::where('id', $this->prid)->update([
            "status" => $statuspr
        ]);
        return redirect()->to('/purchase-orders');
    }
}
