<?php

namespace App\Http\Controllers;

use App\Models\AprvWaitinglist;
use App\Models\PurchaseRequest;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class ApprovalWaitinglistCRUDController extends Controller
{
    //
    //
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $userId = \Auth::id();
        // $cartItems = \Cart::getContent();
        $items = \Cart::session($userId)->getContent();

        // $purchase_requests = PurchaseRequest::orderBy('id','desc')->paginate(5);
        // $status ='Approved';
        $purchase_requests = PurchaseOrder::with("warehouse","project","podetail")->where('status','Wait For Approval')->orderBy("updated_at","DESC")->get();

        return view('approvals.aprv_waitinglists.index', compact(['items','purchase_requests']));
    }
}
