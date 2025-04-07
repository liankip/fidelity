<?php

namespace App\Http\Controllers;
use App\Models\Retur;
use Illuminate\Http\Request;

class ReturController extends Controller
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
        $returs = retur::orderBy('id','desc')->paginate(5);
        return view('returs.index', compact(['items', 'returs']));
    }
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
    return view('returs.create');
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
        'retur_code' => 'required',
        'name' => 'required',
        'type' => 'required',
        'unit' => 'required',
        'created_by' => 'required'
    ]);
    $retur = new retur;
    $retur->retur_code = $request->retur_code;
    $retur->name = $request->name;
    $retur->type = $request->type;
    $retur->unit = $request->unit;
    $retur->created_by = $request->created_by;
    $retur->save();
    return redirect()->route('returs.index')
    ->with('success','retur has been created successfully.');
    }
    /**
    * Display the specified resource.
    *
    * @param  \App\retur  $retur
    * @return \Illuminate\Http\Response
    */
    public function show(retur $retur)
    {
    return view('returs.show',compact('retur'));
    }
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\retur  $retur
    * @return \Illuminate\Http\Response
    */
    public function edit(retur $retur)
    {
    return view('returs.edit',compact('retur'));
    }
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\retur  $retur
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
    $request->validate([
        'retur_code' => 'required',
        'name' => 'required',
        'type' => 'required',
        'unit' => 'required',
        'updated_by' => 'required'


    ]);
    $retur = retur::find($id);
    $retur->retur_code = $request->retur_code;
    $retur->name = $request->name;
    $retur->type = $request->type;
    $retur->unit = $request->unit;
    $retur->updated_by = $request->updated_by;
    $retur->save();
    return redirect()->route('returs.index')
    ->with('success','retur Has Been updated successfully');
    }
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\retur  $retur
    * @return \Illuminate\Http\Response
    */
    public function destroy(retur $retur)
    {
    $retur->delete();
    return redirect()->route('returs.index')
    ->with('success','retur has been deleted successfully');
    }
}
