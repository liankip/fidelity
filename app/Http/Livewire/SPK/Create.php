<?php

namespace App\Http\Livewire\SPK;

use App\Helpers\TermOfPayment\GenerateEstimate;
use App\Models\Item;
use App\Models\ItemUnit;
use App\Models\PaymentMetode;
use App\Models\Price;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestDetail;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Warehouse;
use App\Notifications\PurchaseOrderCreated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class Create extends Component
{
    public $purchaserequestdetail, $statuspr, $brand_partner, $payment_method, $purchaserequest, $pr;
    public $supplier, $harga, $tax, $jumlah;
    public $test, $suppliergroup, $prid;

    //addsupplier
    public $supplieradd, $itemadd, $priceadd, $priceshow, $priceold, $taxstatusadd = 1;

    public $itemall;
    public $supplierall;
    public $showaddprice = false;


    //add supplier
    public $showaddsp = false;
    public $modelsuppliername, $modelsupplierpic, $modelsuppliertop, $modelsupplieremail, $modelsupplierphone, $modelsupplieraddress, $modelsuppliercity, $modelsupplierprovince, $modelsupplierpos;

    public $warehouse;
    public $warehousemodel;

    public $kursmodel,$unit,$unit_selected;



    protected $rules = [
        'purchaserequestdetail.*.item_name' => 'required',
        'purchaserequestdetail.*.supplier' => 'required',
        'purchaserequestdetail.*.price' => 'required',
        'purchaserequestdetail.*.tax' => 'required',
        'purchaserequestdetail.*.payment' => 'required',
        'purchaserequestdetail.*.diantar' => 'required'
    ];

    public function mount($id)
    {

        $this->supplieradd = "";
        $this->itemadd = "";
        $this->priceadd = "";
        $this->priceshow = "";
        $this->priceold = "";

        $this->itemall = Item::all();


        $this->prid = $id;
        $this->purchaserequest = PurchaseRequest::where("id", $id)->first();
        $this->pr = PurchaseRequest::with("project")->where("id", $id)->first();

        // if ($this->purchaserequest->status == "Draft") {
        //     return redirect()->to('/purchase_requests');
        // }

        $this->purchaserequestdetail = PurchaseRequestDetail::where('pr_id', $id)->doesntHave("podetail")->get();
        $this->statuspr = PurchaseRequest::all()->where('id', $id);

        // dd($this->brand_partner);
        $this->payment_method = PaymentMetode::all();

        $this->warehouse = Warehouse::where("deleted_at", null)->get();

        foreach ($this->purchaserequestdetail as $key => $value) {
            $this->purchaserequestdetail[$key]["edit_name"] = false;
            $this->purchaserequestdetail[$key]["supplier"] = "";
            $this->purchaserequestdetail[$key]["price"] = "";
            $this->purchaserequestdetail[$key]["tax"] = "";
            $this->purchaserequestdetail[$key]["jumlah"] = "";
            $this->purchaserequestdetail[$key]["payment"] = "";
            $this->purchaserequestdetail[$key]["supplier_id"] = "";
            $this->purchaserequestdetail[$key]["note"] = "";
            $this->purchaserequestdetail[$key]["exclude_tax"] = "";
            $this->purchaserequestdetail[$key]["non_ppn"] = "";
            $this->purchaserequestdetail[$key]["non_ppn"] = "";
            $this->purchaserequestdetail[$key]["diantar"] = "";
            $this->purchaserequestdetail[$key]["tax_status"] = "";
            // dd($this->purchaserequestdetail);
        }
    }

    public function render()
    {

        $this->supplierall = Supplier::orderBy("name", "asc")->get();
        $this->brand_partner = Price::orderBy('price', 'ASC')->get();

        if ($this->taxstatusadd == 1) {
            # code...
            $priceoldconvert = str_replace(".", "", $this->priceold);
            $this->priceshow = number_format(round((int)$priceoldconvert - ((int)$priceoldconvert / 111 * 11)), 0, ",", ".");
            $this->priceadd =  round((int)$priceoldconvert - ((int)$priceoldconvert / 111 * 11));
        } else {
            $priceoldconvert = str_replace(".", "", $this->priceold);
            $this->priceshow = $this->priceold;
            $this->priceadd = $priceoldconvert;
        }

        foreach ($this->purchaserequestdetail as $key => $value) {

            if ($value["supplier"] != "") {
                $price = Price::where("id", $value["supplier"])->first();
                $this->purchaserequestdetail[$key]->edit_name = true;
                $this->purchaserequestdetail[$key]->price = $price->price;
                $this->purchaserequestdetail[$key]->tax = $price->tax;
                $this->purchaserequestdetail[$key]->jumlah = round($value['qty'] * $price->price);
                $this->purchaserequestdetail[$key]->payment = $price->supplier->term_of_payment;
                $this->purchaserequestdetail[$key]->supplier_id = $price->supplier->id;
                $this->purchaserequestdetail[$key]->exclude_tax = $price->tax_status;
                $this->purchaserequestdetail[$key]->non_ppn = $price->supplier->tax;
                $this->purchaserequestdetail[$key]->tax_status = $price->tax_status;
            } else {
                $this->purchaserequestdetail[$key]->tax_status = 6;
            }
        }

        $countmainaaray = count($this->purchaserequestdetail);
        $testno = 0;
        foreach ($this->purchaserequestdetail as $key1 => $value1) {
            if ($value1["supplier"]) {
                $testno += 1;
            }
        }
        if ($testno != $countmainaaray) {
            $disablesave = true;
        } else {
            $disablesave = false;
        }

        $no = 0;
        foreach ($this->purchaserequestdetail as $key => $value) {
            if ($value["supplier"] != "") {
                $data = Price::where('id', $value["supplier"])->first();
                if ($no == 0) {
                    $this->suppliergroup = [$data->supplier->id];
                } else {
                    array_push($this->suppliergroup, $data->supplier->id);
                }
                $no += 1;
            }
        }

        return view('livewire.spk.create', ["disablesave" => $disablesave, "testno" => $testno, "countmainaaray" => $countmainaaray]);
    }

    public function setEditName($key, $status)
    {
        $this->purchaserequestdetail[$key]["edit_name"] = $status;
    }

    public function saveEditName($key)
    {
        PurchaseRequestDetail::where('pr_id', $this->purchaserequestdetail[$key]["pr_id"])
            ->where('item_id', $this->purchaserequestdetail[$key]["item_id"])
            ->update(['item_name' => $this->purchaserequestdetail[$key]["item_name"]]);
        // Item::where('id', $this->purchaserequestdetail[$key]["item_id"])
        //     ->update(['name' => $this->purchaserequestdetail[$key]["item_name"]]);
        // $this->setEditName($key, false);
        session()->flash('success', 'Data Berhasil Diubah');
    }

    public function showdata()
    {
        // dd(array_unique($this->suppliergroup));
        dd($this->purchaserequestdetail);
    }

    public function reject($index, $id)
    {
        if (count($this->purchaserequestdetail) <= 1) {
            session()->flash("danger", "Item Less than 1");
            return;
        } else {

            unset($this->purchaserequestdetail[$index]);
        }
    }

    public function updatedSupplieradd($value)
    {
    }

    public function getDataItemPrice($result)
    {
        $this->supplieradd = $result->supplier_id;
        $this->itemadd = $result->item_id;
        $this->priceold = $result->price;
        $this->taxstatusadd = $result->tax_status;
    }
    protected $listeners = ['savedataemit' => 'savedata'];


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
                    if (isset($iteminpoval['reduce_qty'])) {
                        $etytemp = $iteminpoval['reduce_qty'];
                    } else {
                        $etytemp = $iteminpoval['qty'];
                    }
                    if ($prdetailcval->item_id == $iteminpoval["item_id"] && $prdetailcval->qty != $etytemp) {
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

        if (!$pr->partially) {
            if ($statuspr == "Partially") {
                PurchaseRequest::where("id", $this->prid)->update([
                    "partially" => true
                ]);
            }
        }

        foreach ($this->purchaserequestdetail as $key => $value) {
            if ($value["supplier"] != "") {
                $price = Price::where("id", $value["supplier"])->first();
                $this->purchaserequestdetail[$key]->price = $price->price;
                $this->purchaserequestdetail[$key]->tax = $price->tax;
                $this->purchaserequestdetail[$key]->jumlah = round($value['qty'] * $price->price);
                $this->purchaserequestdetail[$key]->payment = $price->supplier->term_of_payment;
                $this->purchaserequestdetail[$key]->supplier_id = $price->supplier->id;
                $this->purchaserequestdetail[$key]->exclude_tax = $price->tax_status;
                $this->purchaserequestdetail[$key]->non_ppn = $price->supplier->tax;
                $this->purchaserequestdetail[$key]->tax_status = $price->tax_status;
            } else {
                $this->purchaserequestdetail[$key]->tax_status = 6;
            }
        }

        $this->suppliergroup = array_unique($this->suppliergroup);

        $dataarrray = [];

        foreach ($this->suppliergroup as $key => $value) {

            $PRICE = Price::where('supplier_id', $value)->get()->first();
            $po = PurchaseOrder::create([
                "pr_no" => $this->purchaserequest->pr_no,
                "project_id" => $this->purchaserequest->project_id,
                "warehouse_id" => $this->warehousemodel,
                "date_request" => date("Y/m/d"),
                "payment_id" => 0,
                "term_of_payment" => $PRICE->supplier->term_of_payment,
                "do_id" => 0,
                "company_id" => 1,
                "delivery_service_id" => 0,
                "top_date" => null,
                "supplier_id" => $value,
                "status" =>  "Draft",
                "created_by" => Auth::user()->id
            ]);
            array_push($dataarrray, $po);
        }

        foreach ($dataarrray as $key => $val) {
            foreach ($this->purchaserequestdetail as $key => $value) {

                if ($val->supplier_id == $value->supplier_id) {
                    if ($value->non_ppn) {
                        $tax = 0;
                    } else {
                        $tax = $value->tax;
                    }

                    $deliver_status = (int)$value->diantar;

                    PurchaseOrderDetail::create([
                        "purchase_order_id" => $val->id,
                        "purchase_request_detail_id" => $value->id,
                        "item_id" => $value->item_id,
                        "qty" => $value->qty,
                        "price" => $value->price,
                        "tax" => $tax,
                        "amount" => $value->jumlah,
                        "tax_status" => $value->tax_status,
                        "deliver_status" => $deliver_status,
                    ]);

                    PurchaseOrder::where('id', $val->id)->update([
                        "deliver_status" => $deliver_status,
                    ]);
                }
            }
        }
        PurchaseRequest::where('id', $this->prid)->update([
            "status" => $statuspr
        ]);

        PurchaseRequest::where("id", $this->purchaserequest->id)->update([
            "warehouse_id" => $this->warehousemodel
        ]);

        //for maneger & purchesing
        $datauser = User::where("type", 2)->orWhere("type", 3)->orWhere("type", 5)->get();
        foreach ($datauser as $key => $user) {
            foreach ($dataarrray as $key => $value) {
                $podata = [
                    'po_no' => $value->po_no,
                    'po_detail' => $value->id,
                    'project_name' => $this->purchaserequest->project->name,
                    "created_by" => Auth::user()->name,
                    'podetail' => $value->podetail,
                    'supplier_name' => $value->supplier->name,
                ];
                Notification::send($user, new PurchaseOrderCreated($podata));
            }
        }

        return redirect()->route('purchase-orders')
            ->with('success', 'Anda berhasil memnbuat SPK dari PR ' . $this->purchaserequest->pr_no);
    }

    public function showmodalsp($itemid)
    {
        $this->itemall = Item::all();
        $this->showaddprice = true;
        $this->unit = ItemUnit::where("item_id", $itemid)->get();
        $this->unit_selected = $this->unit[0]->unit_id;
        $this->itemadd = $itemid;
    }
    public function closeshowsp()
    {
        $this->showaddprice = false;
        $this->itemadd = "";
        $this->supplieradd = "";
        $this->priceshow = "";
        $this->priceold = "";
        $this->unit_selected = "";
    }

    public function shomodalsupplier()
    {
        $this->showaddprice = false;
        $this->showaddsp = true;
    }
    public function closemodaladdsupplier()
    {
        $this->showaddsp = false;
        $this->showaddprice = true;
    }

    public function savesupplier()
    {
        $this->validate([
            'modelsuppliername' => 'required',
            'modelsupplierpic' => 'required',
            'modelsupplierphone' => 'required',
            'modelsuppliercity' => 'required',
            'modelsupplierprovince' => 'required',
            'modelsuppliertop' => 'required'
        ]);

        $supplier = new Supplier;
        $supplier->name = $this->modelsuppliername;
        $supplier->pic = $this->modelsupplierpic;
        $supplier->email = $this->modelsupplieremail;
        $supplier->phone = $this->modelsupplierphone;
        $supplier->address = $this->modelsupplieraddress;
        $supplier->city = $this->modelsuppliercity;
        $supplier->province = $this->modelsupplierprovince;
        $supplier->post_code = $this->modelsupplierpos;
        $supplier->created_by = auth()->user()->id;
        $supplier->term_of_payment = $this->modelsuppliertop;
        $supplier->save();
        $this->showaddsp = false;
        $this->showaddprice = true;
    }

    public function clearForm()
    {
        $this->supplieradd = null;
        $this->itemadd = null;
        $this->priceadd = null;
        $this->priceold = null;
        $this->taxstatusadd = 1;
        $this->priceshow = null;
    }

    public function saveprice()
    {
        $this->validate([
            'itemadd' => 'required',
            'supplieradd' => 'required',
            'priceold' => 'required',
        ]);
        if ($this->taxstatusadd == 1) {
            $taxresult = 11;
            $taxstatusresul = 1;
        } elseif ($this->taxstatusadd == 2) {
            $taxstatusresul = 0;
            $taxresult = 11;
        } else {
            $taxstatusresul = 2;
            $taxresult = 0;
        }

        $result = Price::where('supplier_id', $this->supplieradd)->where('item_id', $this->itemadd)->first();
        if ($result) {
            $this->getDataItemPrice($result);

            Price::where('supplier_id', $this->supplieradd)
                ->where('item_id', $this->itemadd)
                ->update([
                    "supplier_id" => $this->supplieradd,
                    "item_id" => $this->itemadd,
                    "price" => $this->priceadd,
                    "tax" => $taxresult,
                    "tax_status" => $taxstatusresul,
                ]);
        } else {
            if ($this->kursmodel) {
                Price::create([
                    "supplier_id" => $this->supplieradd,
                    "item_id" => $this->itemadd,
                    "price" => $this->priceadd,
                    "tax" => $taxresult,
                    "depend_usd" => 1,
                    "old_idr_by_usd" => $this->kursmodel,
                    "created_by" => auth()->user()->id,
                    "tax_status" => $taxstatusresul,
                ]);
            } else {
                Price::create([
                    "supplier_id" => $this->supplieradd,
                    "item_id" => $this->itemadd,
                    "price" => $this->priceadd,
                    "tax" => $taxresult,
                    "created_by" => auth()->user()->id,
                    "tax_status" => $taxstatusresul,
                ]);
            }
        }

        $this->clearForm();

        $this->showaddprice = false;
    }
}
