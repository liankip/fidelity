<?php

namespace App\Http\Livewire;

use App\Helpers\WarehouseHelper;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\ItemUnit;
use App\Models\POPRPivotModel;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseOrderPriceComparison;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestDetail;
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

class PurchaseOrderCreate extends Component
{
    public $purchaserequestdetail, $statuspr, $brand_partner, $purchaserequest, $pr;
    public $supplier, $harga, $tax, $jumlah;
    public $test, $suppliergroup, $prid;
    public array $supplierItemPrice = [];

    //add supplier
    public $supplieradd,
        $itemadd,
        $priceadd,
        $priceshow,
        $priceold,
        $taxstatusadd = 1;

    public $itemall;
    public $supplierall;
    public $showaddprice = false;

    //add supplier
    public $showaddsp = false;
    public $modelsuppliername, $modelsupplierpic, $modelsuppliertop, $modelsupplieremail, $modelsupplierphone, $modelsupplieraddress, $modelsuppliercity, $modelsupplierprovince, $modelsupplierpos;

    public $warehouse;
    public $warehousemodel;
    public $potypemodel;

    public $kursmodel;

    public $quantity = [];
    public $relocatedQty = [];
    public $isQtyChecked = [];
    public $previousRelocatedQty = [];

    public $unit_po = [],
        $unit_po_selected = [];

    protected $rules = [
        'purchaserequestdetail.*.item_name' => 'required',
        'purchaserequestdetail.*.supplier' => 'required',
        'purchaserequestdetail.*.supplier_id' => 'required',
        'purchaserequestdetail.*.price' => 'required',
        'purchaserequestdetail.*.qty' => 'required',
        'purchaserequestdetail.*.reduce_qty' => 'required',
        'purchaserequestdetail.*.tax' => 'required',
        'purchaserequestdetail.*.payment' => 'required',
        'purchaserequestdetail.*.diantar' => 'required',
        'purchaserequestdetail.*.jumlah' => 'nullable',
        'purchaserequestdetail.*.tax_status' => 'nullable',
        'purchaserequestdetail.*.isRelocated' => 'nullable',
        'purchaserequestdetail.*.new_item_name' => 'nullable',

        'unit' => 'required',
        'unit_selected' => 'required',
        'unit_po_selected' => 'required',
    ];

    public $quotationsCounts;

    public function mount($id)
    {
        Session::forget('purchaserequestdetail');
        $this->initializeVariables($id);
        $this->loadPurchaseRequestDetails($id);

        if (Session::has('purchaserequestdetail')) {
            $this->purchaserequestdetail = Session::get('purchaserequestdetail');
            $this->unit_po_selected = Session::get('unit_po_selected');
        } else {
            Session::put('purchaserequestdetail', $this->purchaserequestdetail);
        }
    }

    private function setQuotation($itemIds)
    {
        $quotations = RequestForQuotationDetail::whereIn('item_id', $itemIds)->whereNotNull('price')->orderByDesc('updated_at')->get()->groupBy('item_id');
        $this->quotationsCounts = $quotations->map(function ($item) {
            return $item->count();
        });
    }

    private function initializeVariables($id)
    {
        $this->supplieradd = '';
        $this->itemadd = '';
        $this->priceadd = '';
        $this->priceshow = '';
        $this->priceold = '';

        $this->itemall = Item::all();

        $this->prid = $id;
        $this->purchaserequest = PurchaseRequest::find($id);
        $this->pr = PurchaseRequest::with('project')->find($id);

        $data = [];
        $data = Session::get('data');

        if ($data == null) {
            abort('404');
        }

        $projectId = PurchaseRequest::find($id)->project_id;
        $this->purchaserequestdetail = PurchaseRequestDetail::whereHas('purchaseRequest', function ($query) use ($projectId) {
            $query->where('project_id', $projectId);
        })
            ->whereIn('id', $data)
            ->get();

        $prData = PurchaseRequest::find($id);
        $projectId = $prData->project_id;

        if($prData->partof !== null) {

            $taskId = $prData->task->id;
    
            $checkInventoryRelocate = Inventory::where('project_id', $projectId)->where('new_task_id', $taskId)->pluck('prdetail_id')->toArray();

            if (count($checkInventoryRelocate) > 0) {
                $prDetailData = PurchaseRequestDetail::whereIn('id', $checkInventoryRelocate)
                    ->get()
                    ->map(function ($item) {
                        $item->is_relocated = true;
                        return $item;
                    });
    
                $this->purchaserequestdetail = $this->purchaserequestdetail->merge($prDetailData);
    
                foreach ($this->purchaserequestdetail as $detail) {
                    if ($detail->is_relocated) {
                        $prDetail = \App\Models\PurchaseRequestDetail::where('id', $detail->id)->first();
                        if ($prDetail) {
                            $inventory = \App\Models\Inventory::where('prdetail_id', $prDetail->id)->first();
                            if ($inventory) {
                                $this->relocatedQty[$detail->id] = $inventory->actual_qty;
                                $this->previousRelocatedQty[$detail->id] = $inventory->actual_qty;
                            }
                        }
                    }
                }
            }
        }

        $itemIds = $this->purchaserequestdetail->pluck('item_id')->toArray();

        $this->setQuotation($itemIds);
        $isRawMaterials = $this->purchaserequestdetail->every(fn ($item) => $item->is_raw_materials == 1);
        $this->warehouse = WarehouseHelper::getFilteredWarehouses($projectId, $isRawMaterials);
    }

