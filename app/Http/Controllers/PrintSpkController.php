<?php

namespace App\Http\Controllers;

use App\Models\CompanyDetail;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\Project;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PrintSpkController extends Controller
{
    //
    public function print(Request $request, $id)
    {

        $po_data = PurchaseOrder::all()->where('id', $id);
        // dd($po_data);
        $po_detail = PurchaseOrderDetail::all()->where('purchase_order_id', $id);
        $getproject_id = PurchaseOrder::where('id', $id)->first();
        $total_amount = PurchaseOrderDetail::where('purchase_order_id', $id)->sum('amount');
        $total_tax = PurchaseOrderDetail::where('purchase_order_id', $id)->avg('tax');
        $getproject_name = Project::where('id', $getproject_id->project_id)->first();

        // $date = Carbon::now()->format('d-M-y');
        // if ($getproject_id->date_approved) {
        //     $newDate = date_format(date_create($getproject_id->date_approved), 'F d, Y');
        // } else {
        //     $newDate = date_format(date_create($getproject_id->created_at), 'F d, Y');
        // }
        if ($getproject_id->approved_at) {
            $newDate = date_format(date_create($getproject_id->approved_at), 'F d, Y');
        } elseif ($getproject_id->date_approved_2) {
            $newDate = date_format(date_create($getproject_id->date_approved_2), 'F d, Y');
        } elseif ($getproject_id->date_approved) {
            $newDate = date_format(date_create($getproject_id->date_approved), 'F d, Y');
        } else {
            $newDate = date_format(date_create($getproject_id->cretated_at), 'F d, Y');
        }
        return view('documents.spk', compact([ 'po_data', 'po_detail', 'total_amount', 'total_tax', 'getproject_name', 'newDate']));
    }
}
