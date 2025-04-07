<?php

namespace App\Http\Controllers;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PaymentListConcernController extends Controller
{
    //
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {

        // $cash = PurchaseOrder::all()->where('status','Approved')->where('term_of_payment','Cash');
        $non_cash = PurchaseOrder::with("project","warehouse")->where('status','Concern')->orderBy("updated_at","DESC")->get();

        return view('direksi_pages.payment_concern', compact('non_cash'));
    }
}
