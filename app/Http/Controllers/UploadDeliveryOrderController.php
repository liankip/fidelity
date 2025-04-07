<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeliveryOrder;

class UploadDeliveryOrderController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $do = DeliveryOrder::all();
        return view('delivery_orders.index', compact('do'));
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

        $request->image->move(public_path('images/do'), $imageName);

        $path = 'images/do/'.$imageName;

        $save = new DeliveryOrder();

        $save->do_no = $request->do_no;
        $save->do_type = $request->do_type;
        $save->do_pict = $path;
        $save->referensi = $request->referensi;

        $save->save();

        // return redirect()->route('cart.list')->with('success', 'Item is Added to PR Successfully !');
        return redirect()->route('purchase-orders')
            ->with('success','Surat Jalan Berhasil di Tambahkan.')
            ->with('image',$imageName);
            // return back()
            // ->with('success','You have successfully upload image.')
            // ->with('image',$imageName);
    }
}
