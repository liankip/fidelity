<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PaymentListNonCashController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        return redirect()->route("payment-list-noncash");
        // $cash = PurchaseOrder::all()->where('status','Approved')->where('term_of_payment','Cash');
        $non_cash = PurchaseOrder::with("project","warehouse")->where('status','Waiting For Payment')->orderBy("updated_at","DESC")->get();
        return view('payment_list_noncash.index', compact('non_cash'));
    }
}
