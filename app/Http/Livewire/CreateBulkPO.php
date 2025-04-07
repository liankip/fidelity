<?php

namespace App\Http\Livewire;

use App\Models\Item;
use App\Models\ItemUnit;
use App\Models\Project;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\RequestForQuotationDetail;
use App\Models\Supplier;
use App\Models\SupplierItemPrice;
use App\Models\Unit;
use App\Models\User;
use App\Models\Warehouse;
use App\Notifications\PurchaseOrderCreated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use App\Helpers\WarehouseHelper;

class CreateBulkPO extends Component
{
    public $boqItems, $projectId;
    public $quotationsCounts, $supplieradd, $itemadd, $priceadd, $priceshow, $priceold, $itemall, $warehouse;
    public $showaddsp, $showaddprice;

    public $supplierall, $brand_partner, $supplierItemPrice;
    public $taxstatusadd = 1;
    public $quantity = [];
    public $unit_po = [], $unit_po_selected = [];
    public $kursmodel, $warehousemodel, $potypemodel;


    protected $listeners = ['savedataemit' => 'savedata', 'savesupplieremit' => 'savesupplier'];

    protected $rules = [
        'boqItems.*.item_id' => 'required',
        'boqItems.*.item_name' => 'required',
        'boqItems.*.supplier' => 'required',
        'boqItems.*.supplier_id' => 'required',
        'boqItems.*.price' => 'required',
        'boqItems.*.qty' => 'required',
        'boqItems.*.reduce_qty' => 'required',
        'boqItems.*.tax' => 'required',
        'boqItems.*.payment' => 'required',
        'boqItems.*.diantar' => 'required',
        'boqItems.*.jumlah' => 'nullable',
        'boqItems.*.tax_status' => 'nullable',
        'boqItems.*.is_stock' => 'required',
        'boqItems.*.is_raw_materials' => 'required',
        'boqItems.*.new_item_name' => 'nullable',
        'boqItems.*.is_bulk' => 'required',


        'unit' => 'required',
        'unit_selected' => 'required',
        'unit_po_selected' => 'required'
    ];

    public function mount($id = null)
    {
        $this->projectId = $id;
        $sessionData = Session::get('selectedItems') ?? Session::get('checkedItems');
        if (empty($sessionData)) {
            return redirect()->route('bulk-purchase.index');
        }

        $this->boqItems = $sessionData;

        // Assign Stocks without Project
        if($this->boqItems->every(fn ($item) => $item->is_stock == true)){
            $this->boqItems = $this->boqItems->map(function ($item) {
                $item->item_id = $item->id;
                $item->is_stock = true;
                $this->quantity[$item->id] = 1;
                return $item;
            });
        }

        // Assign Raw Materials
        if($this->boqItems->every(fn ($item) => $item->is_raw_materials == true)){
            $this->boqItems = $this->boqItems->map(function ($item) {
                $item->item_id = $item->id;
                $item->is_raw_materials = true;
                $this->quantity[$item->id] = 1;
                return $item;
            });
        }

        $this->initializeVariables();
        $this->loadBoqData();
    }
    public function render()
    {
        $this->supplierall = Supplier::orderBy("name", "ASC")->where('is_approved', 1)->get();
        $this->brand_partner = SupplierItemPrice::orderBy('price', 'ASC')->get();

        $this->calculatePrice();

        $id = [];
        foreach ($this->boqItems as $key => $value) {
            if ($value["supplier"] != "") {
                $id[] = $value["supplier"];
            }
        }
        if (!empty($id)) {
            $data = SupplierItemPrice::whereIn('id', $id)->get();

            foreach ($data as $item) {
                $this->supplierItemPrice[] = $item->supplier_id;
            }
        }

        $countmainaaray = count($this->boqItems);
        $testno = $this->countSelectedSuppliers();


        $disablesave = $countmainaaray == $testno ? false : true;

        return view('livewire.create-bulk-p-o', compact('disablesave'));
    }

    private function initializeVariables()
    {
        $this->supplieradd = "";
        $this->itemadd = "";
        $this->priceadd = "";
        $this->priceshow = "";
        $this->priceold = "";

        $this->itemall = Item::all();
        $itemIds = $this->boqItems->pluck('item_id')->toArray();

        $this->setQuotation($itemIds);
        $this->warehouse = WarehouseHelper::getFilteredWarehouses($this->projectId);
    }

