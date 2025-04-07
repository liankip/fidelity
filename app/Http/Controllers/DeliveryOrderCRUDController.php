<?php

namespace App\Http\Controllers;

use App\Exports\DeliveryOrdersExport;
use App\Models\DeliveryOrder;
use Illuminate\Http\Request;
// use App\Exports\VendorsExport;
use App\Imports\DeliveryOrdersImport;
// use App\Imports\VendorsImport;
use Maatwebsite\Excel\Facades\Excel;

class DeliveryOrderCRUDController extends Controller
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
        $delivery_orders = deliveryorder::orderBy('id','desc')->paginate(5);
        return view('delivery_orders.index', compact(['items','delivery_orders']));
    }
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        return view('delivery_orders.create');
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
        'name' => 'required',
        'ground' => 'required',
        'created_by' => 'required'
    ]);
    $deliveryorder = new deliveryorder;
    $deliveryorder->name = $request->name;
    $deliveryorder->ground = $request->ground;
    $deliveryorder->created_by = $request->created_by;
    $deliveryorder->save();
    return redirect()->route('delivery_orders.index')
    ->with('success','deliveryorder has been created successfully.');
    }
    /**
    * Display the specified resource.
    *
    * @param  \App\deliveryorder  $deliveryorder
    * @return \Illuminate\Http\Response
    */
    public function show(deliveryorder $deliveryorder)
    {
    return view('delivery_orders.show',compact('deliveryorder'));
    }
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\deliveryorder  $deliveryorder
    * @return \Illuminate\Http\Response
    */
    public function edit(deliveryorder $deliveryorder)
    {
    return view('delivery_orders.edit',compact('deliveryorder'));
    }
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\deliveryorder  $deliveryorder
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
    $request->validate([
        'name' => 'required',
        'ground' => 'required',
        'updated_by' => 'required'

    ]);
    $deliveryorder = deliveryorder::find($id);
    $deliveryorder->name = $request->name;
    $deliveryorder->ground = $request->ground;
    $deliveryorder->updated_by = $request->updated_by;

    $deliveryorder->save();
    return redirect()->route('delivery_orders.index')
    ->with('success','deliveryorder Has Been updated successfully');
    }
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\deliveryorder  $deliveryorder
    * @return \Illuminate\Http\Response
    */
    public function destroy(deliveryorder $deliveryorder)
    {
    $deliveryorder->delete();
    return redirect()->route('delivery_orders.index')
    ->with('success','deliveryorder has been deleted successfully');
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function export()
    // {
    //     return Excel::download(new DeliveryOrdersExport, 'sne-masterdata-deliveryorders.xlsx');
    // }

    // /**
    // * @return \Illuminate\Support\Collection
    // */
    // public function import()
    // {
    //     Excel::import(new DeliveryOrdersImport,request()->file('file'));

    //     return back();
    // }
}
