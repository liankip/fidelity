<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PurchaseOrder;
use App\Models\User;
use App\Notifications\PurchaseOrderPaid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class PaymentCRUDController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = \Auth::id();
        // $cartItems = \Cart::getContent();
        $items = \Cart::session($userId)->getContent();
        $payments = payment::with("purchaseorder")->orderBy('id', 'desc')->paginate(8);
        return view('payments.index', compact(['items', 'payments']));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('payments.create');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd("eleh");
        $request->validate([
            'payment_pict' => 'required',
            'po_id' => 'required',
            'status' => 'required',
            'notes' => 'required',
            'created_by' => 'required'
        ]);


        $payment = new payment;
        $payment->payment_pict = $request->payment_pict;
        $payment->po_id = $request->po_id;
        $payment->status = $request->status;
        $payment->notes = $request->notes;
        $payment->created_by = $request->created_by;
        $payment->save();

        $po = PurchaseOrder::where("id", $request->po_id);

        $purches = User::where("type", 3)->orWhere("type", 4)->orWhere("type",5)->get();

        foreach ($purches as $key => $pur) {
            $podata = [
                'po_no' => $po->po_no,
                'po_detail' => $po->id,
                'status' => $request->status,
                'created_by' => Auth::user()->name,

            ];
            // dd($podata);

            Notification::send($pur, new PurchaseOrderPaid($podata));
        }



        return redirect()->route('payments.index')
            ->with('success', 'payment has been created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(payment $payment)
    {
        return view('payments.show', compact('payment'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(payment $payment)
    {
        return view('payments.edit', compact('payment'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'payment_pict' => 'required',
            'po_id' => 'required',
            'status' => 'required',
            'notes' => 'required',
            'updated_by' => 'required'


        ]);
        $payment = payment::find($id);
        $payment->payment_pict = $request->payment_pict;
        $payment->po_id = $request->po_id;
        $payment->status = $request->status;
        $payment->notes = $request->notes;
        $payment->updated_by = $request->updated_by;
        $payment->save();
        return redirect()->route('payments.index')
            ->with('success', 'payment Has Been updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')
            ->with('success', 'payment has been deleted successfully');
    }
}
