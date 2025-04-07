<?php

namespace App\Http\Controllers;

use App\Exports\PaymentMetodesExport;
use App\Models\PaymentMetode;
use Illuminate\Http\Request;
use App\Exports\VendorsExport;
use App\Imports\PaymentMetodesImport;
use App\Imports\VendorsImport;
use Maatwebsite\Excel\Facades\Excel;

class PaymentMetodeCRUDController extends Controller
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
        $paymentmetodes = paymentmetode::orderBy('id','desc')->paginate(5);
        return view('masterdata.paymentmetodes.index',compact(['items', 'paymentmetodes']));
    }
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
    return view('masterdata.paymentmetodes.create');
    }
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $request->validate([

        'metode' => 'required',
        'created_by' => 'required'

    ]);
    $paymentmetode = new paymentmetode;
    $paymentmetode->metode = $request->metode;
    $paymentmetode->term_of_payment = $request->term_of_payment;
    $paymentmetode->created_by = $request->created_by;
    $paymentmetode->save();
    return redirect()->route('paymentmetodes.index')
    ->with('success','paymentmetode has been created successfully.');
    }
    /**
    * Display the specified resource.
    *
    * @param  \App\paymentmetode  $paymentmetode
    * @return \Illuminate\Http\Response
    */
    public function show(paymentmetode $paymentmetode)
    {
    return view('masterdata.paymentmetodes.show',compact('paymentmetode'));
    }
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\paymentmetode  $paymentmetode
    * @return \Illuminate\Http\Response
    */
    public function edit(paymentmetode $paymentmetode)
    {
    return view('masterdata.paymentmetodes.edit',compact('paymentmetode'));
    }
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\paymentmetode  $paymentmetode
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
    $request->validate([

        'metode' => 'required',
        'updated_by' => 'required'



    ]);
    $paymentmetode = paymentmetode::find($id);
    $paymentmetode->metode = $request->metode;
    $paymentmetode->term_of_payment = $request->term_of_payment;
    $paymentmetode->updated_by = $request->updated_by;

    $paymentmetode->save();
    return redirect()->route('paymentmetodes.index')
    ->with('success','paymentmetode Has Been updated successfully');
    }
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\paymentmetode  $paymentmetode
    * @return \Illuminate\Http\Response
    */
    public function destroy(paymentmetode $paymentmetode)
    {
    $paymentmetode->delete();
    return redirect()->route('paymentmetodes.index')
    ->with('success','paymentmetode has been deleted successfully');
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function export()
    {
        return Excel::download(new PaymentMetodesExport, 'sne-masterdata-paymentmetodes.xlsx');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function import()
    {
        Excel::import(new PaymentMetodesImport,request()->file('file'));

        return back();
    }
}
