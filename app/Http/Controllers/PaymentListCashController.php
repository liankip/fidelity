<?php

namespace App\Http\Controllers;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PaymentListCashController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        return redirect()->route("payment-list-cash");
        $cash = PurchaseOrder::with("project","warehouse")->where('status','Approved')->where('term_of_payment','Cash')->orderBy("date_approved")->get();
        // $non_cash = PurchaseOrder::all()->where('status','Arrived');
        return view('payment_list_cash.index', compact('cash'));
    }
}