    private function loadPurchaseRequestDetails($id)
    {
        foreach ($this->purchaserequestdetail as $key => &$detail) {
            $detail['edit_name'] = false;
            $detail['supplier'] = '';
            $detail['price'] = '';
            $detail['tax'] = '';
            $detail['jumlah'] = '';
            $detail['payment'] = '';
            $detail['supplier_id'] = '';
            $detail['note'] = '';
            $detail['exclude_tax'] = '';
            $detail['non_ppn'] = '';
            $detail['diantar'] = '';
            $detail['tax_status'] = '';
            $detail['isRelocated'] = $detail->is_relocated ?? false;
            $detail['new_item_name'] = $detail->supplier_description ?? '-';

            $countreduce_qty = 0;
            foreach ($detail->podetail as $qtyy) {
                $countreduce_qty += $qtyy->qty;
            }

            $detail['reduce_qty'] = ceil($detail['qty'] - $countreduce_qty) ?: null;

            $this->quantity[$key] = $detail['reduce_qty'] ?? $detail['qty'];

            if (!$detail->is_relocated) {
                if ($countreduce_qty == $detail['qty']) {
                    unset($this->purchaserequestdetail[$key]);
                }
            }
        }
    }

    public function render()
    {
        $this->supplierall = Supplier::orderBy('name', 'ASC')->where('is_approved', 1)->get();
        $this->brand_partner = SupplierItemPrice::orderBy('price', 'ASC')->get();

        $this->calculatePrice();

        $id = [];
        foreach ($this->purchaserequestdetail as $key => $value) {
            if ($value['supplier'] != '') {
                $id[] = $value['supplier'];
            }
        }
        if (!empty($id)) {
            $data = SupplierItemPrice::whereIn('id', $id)->get();

            foreach ($data as $item) {
                $this->supplierItemPrice[] = $item->supplier_id;
            }
        }

        $countmainaaray = count($this->purchaserequestdetail->where('is_bulk', 0));
        $testno = $this->countSelectedSuppliers();

        $relocatedItems = collect($this->purchaserequestdetail)->filter(function ($item) {
            return $item->isRelocated == true;
        });

        $uncheckedRelocatedItems = collect();

        foreach ($relocatedItems as $item) {
            $id = $item->id;

            if (!isset($this->isQtyChecked[$id]) || $this->isQtyChecked[$id] == false) {
                $uncheckedRelocatedItems->push($item);
            }
        }

        $totalUnchecked = count($uncheckedRelocatedItems);

        $disablesave = $testno != $countmainaaray - $totalUnchecked;

        if ($this->purchaserequestdetailHasChanged()) {
            Session::put('purchaserequestdetail', $this->purchaserequestdetail);
        }

        return view('livewire.purchase-order-create', compact('disablesave', 'totalUnchecked'));
    }

    private function purchaserequestdetailHasChanged()
    {
        $sessionValue = Session::get('purchaserequestdetail');

        return $this->purchaserequestdetail !== $sessionValue;
    }

    private function calculatePrice()
    {
        $priceoldconvert = str_replace('.', '', $this->priceold);
        if ($this->taxstatusadd == 1) {
            $this->priceshow = number_format(round((int) $priceoldconvert - ((int) $priceoldconvert / 111) * 11), 0, ',', '.');
            $this->priceadd = round((int) $priceoldconvert - ((int) $priceoldconvert / 111) * 11);
        } else {
            $this->priceshow = $this->priceold;
            $this->priceadd = $priceoldconvert;
        }
    }

