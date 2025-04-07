<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;


class ApprovalHistoryCRUDController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect(route('approval-histories'));
        $purchase_requests = PurchaseOrder::with("project","warehouse")->where("status", "Approved")->orWhere("status", "Rejected")->orderBy("updated_at","DESC")->get();
        return view('approvals.aprv_histories.index', compact('purchase_requests'));
    }
}