    private function loadBoqData()
    {
        foreach ($this->boqItems as $key => &$detail) {
            $detail["edit_name"] = false;
            $detail["supplier"] = "";
            $detail["price"] = "";
            $detail["tax"] = "";
            $detail["jumlah"] = "";
            $detail["payment"] = "";
            $detail["supplier_id"] = "";
            $detail["note"] = "";
            $detail["exclude_tax"] = "";
            $detail["non_ppn"] = "";
            $detail["diantar"] = "";
            $detail["tax_status"] = "";
            $detail["is_stock"] = $detail->is_stock ?? false;
            $detail["item_id"] = $detail->is_stock == true ? $detail->id : $detail->item_id;
            $detail["is_raw_materials"] = $detail->is_raw_materials ?? false;
            $detail['new_item_name'] = $detail->supplier_description ?? '-';
            $detail['is_bulk'] = $detail->is_bulk ?? false;
        }
    }
    private function setQuotation($itemIds)
    {
        $quotations = RequestForQuotationDetail::whereIn('item_id', $itemIds)->whereNotNull('price')->orderByDesc('updated_at')->get()->groupBy('item_id');
        $this->quotationsCounts = $quotations->map(function ($item) {
            return $item->count();
        });
    }

    private function calculatePrice()
    {
        $priceoldconvert = str_replace(".", "", $this->priceold);
        if ($this->taxstatusadd == 1) {
            $this->priceshow = number_format(round((int)$priceoldconvert - ((int)$priceoldconvert / 111 * 11)), 0, ",", ".");
            $this->priceadd = round((int)$priceoldconvert - ((int)$priceoldconvert / 111 * 11));
        } else {
            $this->priceshow = $this->priceold;
            $this->priceadd = $priceoldconvert;
        }
    }
    private function countSelectedSuppliers()
    {
        $count = 0;
        foreach ($this->boqItems as $value) {
            if ($value["supplier"]) {
                $count++;
            }
        }
        return $count;
    }

    public function setEditName($key, $status)
    {
        $this->boqItems[$key]['edit_name'] = $status;
    }

    public function saveEditName($key)
    {
        $editValue = $this->boqItems[$key]['new_item_name'];
        if ($editValue == '' || $editValue == null || $editValue == '-') {
            $editValue = null;
        } else {
            $this->boqItems[$key]['new_item_name'] = $editValue;
        }
    }

    public function getDataItemPrice($result)
    {
        $this->supplieradd = $result->supplier_id;
        $this->itemadd = $result->item_id;
        $this->priceold = $result->price;
        $this->taxstatusadd = $result->tax_status;
    }