    private function countSelectedSuppliers()
    {
        $count = 0;
        foreach ($this->purchaserequestdetail as $value) {
            if ($value['supplier']) {
                $count++;
            }
        }
        return $count;
    }

    public function setEditName($key, $status)
    {
        $this->purchaserequestdetail[$key]['edit_name'] = $status;
    }

    public function saveEditName($key)
    {
        $editValue = $this->purchaserequestdetail[$key]['new_item_name'];
        if ($editValue == '' || $editValue == null || $editValue == '-') {
            $editValue = null;
        } else {
            $editValue = $editValue;
        }
        PurchaseRequestDetail::where('pr_id', $this->purchaserequestdetail[$key]['pr_id'])
            ->where('item_id', $this->purchaserequestdetail[$key]['item_id'])
            ->update(['supplier_description' => $editValue]);
        session()->flash('success', 'Data Berhasil Diubah');
    }

    public function showdata()
    {
        dd($this->purchaserequestdetail);
    }

    public function reject($index, $id)
    {
        if (count($this->purchaserequestdetail) <= 1) {
            session()->flash('danger', 'Item Less than 1');
            return;
        } else {
            unset($this->purchaserequestdetail[$index]);
        }
    }

    public function getDataItemPrice($result)
    {
        $this->supplieradd = $result->supplier_id;
        $this->itemadd = $result->item_id;
        $this->priceold = $result->price;
        $this->taxstatusadd = $result->tax_status;
    }

    protected $listeners = ['savedataemit' => 'savedata', 'savesupplieremit' => 'savesupplier'];

