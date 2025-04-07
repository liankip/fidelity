<?php

namespace App\Http\Controllers;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use Illuminate\Http\Request;

class ArrivedController extends Controller
{
    public function arrivedpo(Request $request, $id)
    {
        PurchaseOrder::where('id', $request->id)->update(['status' => 'Arrived']);
        PurchaseOrderDetail::where('purchase_order_id', $request->id)->update(['percent_complete' => 100]);
        return redirect()->back();
    }
}
