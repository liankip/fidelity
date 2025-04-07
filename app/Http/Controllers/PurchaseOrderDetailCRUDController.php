<?php

namespace App\Http\Controllers;

use App\Models\DeliveryOrder;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\SubmitionHistory;
use Illuminate\Http\Request;

class PurchaseOrderDetailCRUDController extends Controller
{
    public function index($po_id)
    {
        $purchaseorderdetail = PurchaseOrderDetail::with('item')->orderBy('id', 'desc')->paginate(8);
        $statuspo = PurchaseOrder::where('id', $po_id)->get();

        $do = DeliveryOrder::where('referensi', $statuspo->po_no)->get();
        $sh = SubmitionHistory::where('po_id', $po_id)->get();
        $inv = Invoice::where('po_id', $po_id)->get();
        $count_do_list = count($do);
        $count_sh_list = count($sh);
        $count_inv_list = count($inv);

        return view('purchase_order_details.index', compact(['purchaseorderdetail', 'statuspo', 'x', 'count_do_list', 'count_sh_list', 'count_inv_list']));
    }

    public function create_do($po_id)
    {
        $po = PurchaseOrder::where('id', $po_id)->get()->first();
        $po_detail = PurchaseOrderDetail::with('item')->where('purchase_order_id', $po_id)->get();
        return view('purchase_order_details.do', compact(['po', 'po_detail']));
    }

    public function create_submition($detail_id)
    {
        $purchaseorderdetail = PurchaseOrderDetail::where('id', $detail_id)->get()->first();
        $po = PurchaseOrder::where('id', $purchaseorderdetail->purchase_order_id)
            ->get()
            ->first();

        $item = Item::where('id', $purchaseorderdetail->item_id)
            ->get()
            ->first();

        $do = DeliveryOrder::where('referensi', $po->po_no)->get();
        $count_do = count($do);

        return view('purchase_order_details.submition', compact(['purchaseorderdetail', 'do', 'po', 'item', 'count_do']));
    }

    public function create()
    {
        return view('purchase_order_details.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pr_id' => 'required',
            'item_id' => 'required',
            'item_name' => 'required',
            'type' => 'required',
            'unit' => 'required',
            'qty' => 'required',
            'status' => 'required',
            'remark' => 'required',
            'created_by' => 'required',
        ]);
        $purchaseorderdetail = new purchaseorderdetail();
        $purchaseorderdetail->pr_id = $request->pr_no;
        $purchaseorderdetail->item_id = $request->pr_type;
        $purchaseorderdetail->item_name = $request->project_id;
        $purchaseorderdetail->type = $request->nama_project;
        $purchaseorderdetail->unit = $request->warehouse_id;
        $purchaseorderdetail->qty = $request->nama_warehouse;
        $purchaseorderdetail->status = $request->status;
        $purchaseorderdetail->remark = $request->remark;
        $purchaseorderdetail->created_by = $request->created_by;
        $purchaseorderdetail->save();
        return redirect()->route('purchase_order_details.index')->with('success', 'purchaseorderdetail has been created successfully.');
    }

    public function show($po_id)
    {
        return redirect(url('/po-details', $po_id));
    }

    public function submition(purchaseorderdetail $purchaseorderdetail)
    {
        return view('purchase_order_details.submition', compact('purchaseorderdetails'));
    }

    public function edit(purchaseorderdetail $purchaseorderdetail)
    {
        return view('purchase_order_details.edit', compact('purchaseorderdetail'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pr_id' => 'required',
            'item_id' => 'required',
            'item_name' => 'required',
            'type' => 'required',
            'unit' => 'required',
            'qty' => 'required',
            'status' => 'required',
            'remark' => 'required',
            'updated_by' => 'required',
        ]);

        $purchaseorderdetail = purchaseorderdetail::find($id);
        $purchaseorderdetail->pr_id = $request->pr_no;
        $purchaseorderdetail->item_id = $request->pr_type;
        $purchaseorderdetail->item_name = $request->project_id;
        $purchaseorderdetail->type = $request->nama_project;
        $purchaseorderdetail->unit = $request->warehouse_id;
        $purchaseorderdetail->qty = $request->nama_warehouse;
        $purchaseorderdetail->status = $request->status;
        $purchaseorderdetail->remark = $request->remark;
        $purchaseorderdetail->updated_by = $request->updated_by;
        $purchaseorderdetail->save();

        return redirect()->route('purchase_order_details.index')->with('success', 'purchaseorderdetail Has Been updated successfully');
    }

    public function destroy(purchaseorderdetail $purchaseorderdetail)
    {
        $purchaseorderdetail->delete();
        return redirect()->route('purchase_order_details.index')->with('success', 'purchaseorderdetail has been deleted successfully');
    }
}
