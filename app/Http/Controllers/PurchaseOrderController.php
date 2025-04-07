<?php

namespace App\Http\Controllers;

use App\Models\DeliveryService;
use App\Models\PaymentMetode;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// use App\Models\price;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        return redirect(url('/purchase-orders'));
        if ($request->search) {
            $searchcompact = $request->search;
            $purchase_orders = purchaseorder::with('supplier', 'warehouse', 'project', 'podetail')
                ->where('po_no', 'like', '%' . $request->search . '%')
                ->orWhere('pr_no', 'like', '%' . $request->search . '%')
                ->orWhere('status', 'like', '%' . $request->search . '%')
                ->orWhere('term_of_payment', 'like', '%' . $request->search . '%')
                ->orWhereHas('project', function ($query) use ($searchcompact) {
                    $query->where('name', 'like', '%' . $searchcompact . '%');
                })
                ->orWhereHas('warehouse', function ($query) use ($searchcompact) {
                    $query->where('name', 'like', '%' . $searchcompact . '%');
                })
                ->orWhereHas('supplier', function ($query) use ($searchcompact) {
                    $query->where('name', 'like', '%' . $searchcompact . '%');
                })
                ->orderBy('id', 'desc')
                ->paginate(8);
            $purchase_orders->appends(['search' => $request->search]);
        } else {
            $searchcompact = '';
            $purchase_orders = purchaseorder::with('supplier', 'warehouse', 'project', 'podetail')->orderBy('id', 'desc')->paginate(8);
        }
        // dd($request->search);
        $userId = \Auth::id();
        // $cartItems = \Cart::getContent();
        $items = \Cart::session($userId)->getContent();
        $ds = DeliveryService::all();
        return view('purchase_orders.index', compact(['items', 'purchase_orders', 'searchcompact', 'ds']));
    }

    public function create_inv($po_id)
    {
        $po = PurchaseOrder::where('id', $po_id)->get()->first();
        return view('purchase_orders.invoice', compact('po'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('purchase_orders.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        echo 'test';
        $query = purchaserequestdetail::where('pr_id', 7)->get();

        foreach ($query as $test) {
            $data = [
                'item_id' => $test->item_id,
                'item_name' => $test->item_name,
                'type' => $test->type,
                'unit' => $test->unit,
                'qty' => $test->qty,
                'notes' => $test->notes,
                'price_id' => $request->price_id,
                'price' => $request->price,
                'tax' => $request->tax,
                'amount' => $request->amount,
            ];
        }
        dd($data);
        // DB::table('testingarray')->insert($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\purchaseorder  $purchaseorder
     * @return \Illuminate\Http\Response
     */
    public function show($pr_id)
    {
        $this->authorize('show', PurchaseOrder::class);

        $purchaserequestdetail = purchaserequestdetail::where('pr_id', $pr_id)->get();
        $statuspr = PurchaseRequest::all()->where('id', $pr_id);
        $brand_partner = DB::table('prices')->orderBy('price', 'ASC')->get();
        $payment_method = PaymentMetode::all();

        return view('purchase_orders.create', compact(['purchaserequestdetail', 'statuspr', 'brand_partner']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\purchaseorder  $purchaseorder
     * @return \Illuminate\Http\Response
     */
    public function edit(purchaseorder $purchaseorder)
    {
        return view('purchase_orders.edit', compact('purchaseorder'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\purchaseorder  $purchaseorder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'po_no' => 'required',
            'pr_id' => 'required',
            'payment_metode_id' => 'required',
            'project_id' => 'required',
            'warehouse_id' => 'required',
            'price_id' => 'required',
            'company_id' => 'required',
            'date_request' => 'required',
            'do_id' => 'required',
            'remark' => 'required',
            'status' => 'required',
            'updated_by' => 'required',
        ]);

        $purchaseorder = purchaseorder::find($id);
        $purchaseorder->po_no = $request->po_no;
        $purchaseorder->pr_id = $request->pr_id;
        $purchaseorder->payment_metode_id = $request->payment_metode_id;
        $purchaseorder->project_id = $request->project_id;
        $purchaseorder->warehouse_id = $request->warehouse_id;
        $purchaseorder->price_id = $request->price_id;
        $purchaseorder->company_id = $request->company_id;
        $purchaseorder->date_request = $request->date_request;
        $purchaseorder->do_id = $request->do_id;
        $purchaseorder->remark = $request->remark;
        $purchaseorder->status = $request->status;
        $purchaseorder->updated_by = $request->updated_by;
        $purchaseorder->save();

        return redirect()->route('purchase-orders')->with('success', 'purchaseorder Has Been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\purchaseorder  $purchaseorder
     * @return \Illuminate\Http\Response
     */
    public function destroy(purchaseorder $purchaseorder)
    {
        $purchaseorder->delete();
        return redirect()->route('purchase-orders')->with('success', 'purchaseorder has been deleted successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\purchaseorder  $purchaseorder
     * @return \Illuminate\Http\Response
     */
    public function cancel(Request $request, $id)
    {
        $userId = \Auth::id();
        $request->validate([
            'status' => 'required',
        ]);
        $purchaseorder = purchaseorder::find($id);
        $purchaseorder->status = 'Cancel';
        $purchaseorder->updated_by = $userId;
        $purchaseorder->save();
        return redirect()->route('purchase-orders')->with('success', 'Purchase Order Has Been Canceled Successfully');
    }
}
