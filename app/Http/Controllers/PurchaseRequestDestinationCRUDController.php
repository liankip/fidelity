<?php

namespace App\Http\Controllers;
use App\Models\PurchaseRequest;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Warehouse;
use App\Models\PurchaseRequestDetail;
use App\Models\IdxPurchaseRequest;
use Carbon\Carbon;

class PurchaseRequestDestinationCRUDController extends Controller
{
    //
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $userId = \Auth::id();
        // $cartpurchase_request_destinantions = \Cart::getContent();
        $carts = \Cart::session($userId)->getContent();
        $purchase_request_destinantions = purchaserequest::orderBy('id','desc')->paginate(5);
        return view('purchase_request_destinantions.index', compact(['carts', 'purchase_request_destinantions']));
    }
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        return view('purchase_request_destinantions.create');
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
            'purchaserequest_code' => 'required',
            'name' => 'required',
            'type' => 'required',
            'unit' => 'required',
            'creted_by' => 'required'
        ]);
        $purchaserequest = new purchaserequest;
        $purchaserequest->purchaserequest_code = $request->purchaserequest_code;
        $purchaserequest->name = $request->name;
        $purchaserequest->type = $request->type;
        $purchaserequest->unit = $request->unit;
        $purchaserequest->created_by = $request->created_by;
        $purchaserequest->save();
        return redirect()->route('purchase_request_destinantions.index')
        ->with('success','purchaserequest has been created successfully.');
    }
    /**
    * Display the specified resource.
    *
    * @param  \App\purchaserequest  $purchaserequest
    * @return \Illuminate\Http\Response
    */
    public function show(purchaserequest $purchaserequest)
    {
        return view('purchase_request_destinantions.show',compact('purchaserequest'));
    }
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\purchaserequest  $purchaserequest
    * @return \Illuminate\Http\Response
    */
    public function edit(purchaserequest $purchaserequest)
    {
        return view('purchase_request_destinantions.edit',compact('purchaserequest'));
    }
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\purchaserequest  $purchaserequest
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
    $request->validate([
        'purchaserequest_code' => 'required',
        'name' => 'required',
        'type' => 'required',
        'unit' => 'required',
        'updated_by' => 'required'

    ]);
    $purchaserequest = purchaserequest::find($id);
    $purchaserequest->purchaserequest_code = $request->purchaserequest_code;
    $purchaserequest->name = $request->name;
    $purchaserequest->type = $request->type;
    $purchaserequest->unit = $request->unit;
    $purchaserequest->updated_by = $request->updated_by;
    $purchaserequest->save();
    return redirect()->route('purchase_request_destinantions.index')
    ->with('success','purchaserequest Has Been updated successfully');
    }
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\purchaserequest  $purchaserequest
    * @return \Illuminate\Http\Response
    */
    public function destroy(purchaserequest $purchaserequest)
    {
        $purchaserequest->delete();
        return redirect()->route('purchase_request_destinantions.index')
        ->with('success','purchaserequest has been deleted successfully');
    }

}
