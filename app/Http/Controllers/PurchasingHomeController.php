<?php

namespace App\Http\Controllers;
use App\Models\IdxPurchaseRequest;
use App\Models\IdxPurchaseOrder;
use Illuminate\Http\Request;

class PurchasingHomeController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $totalpr = IdxPurchaseRequest::all();
        $totalpo = IdxPurchaseOrder::all();
        return view('PurchasingHome', compact(['IdxPurchaseRequest', 'IdxPurchaseOrder']));
    }
}
