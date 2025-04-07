<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Project;
use App\Models\MemoList;
use Illuminate\Http\Request;
use App\Models\CompanyDetail;
use App\Models\PurchaseOrder;
use App\Models\DeliveryService;
use App\Models\PurchaseRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseRequestDetail;

class PrintPoController extends Controller
{
    //
    public function print(Request $request, $id)
    {
        $po_data = PurchaseOrder::where('id', $id)->first();
        // dd($po_data);
        $po_detail = PurchaseOrderDetail::where('purchase_order_id', $id)->get();
        // dd($po_detail);
        // $getproject_id = PurchaseOrder::where('id',$id)->first();
        $total_amount = PurchaseOrderDetail::where('purchase_order_id', $id)->sum('amount');

        $taxi = PurchaseOrderDetail::where('purchase_order_id', $id)->first();
        if ($taxi->tax_status != 2) {

            $total_tax = 11;
        } else {
            $total_tax = 0;
        }

        $getproject_name = Project::where('id', $po_data->project_id)->first();
        $get_prtype = PurchaseRequest::where('pr_no', $po_data->pr_no)->first();
        $our_company = CompanyDetail::first();

        // $date = Carbon::now()->format('d-M-y');
        // $newDate = date('F d, Y', strtotime($date));
        // if ($po_data->date_approved) {
        //     $newDate = date_format(date_create($po_data->date_approved), 'F d, Y');
        // } else {
        //     $newDate = date_format(date_create($po_data->created_at), 'F d, Y');
        // }
        if ($po_data->approved_at) {
            $newDate = date_format(date_create($po_data->approved_at), 'F d, Y');
        } elseif ($po_data->date_approved_2) {
            $newDate = date_format(date_create($po_data->date_approved_2), 'F d, Y');
        } elseif ($po_data->date_approved) {
            $newDate = date_format(date_create($po_data->date_approved), 'F d, Y');
        } else {
            $newDate = date_format(date_create($po_data->cretated_at), 'F d, Y');
        }

        $notes = $po_data->notes ? json_decode($po_data->notes) : [];
        // Get all user IDs from the notes
        $userIds = collect($notes)->pluck('user_id')->unique();

        // Fetch user names for these IDs
        $users = User::whereIn('id', $userIds)->pluck('name', 'id'); // Keyed by user_id

        return view('documents.po', compact([
            'po_data',
            'po_detail',
            'total_amount',
            'total_tax',
            'getproject_name',
            'newDate',
            'get_prtype',
            'our_company',
            'users'
        ]));
    }


    public function print_po_ds(Request $request, $id)
    {
        $po_data = PurchaseOrder::where('id', $id)->first();
        $po = PurchaseOrder::where('id', $id)->get()->first();
        $ds = DeliveryService::where('id', $po->ds_id)->get()->first();
        $po_detail = PurchaseOrderDetail::where('purchase_order_id', $id)->get();
        $getproject_id = PurchaseOrder::where('id', $id)->first();
        $total_amount = PurchaseOrderDetail::where('purchase_order_id', $id)->sum('amount');
        $taxi = PurchaseOrderDetail::where('purchase_order_id', $id)->first();

        if ($taxi->tax_status != 2) {
            $total_tax = 11;
        } else {
            $total_tax = 0;
        }
        // $total_tax = PurchaseOrderDetail::where('purchase_order_id',$id)->avg('tax');

        $getproject_name = Project::where('id', $getproject_id->project_id)->first();
        $get_prtype = PurchaseRequest::where('pr_no', $getproject_id->pr_no)->first();
        $our_company = CompanyDetail::get()->first();
        // dd($our_company->name);

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

        $notes = $po_data->notes ? json_decode($po_data->notes) : [];
        // Get all user IDs from the notes
        $userIds = collect($notes)->pluck('user_id')->unique();

        // Fetch user names for these IDs
        $users = User::whereIn('id', $userIds)->pluck('name', 'id'); // Keyed by user_id

        return view('documents.po_ds', compact([
            'po_data',
            'po_detail',
            'total_amount',
            'total_tax',
            'getproject_name',
            'newDate',
            'get_prtype',
            'ds',
            'po',
            'our_company',
            'users'
        ]));
    }

    public function print_memo(Request $request, $id)
    {
        $company_detail = CompanyDetail::all();

        $po_data = PurchaseOrder::all()->where('id', $id);
        // dd($po_data);
        $po_detail = PurchaseOrderDetail::where('purchase_order_id', $id)->get();
        // dd($po_detail);
        $getproject_id = PurchaseOrder::where('id', $id)->first();
        $total_amount = PurchaseOrderDetail::where('purchase_order_id', $id)->sum('amount');
        $total_tax = PurchaseOrderDetail::where('purchase_order_id', $id)->avg('tax');
        $getproject_name = Project::where('id', $getproject_id->project_id)->first();
        $get_prtype = PurchaseRequest::where('pr_no', $getproject_id->pr_no)->first();
        $purchase_orders = purchaseorder::with("supplier")->get();
        $drivermemo = MemoList::where('po_id', $id)->get()->first();
        $our_company = CompanyDetail::get()->first();



        // $date = Carbon::now()->format('d-M-y');
        if ($getproject_id->date_approved) {
            # code...
            $newDate = date_format(date_create($getproject_id->date_approved), 'F d, Y');
        } else {
            $newDate = date_format(date_create($getproject_id->created_at), 'F d, Y');
        }



        return view('documents.memo', compact([
            'company_detail',
            'drivermemo',
            'po_data',
            'po_detail',
            'total_amount',
            'total_tax',
            'getproject_name',
            'newDate',
            'get_prtype',
            'our_company'
        ]));
    }
}