    public function savedata()
    {
        DB::beginTransaction();
        try {
            $notRelocatedData = $this->purchaserequestdetail->filter(function ($detail) {
                return !$detail->isRelocated; // Check if isRelocated is false
            });

            $statuspr = 'Processed';
            $pr = PurchaseRequest::with('po')
                ->where('id', $this->prid)
                ->first();

            $iteminpo = collect([]);
            if (count($pr->po)) {
                foreach ($pr->po as $value) {
                    if ($value->status != 'Cancel' && $value->status != 'Rejected') {
                        foreach ($value->podetail as $pdettail) {
                            //cari id sudah ada apa belum
                            $countitemid = 0;
                            foreach ($iteminpo as $iteminpoval) {
                                if ($iteminpoval['item_id'] == $pdettail->item_id) {
                                    $iteminpoval['qty'] = $iteminpoval['qty'] + $pdettail->qty;
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
            foreach ($notRelocatedData as $key => $value) {
                $countitemid = 0;
                foreach ($iteminpo as $iteminpoval) {
                    if ($iteminpoval['item_id'] == $value->item_id) {
                        if ($value->reduce_qty) {
                            $iteminpoval['qty'] = $iteminpoval['qty'] + $value->reduce_qty;
                        } else {
                            $iteminpoval['qty'] = $iteminpoval['qty'] + $value->qty;
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
                $wrongqty = 0;
                foreach ($iteminpo as $iteminpoval) {
                    foreach ($prdetailc as $prdetailcval) {
                        if (isset($iteminpoval['reduce_qty'])) {
                            $etytemp = $iteminpoval['reduce_qty'];
                        } else {
                            $etytemp = $iteminpoval['qty'];
                        }
                        if ($prdetailcval->item_id == $iteminpoval['item_id'] && $prdetailcval->qty != $etytemp) {
                            $wrongqty += 1;
                        }
                    }
                }
                if ($wrongqty == 0) {
                } else {
                    $statuspr = 'Partially';
                }
            } else {
                $statuspr = 'Partially';
            }

            //set partially true dependent condition
            if (!$pr->partially) {
                if ($statuspr == 'Partially') {
                    PurchaseRequest::where('id', $this->prid)->update([
                        'partially' => true,
                    ]);
                }
            }

            $prData = $this->purchaserequestdetail->first()->purchaseRequest;
            $projectId = $prData->project_id;
            $poDetailData = [];

            if ($prData->partof !== null) {
                $taskId = $prData->task->id;
    
                $checkInventoryRelocate = Inventory::where('project_id', $projectId)->where('new_task_id', $taskId)->pluck('prdetail_id')->toArray();
    
                $poDetailData = PurchaseRequestDetail::whereIn('id', $checkInventoryRelocate)->pluck('id')->toArray();
            }

            foreach ($this->purchaserequestdetail as $key => $value) {
                $cek_data_pr_detail = PurchaseRequestDetail::where('pr_id', $value->pr_id)
                    ->where('item_id', $value->item_id)
                    ->first();

                $cek_data_po_detail = PurchaseOrderDetail::where('purchase_request_detail_id', $cek_data_pr_detail->id)
                    ->where('item_id', $value->item_id)
                    ->whereHas('po', function ($query) {
                        $query->where('status', '!=', 'Cancel')->where('status', '!=', 'Rejected');
                    })
                    ->sum('qty');

                $reduce_qty = ceil($cek_data_pr_detail->qty - $cek_data_po_detail);

                $isRelocate = in_array($cek_data_pr_detail->id, $poDetailData);

                if (!$isRelocate) {
                    if ($value->reduce_qty != null) {
                        if ($value->reduce_qty > $reduce_qty) {
                            session()->flash('danger', 'Qty melebihi batas yang ada di PR');
                            return;
                        }
                    } else {
                        if ($value->qty > $reduce_qty) {
                            session()->flash('danger', 'Qty melebihi batas yang ada di PR');
                            return;
                        }
                    }
                }
            }

            foreach ($this->purchaserequestdetail as $key => $value) {
                if ($value['supplier'] != '') {
                    if ($this->purchaserequestdetail[$key]->reduce_qty != null) {
                        $this->purchaserequestdetail[$key]->qty = $this->purchaserequestdetail[$key]->reduce_qty;
                    } else {
                        $this->purchaserequestdetail[$key]->qty = $value['qty'];
                    }

                    $price = SupplierItemPrice::where('id', $value['supplier'])->first();
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

            $this->supplierItemPrice = array_unique($this->supplierItemPrice);

            foreach ($this->supplierItemPrice as $key => $value) {
                $the_price = SupplierItemPrice::where('supplier_id', $value)->get()->first();
                $po = PurchaseOrder::create([
                    'pr_no' => $this->purchaserequest->pr_no,
                    'project_id' => $this->purchaserequest->project_id,
                    'warehouse_id' => $this->warehousemodel,
                    'date_request' => date('Y/m/d'),
                    'payment_id' => 0,
                    'term_of_payment' => $the_price->supplier->term_of_payment,
                    'do_id' => 0,
                    'company_id' => 1,
                    'delivery_service_id' => 0,
                    'top_date' => null,
                    'supplier_id' => $value,
                    'po_type' => $this->potypemodel,
                    'created_by' => Auth::user()->id,
                ]);

                $dataarrray[] = $po;
            }

            $poDetailId = [];
            $newRecord = [];
            $updatedRecord = [];
            $pivotData = [];

            $inventoryData = Inventory::all();
            foreach ($dataarrray as $key => $val) {
                foreach ($this->purchaserequestdetail as $key => $value) {
                    if ($val->supplier_id == $value->supplier_id && !$value->is_bulk) {
                        $tax = $value->non_ppn ? 0 : $value->tax;

                        $get_supplier = SupplierItemPrice::where('supplier_id', $value->supplier_id)
                            ->where('item_id', $value->item_id)
                            ->where('unit_id', $this->unit_po_selected[$key])
                            ->first();

                        if (in_array($value->id, $poDetailData)) {
                            $inventory = Inventory::where('project_id', $projectId)
                                ->where('new_task_id', $taskId)
                                ->where('item_id', $value->item_id)
                                ->first();

                            $qty = $inventory->actual_qty;
                            $inputQty = $this->relocatedQty[$value->id];

                            $podetail = PurchaseOrderDetail::where('purchase_request_detail_id', $value->id)->first();

                            $newPurchaseRequestDetail = PurchaseRequestDetail::create([
                                'pr_id' => $val->pr->id,
                                'item_id' => $value->item_id,
                                'item_name' => $value->item->name,
                                'type' => $value->item->type,
                                'unit' => $value->unit,
                                'qty' => $inputQty,
                                'created_by' => auth()->user()->id,
                                'updated_by' => auth()->user()->id,
                                'status' => 'baru',
                                'notes' => '',
                            ]);

                            $newPurchaseOrderDetail = PurchaseOrderDetail::create([
                                'purchase_order_id' => $val->id,
                                'purchase_request_detail_id' => $newPurchaseRequestDetail['id'],
                                'item_id' => $value->item_id,
                                'qty' => $inputQty,
                                'unit' => $get_supplier->unit->name,
                                'price' => $value->price,
                                'tax' => $tax,
                                'amount' => ($inputQty - $qty) * $value->price,
                                'tax_status' => $value->tax_status,
                                'deliver_status' => (int) $value->diantar,
                                'relocate_from' => $podetail->po->pr_no,
                                'task_id' => $taskId,
                                'supplier_description' => $value->supplier_description ?? "-"
                            ]);
                            $newRecord[] = $newPurchaseOrderDetail;

                            $pivotData[] = POPRPivotModel::create([
                                'po_id' => $val->id,
                                'pr_id' => $newPurchaseRequestDetail['id'],
                            ]);
                        } else {
                            $qty = $value->qty;
                            $deliver_status = (int) $value->diantar;

                            $newPurchaseOrderDetail = PurchaseOrderDetail::create([
                                'purchase_order_id' => $val->id,
                                'purchase_request_detail_id' => $value->id,
                                'item_id' => $value->item_id,
                                'qty' => $qty,
                                'unit' => $get_supplier->unit->name,
                                'price' => $value->price,
                                'tax' => $tax,
                                'amount' => $value->jumlah,
                                'tax_status' => $value->tax_status,
                                'deliver_status' => $deliver_status,
                                'supplier_description' => $value->supplier_description ?? "-",
                                'is_raw_materials' => $value->is_raw_materials
                            ]);

                            $newRecord[] = $newPurchaseOrderDetail;

                            PurchaseOrder::where('id', $val->id)->update([
                                'deliver_status' => $deliver_status,
                            ]);

                            $pivotData[] = POPRPivotModel::create([
                                'po_id' => $val->id,
                                'pr_id' => $value->purchaseRequest->id,
                            ]);
                        }
                        $poDetailId[] = $value->id;
                    }
                }
            }

//             foreach($this->purchaserequestdetail as $key => $value) {
//                 if($value->is_bulk == 1) {
//                     $inventoryItem = $inventoryData->where('item_id', $value->item_id)->first();
//                     $itemQty = $value->qty;
//
//                     $newQty = $inventoryItem->stock - $itemQty;
//
//                     $inventoryItem->histories()->create([
//                         "inventory_id" => $inventoryItem->id,
//                         "type" => "OUT",
//                         "stock_before" => $inventoryItem->stock,
//                         "stock_after" => $newQty,
//                         "stock_change" => $inventoryItem->stock - $newQty,
//                         "user_id" => auth()->id(),
//                     ]);
//
//                     $inventoryItem->update([
//                         "stock" => $newQty,
//                     ]);
//
//                 }
//             }

            $purchaserequestdetailIds = $this->purchaserequestdetail->pluck('id');

            $poDetailDiff = $purchaserequestdetailIds->diff($poDetailId);

            $poDetailData = PurchaseOrderDetail::whereIn('purchase_request_detail_id', $poDetailDiff)->get();

            if (count($poDetailDiff) > 0) {
                foreach ($poDetailData as $key => $value) {
                    $inventory = Inventory::where('project_id', $projectId)
                        ->where('new_task_id', $taskId)
                        ->where('item_id', $value->item_id)
                        ->first();

                    $qty = $inventory->actual_qty;

                    $podetail = PurchaseOrderDetail::where('purchase_request_detail_id', $value->purchase_request_detail_id)->first();
                    $podetail->update([
                        'qty' => $qty,
                        'relocate_from' => $podetail->po->pr_no,
                        'task_id' => $taskId,
                    ]);

                    $updatedRecord[] = $podetail;
                }
            }

            $uniquePrIds = array_unique($this->purchaserequestdetail->where('is_bulk', '!=', 1)->pluck('pr_id')->toArray());

            foreach ($uniquePrIds as $prId) {
                $prDetails = PurchaseRequestDetail::where('pr_id', $prId)->where('is_bulk', '!=', 1)->where('status', '!=', 'Cancel')->get();

                $poDetails = PurchaseOrderDetail::whereIn('purchase_request_detail_id', $prDetails->pluck('id'))
                    ->whereHas('po', function ($query) {
                        $query->where('status', '!=', 'Cancel');
                    })
                    ->get();

                $prIdsInPo = $poDetails->pluck('purchase_request_detail_id')->unique();
                $allExist = $prIdsInPo->count() === $prDetails->count();

                $prTotalQty = $prDetails->sum('qty');
                $poTotalQty = $poDetails->sum('qty');
                $quantitiesMatch = $prTotalQty === $poTotalQty;

                if ($allExist && $quantitiesMatch) {
                    $status = 'Processed';
                } elseif ($poDetails->isNotEmpty()) {
                    $status = 'Partially';
                }

                PurchaseRequest::where('id', $prId)->update([
                    'status' => $status,
                ]);
            }

            PurchaseRequest::where('id', $this->purchaserequest->id)->update([
                'warehouse_id' => $this->warehousemodel,
            ]);

            //for manager & purchasing
            $datauser = User::where('type', 2)->orWhere('type', 3)->orWhere('type', 5)->get();
            foreach ($datauser as $key => $user) {
                foreach ($dataarrray as $key => $value) {
                    $podata = [
                        'po_no' => $value->po_no,
                        'po_detail' => $value->id,
                        'project_name' => $this->purchaserequest->project->name ?? '-',
                        'created_by' => Auth::user()->name,
                        'podetail' => $value->podetail,
                        'supplier_name' => $value->supplier->name,
                    ];
                    Notification::send($user, new PurchaseOrderCreated($podata));
                }
            }

            Session::forget('purchaserequestdetail');
            Session::forget('unit_po_selected');
            Session::forget('id_po');

            DB::commit();

            return redirect()
                ->route('purchase-orders')
                ->with('success', 'Anda berhasil memnbuat PO dari PR ' . $this->purchaserequest->pr_no);
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
        $this->unit = ItemUnit::where('item_id', $itemid)->get();
        $this->unit_selected = $this->unit[0]->unit_id;
        $this->selected_key = $key;
    }

    public function closeshowsp()
    {
        $this->showaddprice = false;
        $this->itemadd = '';
        $this->supplieradd = '';
        $this->priceshow = '';
        $this->priceold = '';
        $this->unit = '';
        $this->unit_selected = '';
        $this->selected_key = '';
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
            'modelsuppliertop' => 'required',
        ]);

        $supplier = new Supplier();
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
            'unit_selected' => ['required'],
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

        $result = SupplierItemPrice::where('supplier_id', $this->supplieradd)
            ->where('item_id', $this->itemadd)
            ->where('unit_id', $this->unit_selected)
            ->first();
        if ($result) {
            $this->getDataItemPrice($result);

            $data_save = SupplierItemPrice::where('supplier_id', $this->supplieradd)
                ->where('item_id', $this->itemadd)
                ->update([
                    'supplier_id' => $this->supplieradd,
                    'item_id' => $this->itemadd,
                    'unit_id' => $this->unit_selected,
                    'price' => $this->priceadd,
                    'tax' => $taxresult,
                    'tax_status' => $taxstatusresul,
                ]);
            $data_save = SupplierItemPrice::where('supplier_id', $this->supplieradd)->first();
        } else {
            if ($this->kursmodel) {
                $data_save = SupplierItemPrice::create([
                    'supplier_id' => $this->supplieradd,
                    'item_id' => $this->itemadd,
                    'unit_id' => $this->unit_selected,
                    'price' => $this->priceadd,
                    'tax' => $taxresult,
                    'depend_usd' => 1,
                    'old_idr_by_usd' => $this->kursmodel,
                    'created_by' => auth()->user()->id,
                    'tax_status' => $taxstatusresul,
                ]);
            } else {
                $data_save = SupplierItemPrice::create([
                    'supplier_id' => $this->supplieradd,
                    'item_id' => $this->itemadd,
                    'unit_id' => $this->unit_selected,
                    'price' => $this->priceadd,
                    'tax' => $taxresult,
                    'created_by' => auth()->user()->id,
                    'tax_status' => $taxstatusresul,
                ]);
            }
        }

        $this->clearForm();
        $data = $data_save;

        $this->purchaserequestdetail[$this->selected_key]['supplier'] = $data->id;

        $this->purchaserequestdetail[$this->selected_key]->payment = $data->supplier->term_of_payment;
        $this->purchaserequestdetail[$this->selected_key]->supplier_id = $data->supplier->id;
        $this->purchaserequestdetail[$this->selected_key]->exclude_tax = $taxstatusresul;
        $this->purchaserequestdetail[$this->selected_key]->non_ppn = $data->tax;
        $this->purchaserequestdetail[$this->selected_key]->tax_status = $taxstatusresul;
        $this->purchaserequestdetail[$this->selected_key]->price = $data->price;

        if ($this->purchaserequestdetail[$this->selected_key]['reduce_qty'] != null) {
            if ((int) $this->purchaserequestdetail[$this->selected_key]['reduce_qty'] == 0 or (int) $this->purchaserequestdetail[$this->selected_key]['reduce_qty'] == null or (int) $this->purchaserequestdetail[$this->selected_key]['reduce_qty'] == '') {
                $this->purchaserequestdetail[$this->selected_key]->jumlah = 0;
            } else {
                $this->purchaserequestdetail[$this->selected_key]->jumlah = round($this->purchaserequestdetail[$this->selected_key]['reduce_qty'] * $data->price);
            }
        } else {
            if ((int) $this->purchaserequestdetail[$this->selected_key]['qty'] == 0 or (int) $this->purchaserequestdetail[$this->selected_key]['qty'] == null or (int) $this->purchaserequestdetail[$this->selected_key]['qty'] == '') {
                $this->purchaserequestdetail[$this->selected_key]->jumlah = 0;
            } else {
                $this->purchaserequestdetail[$this->selected_key]->jumlah = round($this->purchaserequestdetail[$this->selected_key]['qty'] * $data->price);
            }
        }

        $this->unit_po[$this->selected_key] = SupplierItemPrice::where('supplier_id', $data->supplier->id)
            ->where('item_id', $data->item_id)
            ->get();

        $this->unit_po_selected[$this->selected_key] = $this->unit_po[$this->selected_key][0]->unit_id;

        Session::put('unit_po_selected', $this->unit_po_selected);

        $this->selected_key = '';

        $this->showaddprice = false;

        $this->saveComparisonPrice($data);

        return redirect()
            ->route('purchase_order_create', $this->prid)
            ->with('success', 'Data Berhasil Disimpan');
    }

    private function saveComparisonPrice($itemPrice)
    {
        $priceComparison = PurchaseOrderPriceComparison::where('purchase_request_id', $this->prid)
            ->where('item_id', $itemPrice->item_id)
            ->first();

        if (is_null($priceComparison)) {
            PurchaseOrderPriceComparison::create([
                'purchase_request_id' => $this->prid,
                'item_id' => $itemPrice->item_id,
                'supplier_item_price_id' => $itemPrice->id,
            ]);
        }
    }

    public function resetSupplierItemPrice()
    {
        Session::forget('supplierItemPrice');

        $this->supplierItemPrice = [];
    }

    public function purchaserequestdetail_supplier($key)
    {
        $this->resetSupplierItemPrice();

        $data = SupplierItemPrice::where('id', $this->purchaserequestdetail[$key]['supplier'])->first();

        $this->unit_po[$key] = SupplierItemPrice::where('supplier_id', $data->supplier_id)
            ->where('item_id', $data->item_id)
            ->get();

        $this->unit_po_selected[$key] = $this->unit_po[$key][0]->unit_id;

        Session::put('unit_po_selected', $this->unit_po_selected);

        $this->purchaserequestdetail[$key]->payment = $this->unit_po[$key][0]->supplier->term_of_payment;
        $this->purchaserequestdetail[$key]->supplier_id = $this->unit_po[$key][0]->supplier->id;
        $this->purchaserequestdetail[$key]->exclude_tax = $this->unit_po[$key][0]->tax_status;
        $this->purchaserequestdetail[$key]->non_ppn = $this->unit_po[$key][0]->supplier->tax;
        $this->purchaserequestdetail[$key]->tax_status = $this->unit_po[$key][0]->tax_status;
        $this->purchaserequestdetail[$key]->price = $this->unit_po[$key][0]->price;

        if ($this->purchaserequestdetail[$key]['reduce_qty'] != null) {
            if ((int) $this->purchaserequestdetail[$key]['reduce_qty'] == 0 or (int) $this->purchaserequestdetail[$key]['reduce_qty'] == null or (int) $this->purchaserequestdetail[$key]['reduce_qty'] == '') {
                $this->purchaserequestdetail[$key]->jumlah = 0;
            } else {
                $this->purchaserequestdetail[$key]->jumlah = round($this->purchaserequestdetail[$key]['reduce_qty'] * $this->unit_po[$key][0]->price);
            }
        } else {
            if ((int) $this->purchaserequestdetail[$key]['qty'] == 0 or (int) $this->purchaserequestdetail[$key]['qty'] == null or (int) $this->purchaserequestdetail[$key]['qty'] == '') {
                $this->purchaserequestdetail[$key]->jumlah = 0;
            } else {
                $this->purchaserequestdetail[$key]->jumlah = round($this->purchaserequestdetail[$key]['qty'] * $this->unit_po[$key][0]->price);
            }
        }
    }

    public function unit_po_changed($key)
    {
        if (isset($this->purchaserequestdetail[$key]->unit)) {
            if (isset($this->unit_po_selected[$key])) {
                $this->purchaserequestdetail[$key]->unit = Unit::where('id', $this->unit_po_selected[$key])->first()->name;
                $find_supplier = SupplierItemPrice::where('id', $this->purchaserequestdetail[$key]->supplier)->first();

                $get_supplier = SupplierItemPrice::where('supplier_id', $find_supplier->supplier_id)
                    ->where('item_id', $find_supplier->item_id)
                    ->where('unit_id', $this->unit_po_selected[$key])
                    ->first();

                $this->purchaserequestdetail[$key]->price = $get_supplier->price;
                $this->purchaserequestdetail[$key]->tax = $get_supplier->tax;
                $this->purchaserequestdetail[$key]->tax_status = $get_supplier->tax_status;

                $this->purchaserequestdetail[$key]->edit_name = true;

                if ($this->purchaserequestdetail[$key]['reduce_qty'] != null) {
                    if ((int) $this->purchaserequestdetail[$key]['reduce_qty'] == 0 or (int) $this->purchaserequestdetail[$key]['reduce_qty'] == null or (int) $this->purchaserequestdetail[$key]['reduce_qty'] == '') {
                        $this->purchaserequestdetail[$key]->jumlah = 0;
                    } else {
                        $this->purchaserequestdetail[$key]->jumlah = round($this->purchaserequestdetail[$key]['reduce_qty'] * $get_supplier->price);
                    }
                } else {
                    if ((int) $this->purchaserequestdetail[$key]['qty'] == 0 or (int) $this->purchaserequestdetail[$key]['qty'] == null or (int) $this->purchaserequestdetail[$key]['qty'] == '') {
                        $this->purchaserequestdetail[$key]->jumlah = 0;
                    } else {
                        $this->purchaserequestdetail[$key]->jumlah = round($this->purchaserequestdetail[$key]['qty'] * $get_supplier->price);
                    }
                }

                $this->purchaserequestdetail[$key]->payment = $get_supplier->supplier->term_of_payment;
                $this->purchaserequestdetail[$key]->supplier_id = $get_supplier->supplier->id;
                $this->purchaserequestdetail[$key]->exclude_tax = $get_supplier->tax_status;
                $this->purchaserequestdetail[$key]->non_ppn = $get_supplier->supplier->tax;
                $this->purchaserequestdetail[$key]->tax_status = $get_supplier->tax_status;
            }
        }

        Session::put('unit_po_selected', $this->unit_po_selected);
    }

    public function cancel_po()
    {
        Session::forget('purchaserequestdetail');
        Session::forget('unit_po_selected');
        Session::forget('id_po');
        return Redirect(url('purchase_requests'));
    }

    public function toggleCheck($id)
    {
        if (empty($this->isQtyChecked[$id]) || !$this->isQtyChecked[$id]) {
            foreach ($this->purchaserequestdetail as $key => $detail) {
                if ($detail['id'] == $id) {
                    $this->purchaserequestdetail[$key]['supplier'] = '';
                    $this->relocatedQty[$id] = $this->previousRelocatedQty[$id];
                    break;
                }
            }
        }
        $this->updateSupplierItemPrice();
    }

    public function updateSupplierItemPrice()
    {
        $supplierIds = [];

        foreach ($this->purchaserequestdetail as $detail) {
            if (!empty($detail['supplier'])) {
                $supplierIds[] = $detail['supplier'];
            }
        }

        if (!empty($supplierIds)) {
            $data = SupplierItemPrice::whereIn('id', $supplierIds)->get();

            $this->supplierItemPrice = $data->pluck('supplier_id')->unique()->toArray();
        } else {
            $this->supplierItemPrice = [];
        }
    }
}
