<?php

namespace App\Http\Controllers;
use App\Models\Payment;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PaidController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $po = PurchaseOrder::all();
        return view('PaymentUpload', compact('po'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = time().'.'.$request->image->extension();

        dd($imageName);

        $request->image->move(public_path('images/payment'), $imageName);

        $path = 'images/payment/'.$imageName;

        $save = new Payment;


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
