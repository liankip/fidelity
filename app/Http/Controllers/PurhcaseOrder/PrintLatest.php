<?php

namespace App\Http\Controllers\PurhcaseOrder;

use App\Http\Controllers\Controller;
use App\Models\CompanyDetail;
use App\Models\PurchaseOrder;
use App\Models\Voucher;
use App\Permissions\Permission;
use Illuminate\Http\Request;

class PrintLatest extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->can(Permission::PRINT_LATEST_PO)) {
            return abort("403");
        }
        $limit = 10;
        if ($request->limit) {
            $limit = $request->limit;
        }
        $po_data = PurchaseOrder::where("po_no", "!=", null)->whereNotIn("status", ["Rejected", "Cencel", "Wait For Approval"])->orderBy("date_approved", "DESC")->take($limit)->get();
        $our_company = CompanyDetail::first();
        return view(
            'purchase_orders.print_latest',
            [
                "po_data" => $po_data,
                "our_company" => $our_company
            ]
        );
    }

    public function voucher($date)
    {
        $vouchers = Voucher::with(["voucher_details", 'voucher_details.project', 'voucher_details.purchase_order.submition', 'voucher_details.purchase_order.podetail', 'voucher_details.purchase_order.pr', 'voucher_details.supplier'])->whereDate("created_at", $date)->get();
        return view('purchase_orders.print_vouchers', compact('vouchers', 'date'));
    }
}
