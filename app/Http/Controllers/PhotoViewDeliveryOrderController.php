<?php

namespace App\Http\Controllers;
use App\Models\PurchaseOrder;
use App\Models\DeliveryOrder;
use Illuminate\Http\Request;

class PhotoViewDeliveryOrderController extends Controller
{
    //
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function viewphoto_do($po_id)
    {
        $po = PurchaseOrder::where('id',$po_id)->get()->first();
        // dd($po);
        // $po_no = $po->po_no;
        $delivery_orders = deliveryorder::where('referensi',$po->po_no)->orderBy('id','desc')->paginate(10);

        return view('photo_view.photo_delivery_order', compact(['delivery_orders','po']));
    }
}
