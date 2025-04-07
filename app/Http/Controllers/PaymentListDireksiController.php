<?php

namespace App\Http\Controllers;
use App\Models\Payment;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PaymentListDireksiController extends Controller
{
    //
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        return redirect()->route("payment-list");
        // $cash = PurchaseOrder::all()->where('status','Approved')->where('term_of_payment','Cash');
        $non_cash = PurchaseOrder::with("project","warehouse","podetail")->where('status','Need To Pay')->orWhere('status','Paid Particially')->orderBy("updated_at","DESC")->get();
        // $history_note = Payment::where('status','part')->get();
        return view('direksi_pages.payment_list', compact('non_cash'));
    }
}
