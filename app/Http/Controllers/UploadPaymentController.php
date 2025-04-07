<?php

namespace App\Http\Controllers;
use App\Models\Payment;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class UploadPaymentController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($po_id)
    {
        $po = PurchaseOrder::where('id',$po_id)->get()->first();
        return view('PaymentUpload', compact('po'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // store upload payment
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5000',
        ]);

        $imageName = time().'.'.$request->image->extension();

        $request->image->move(public_path('images/invoices'), $imageName);

        $path = 'images/invoices/'.$imageName;

        $save = new Payment;

        dd($request->po_id);
        $save->po_id = $request->po_id;
        $save->payment_pict = $path;

        $save->status = $request->status;
        $save->notes = $request->notes;

        $save->save();

        return back()
            ->with('success','You have successfully upload image.')
            ->with('image',$imageName);
    }
}
