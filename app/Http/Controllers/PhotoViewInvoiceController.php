<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\PurchaseOrder;
use App\Roles\Role;

class PhotoViewInvoiceController extends Controller
{
    //
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewphoto_inv($po_id)
    {
        if (!auth()->user()->hasTopLevelAccess() && !auth()->user()->hasRole(Role::FINANCE) && !auth()->user()->hasRole(Role::PAYABLE) && !auth()->user()->hasRole(Role::PURCHASING)) {
            abort(403);
        }
        $po = PurchaseOrder::where('id', $po_id)->get()->first();
        // dd($po);
        // $po_no = $po->po_no;
        // $invoices = invoice::with("purchaseorder")->orderBy('created_at','desc')->paginate(10);
        $invoices = Invoice::with("purchaseorder")->where('po_id', $po->id)->orderBy('id', 'desc')->paginate(10);

        return view('photo_view.photo_invoice', compact(['invoices', 'po']));
    }
}