    public function savedata()
    {
        DB::beginTransaction();
        try {

            foreach ($this->boqItems as $key => $value) {
                if ($value["supplier"] != "") {

                    if ($this->boqItems[$key]->reduce_qty != null) {
                        $this->boqItems[$key]->qty = $this->boqItems[$key]->reduce_qty;
                    } else {
                        $this->boqItems[$key]->qty = $value['qty'];
                    }

                    $price = SupplierItemPrice::where("id", $value["supplier"])->first();
                    $this->boqItems[$key]->price = $price->price;
                    $this->boqItems[$key]->tax = $price->tax;
                    $this->boqItems[$key]->jumlah = round($value['qty'] * $price->price);
                    $this->boqItems[$key]->payment = $price->supplier->term_of_payment;
                    $this->boqItems[$key]->supplier_id = $price->supplier->id;
                    $this->boqItems[$key]->exclude_tax = $price->tax_status;
                    $this->boqItems[$key]->non_ppn = $price->supplier->tax;
                    $this->boqItems[$key]->tax_status = $price->tax_status;
                } else {
                    $this->boqItems[$key]->tax_status = 6;
                }
            }

            $this->supplierItemPrice = array_unique($this->supplierItemPrice);

            foreach ($this->supplierItemPrice as $key => $value) {

                $the_price = SupplierItemPrice::where('supplier_id', $value)->get()->first();
                $po = PurchaseOrder::create([
                    "pr_no" => null,
                    "project_id" => $this->projectId,
                    "warehouse_id" => $this->warehousemodel,
                    "date_request" => date("Y/m/d"),
                    "payment_id" => 0,
                    "term_of_payment" => $the_price->supplier->term_of_payment,
                    "do_id" => 0,
                    "company_id" => 1,
                    "delivery_service_id" => 0,
                    "top_date" => null,
                    "supplier_id" => $value,
                    'po_type' => $this->potypemodel,
                    "created_by" => Auth::user()->id,
                ]);

                $dataarrray[] = $po;
            }

            $poDetailId = [];
            $newRecord = [];

            foreach ($dataarrray as $key => $val) {
                foreach ($this->boqItems as $key => $value) {
                    if ($val->supplier_id == $value->supplier_id) {
                        $tax = $value->non_ppn ? 0 : $value->tax;


                        $qty = $value->qty ?? $this->quantity[$value->item_id];
                        $deliver_status = (int)$value->diantar;

                        $isStock = $value->is_stock ?? 0;
                        $isRawMaterials = $value->is_raw_materials ?? 0;

                        $newPurchaseOrderDetail = PurchaseOrderDetail::create([
                            "purchase_order_id" => $val->id,
                            "purchase_request_detail_id" => null,
                            "item_id" => $value->item_id,
                            "qty" => $qty,
                            "unit" => $value->unit->name ?? $value->unit,
                            "price" => $value->price,
                            "tax" => $tax,
                            "amount" => $isStock || $isRawMaterials ? $this->quantity[$value->item_id] * $value->price : $value->jumlah,
                            "tax_status" => $value->tax_status,
                            "deliver_status" => $deliver_status,
                            "is_bulk" => $value->is_bulk ?? 0,
                            "is_stock" => $value->is_stock ?? 0,
                            "is_raw_materials" => $value->is_raw_materials ?? 0,
                            'supplier_description' => $value->new_item_name ?? "-"
                        ]);

                        $newRecord[] = $newPurchaseOrderDetail;


                        PurchaseOrder::where('id', $val->id)->update([
                            "deliver_status" => $deliver_status,
                        ]);
                    }
                    $poDetailId[] = $value->id;
                }
            }

            //for manager & purchasing
            $datauser = User::where("type", 2)->orWhere("type", 3)->orWhere("type", 5)->get();
            foreach ($datauser as $key => $user) {
                foreach ($dataarrray as $key => $value) {
                    $podata = [
                        'po_no' => $value->po_no,
                        'po_detail' => $value->id,
                        'project_name' => Project::where('id', $this->projectId)->first()->name ?? '',
                        "created_by" => Auth::user()->name,
                        'podetail' => $value->podetail,
                        'supplier_name' => $value->supplier->name,
                    ];
                    Notification::send($user, new PurchaseOrderCreated($podata));
                }
            }

            Session::forget('selectedItems');
            Session::forget('checkedItems');

            DB::commit();
            return redirect()->route('purchase-orders')
                ->with('success', 'Anda berhasil membuat Bulk PO');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public $unit, $unit_selected, $selected_key;

    public function showmodalsp($itemid, $key)
    {
        $this->itemall = Item::where('id', $itemid)->get();
        $this->showaddprice = true;
        $this->itemadd = $itemid;
        $this->unit = ItemUnit::where("item_id", $itemid)->get();
        $this->unit_selected = $this->unit[0]->unit_id;
        $this->selected_key = $key;
    }

    public function closeshowsp()
    {
        $this->showaddprice = false;
        $this->itemadd = "";
        $this->supplieradd = "";
        $this->priceshow = "";
        $this->priceold = "";
        $this->unit = "";
        $this->unit_selected = "";
        $this->selected_key = "";
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
            'itemadd' => ['required'],
            'supplieradd' => ['required'],
            'priceold' => ['required'],
            'unit_selected' => ['required']
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

        $result = SupplierItemPrice::where('supplier_id', $this->supplieradd)->where('item_id', $this->itemadd)->where("unit_id", $this->unit_selected)->first();
        if ($result) {
            $this->getDataItemPrice($result);

            $data_save = SupplierItemPrice::where('supplier_id', $this->supplieradd)
                ->where('item_id', $this->itemadd)
                ->update([
                    "supplier_id" => $this->supplieradd,
                    "item_id" => $this->itemadd,
                    "unit_id" => $this->unit_selected,
                    "price" => $this->priceadd,
                    "tax" => $taxresult,
                    "tax_status" => $taxstatusresul,
                ]);
            $data_save = SupplierItemPrice::where('supplier_id', $this->supplieradd)->first();
        } else {
            if ($this->kursmodel) {
                $data_save = SupplierItemPrice::create([
                    "supplier_id" => $this->supplieradd,
                    "item_id" => $this->itemadd,
                    "unit_id" => $this->unit_selected,
                    "price" => $this->priceadd,
                    "tax" => $taxresult,
                    "depend_usd" => 1,
                    "old_idr_by_usd" => $this->kursmodel,
                    "created_by" => auth()->user()->id,
                    "tax_status" => $taxstatusresul,
                ]);
            } else {
                $data_save = SupplierItemPrice::create([
                    "supplier_id" => $this->supplieradd,
                    "item_id" => $this->itemadd,
                    "unit_id" => $this->unit_selected,
                    "price" => $this->priceadd,
                    "tax" => $taxresult,
                    "created_by" => auth()->user()->id,
                    "tax_status" => $taxstatusresul,
                ]);
            }
        }

        $this->clearForm();
        $data = $data_save;

        $this->boqItems[$this->selected_key]["supplier"] = $data->id;

        $this->boqItems[$this->selected_key]->payment = $data->supplier->term_of_payment;
        $this->boqItems[$this->selected_key]->supplier_id = $data->supplier->id;
        $this->boqItems[$this->selected_key]->exclude_tax = $taxstatusresul;
        $this->boqItems[$this->selected_key]->non_ppn = $data->tax;
        $this->boqItems[$this->selected_key]->tax_status = $taxstatusresul;
        $this->boqItems[$this->selected_key]->price = $data->price;

        if ($this->boqItems[$this->selected_key]['reduce_qty'] != null) {
            if ((int)$this->boqItems[$this->selected_key]['reduce_qty'] == 0 or (int)$this->boqItems[$this->selected_key]['reduce_qty'] == null or (int)$this->boqItems[$this->selected_key]['reduce_qty'] == '') {
                $this->boqItems[$this->selected_key]->jumlah = 0;
            } else {
                $this->boqItems[$this->selected_key]->jumlah = round($this->boqItems[$this->selected_key]['reduce_qty'] * $data->price);
            }
        } else {
            if ((int)$this->boqItems[$this->selected_key]['qty'] == 0 or (int)$this->boqItems[$this->selected_key]['qty'] == null or (int)$this->boqItems[$this->selected_key]['qty'] == '') {
                $this->boqItems[$this->selected_key]->jumlah = 0;
            } else {
                $this->boqItems[$this->selected_key]->jumlah = round($this->boqItems[$this->selected_key]['qty'] * $data->price);
            }
        }

        $this->unit_po[$this->selected_key] = SupplierItemPrice::where('supplier_id', $data->supplier->id)->where('item_id', $data->item_id)->get();

        $this->unit_po_selected[$this->selected_key] = $this->unit_po[$this->selected_key][0]->unit_id;

        Session::put('unit_po_selected', $this->unit_po_selected);

        $this->selected_key = "";

        $this->showaddprice = false;

        return redirect()->route('bulk-purchase-order.create', $this->projectId)->with('success', 'Data Berhasil Disimpan');
    }

    public function resetSupplierItemPrice()
    {
        Session::forget('supplierItemPrice');

        $this->supplierItemPrice = [];
    }

    public function purchaserequestdetail_supplier($key)
    {
        $this->resetSupplierItemPrice();

        $data = SupplierItemPrice::where('id', $this->boqItems[$key]["supplier"])->first();

        $this->unit_po[$key] = SupplierItemPrice::where('supplier_id', $data->supplier_id)->where('item_id', $data->item_id)->get();

        $this->unit_po_selected[$key] = $this->unit_po[$key][0]->unit_id;

        Session::put('unit_po_selected', $this->unit_po_selected);

        $this->boqItems[$key]->payment = $this->unit_po[$key][0]->supplier->term_of_payment;
        $this->boqItems[$key]->supplier_id = $this->unit_po[$key][0]->supplier->id;
        $this->boqItems[$key]->exclude_tax = $this->unit_po[$key][0]->tax_status;
        $this->boqItems[$key]->non_ppn = $this->unit_po[$key][0]->supplier->tax;
        $this->boqItems[$key]->tax_status = $this->unit_po[$key][0]->tax_status;
        $this->boqItems[$key]->price = $this->unit_po[$key][0]->price;

        if ($this->boqItems[$key]['reduce_qty'] != null) {
            if (
                (int)$this->boqItems[$key]['reduce_qty'] == 0 or
                (int)$this->boqItems[$key]['reduce_qty'] == null or
                (int)$this->boqItems[$key]['reduce_qty'] == ''
            ) {
                $this->boqItems[$key]->jumlah = 0;
            } else {
                $this->boqItems[$key]->jumlah = round($this->boqItems[$key]['reduce_qty'] * $this->unit_po[$key][0]->price);
            }
        } else {
            if (
                (int)$this->boqItems[$key]['qty'] == 0 or
                (int)$this->boqItems[$key]['qty'] == null or
                (int)$this->boqItems[$key]['qty'] == ''
            ) {
                $this->boqItems[$key]->jumlah = 0;
            } else {
                $this->boqItems[$key]->jumlah = round($this->boqItems[$key]['qty'] * $this->unit_po[$key][0]->price);
            }
        }
    }

    public function unit_po_changed($key)
    {
        if (isset($this->boqItems[$key]->unit)) {
            if (isset($this->unit_po_selected[$key])) {
                $this->boqItems[$key]->unit = Unit::where('id', $this->unit_po_selected[$key])->first()->name;
                $find_supplier = SupplierItemPrice::where('id', $this->boqItems[$key]->supplier)->first();

                $get_supplier = SupplierItemPrice::where('supplier_id', $find_supplier->supplier_id)
                    ->where('item_id', $find_supplier->item_id)
                    ->where('unit_id', $this->unit_po_selected[$key])
                    ->first();

                $this->boqItems[$key]->price = $get_supplier->price;
                $this->boqItems[$key]->tax = $get_supplier->tax;
                $this->boqItems[$key]->tax_status = $get_supplier->tax_status;

                $this->boqItems[$key]->edit_name = true;

                if ($this->boqItems[$key]['reduce_qty'] != null) {
                    if ((int)$this->boqItems[$key]['reduce_qty'] == 0 or (int)$this->boqItems[$key]['reduce_qty'] == null or (int)$this->boqItems[$key]['reduce_qty'] == '') {
                        $this->boqItems[$key]->jumlah = 0;
                    } else {
                        $this->boqItems[$key]->jumlah = round($this->boqItems[$key]['reduce_qty'] * $get_supplier->price);
                    }
                } else {
                    if ((int)$this->boqItems[$key]['qty'] == 0 or (int)$this->boqItems[$key]['qty'] == null or (int)$this->boqItems[$key]['qty'] == '') {
                        $this->boqItems[$key]->jumlah = 0;
                    } else {
                        $this->boqItems[$key]->jumlah = round($this->boqItems[$key]['qty'] * $get_supplier->price);
                    }
                }

                $this->boqItems[$key]->payment = $get_supplier->supplier->term_of_payment;
                $this->boqItems[$key]->supplier_id = $get_supplier->supplier->id;
                $this->boqItems[$key]->exclude_tax = $get_supplier->tax_status;
                $this->boqItems[$key]->non_ppn = $get_supplier->supplier->tax;
                $this->boqItems[$key]->tax_status = $get_supplier->tax_status;
            }
        }

        Session::put('unit_po_selected', $this->unit_po_selected);
    }

    public function cancel_po()
    {
        Session::forget('selectedItems');
        Session::forget('unit_po_selected');
        Session::forget('id_po');
        Session::forget('checkedItems');
        return redirect()->route('bulk-purchase.index');
    }


}
