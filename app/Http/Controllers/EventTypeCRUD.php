<?php

namespace App\Http\Controllers;

use App\Exports\EventTypesExport;
use App\Models\EventType;
use Illuminate\Http\Request;
use App\Exports\VendorsExport;
use App\Imports\EventTypesImport;
use App\Imports\VendorsImport;
use Maatwebsite\Excel\Facades\Excel;

class EventTypeCRUD extends Controller
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
        $event_types = EventType::orderBy('id','desc')->paginate(5);
        return view('masterdata.event_types.index', compact(['items','event_types']));
    }
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        return view('masterdata.event_types.create');
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
        'type' => 'required',
        'remark' => 'required',
        'created_by' => 'required'
    ]);
    $EventType = new EventType;
    $EventType->type = $request->type;
    $EventType->remark = $request->remark;
    $EventType->created_by = $request->created_by;
    $EventType->save();
    return redirect()->route('event_types.index')
    ->with('success','EventType has been created successfully.');
    }
    /**
    * Display the specified resource.
    *
    * @param  \App\EventType  $EventType
    * @return \Illuminate\Http\Response
    */
    public function show(EventType $EventType)
    {
    return view('masterdata.event_types.show',compact('EventType'));
    }
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\EventType  $EventType
    * @return \Illuminate\Http\Response
    */
    public function edit(EventType $EventType)
    {
        return view('masterdata.event_types.edit',compact('EventType'));
    }
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\EventType  $EventType
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
    $request->validate([
        'type' => 'required',
        'remark' => 'required',
        'updated_by' => 'required'

    ]);
    $EventType = EventType::find($id);
    $EventType->type = $request->type;
    $EventType->remark = $request->remark;
    $EventType->updated_by = $request->updated_by;

    $EventType->save();
    return redirect()->route('event_types.index')
    ->with('success','EventType Has Been updated successfully');
    }
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\EventType  $EventType
    * @return \Illuminate\Http\Response
    */
    public function destroy(EventType $EventType)
    {
        $EventType->delete();
        return redirect()->route('event_types.index')
        ->with('success','EventType has been deleted successfully');
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function export()
    {
        return Excel::download(new EventTypesExport, 'sne-masterdata-eventtypes.xlsx');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function import()
    {
        Excel::import(new EventTypesImport,request()->file('file'));

        return back();
    }
}
