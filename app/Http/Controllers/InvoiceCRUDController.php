<?php

namespace App\Http\Controllers;

use App\Helpers\TermOfPayment\GenerateEstimate;
use App\Models\Invoice;
use App\Models\NotificationTop;
use App\Models\PurchaseOrder;
use DateTime;
use Illuminate\Http\Request;

class InvoiceCRUDController extends Controller
{
    public function index()
    {
        $userId = \Auth::id();

        $carts = \Cart::session($userId)->getContent();
        $po = PurchaseOrder::all();
        $invoices = invoice::with("purchaseorder")->whereHas("purchaseorder")->orderBy('created_at', 'desc')->paginate(15);
        return view('invoices.index', compact(['carts', 'invoices', 'po']));
    }

    public function create()
    {
        $po = PurchaseOrder::all();
        return view('invoices.create', compact('po'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'po_id' => 'required',
            'foto_invoice' => 'required',
            'creted_by' => 'required'
        ]);
        $invoice = new invoice;
        $invoice->po_id = $request->po_id;
        $invoice->penerima = $request->penerima;
        $invoice->save();

        $getpo = PurchaseOrder::where("id", $request->po_id)->first();
        if ($getpo->term_of_payment == "7 hari" || $getpo->term_of_payment == "DP 7 hari") {
            NotificationTop::where("purchase_order_id", $request->po_id)->update([
                "est_paid_off_date" => GenerateEstimate::GetEstimate7hari(new DateTime()),
                "est_pay_date" => GenerateEstimate::GetEstimate7hari(new DateTime())
            ]);
        } elseif ($getpo->term_of_payment == "30 hari" || $getpo->term_of_payment == "DP 30 hari") {
            NotificationTop::where("purchase_order_id", $request->po_id)->update([
                "est_paid_off_date" => GenerateEstimate::GetEstimate30hari(new DateTime()),
                "est_pay_date" => GenerateEstimate::GetEstimate30hari(new DateTime())
            ]);
        }


        return redirect()->route('invoices.index')
            ->with('success', 'invoice has been uploaded successfully.');
    }

    public function show(invoice $invoice)
    {
        return view('invoices.show', compact('invoice'));
    }

    public function edit(invoice $invoice)
    {
        $po = PurchaseOrder::all();
        return view('invoices.edit', compact('invoice', compact('po')));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'po_id' => 'required',
            'foto_invoice' => 'required',
            'updated_by' => 'required'

        ]);
        $invoice = invoice::find($id);
        $invoice->po_id = $request->po_id;
        $invoice->foto_invoice = $request->foto_invoice;
        $invoice->updated_by = $request->updated_by;
        $invoice->save();
        return redirect()->route('invoices.index')
            ->with('success', 'invoice Has Been updated successfully');
    }

    public function destroy(invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')
            ->with('success', 'invoice has been deleted successfully');
    }
}
