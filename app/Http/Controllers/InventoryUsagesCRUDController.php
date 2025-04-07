<?php

namespace App\Http\Controllers;
use App\Models\InventoryUsages;
use App\Models\Warehouse;
use App\Models\Project;
use App\Models\IdxInventoryUsages;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class InventoryUsagesCRUDController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $inventory_usages = InventoryUsages::with("warehousefrom","project","user")->orderBy('id','desc')->paginate(5);
        return view('inventory_usages.index', compact('inventory_usages'));
    }
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $warehouses = warehouse::all()->where('deleted_at',null);
        $projects = Project::all();
        // dd($projects);
        // ddd($warehouses);
        $idxs = IdxInventoryUsages::all();
        $year = Carbon::now()->format('Y');
        $month = Carbon::now()->format('m');
        // dd($idx);
        $map = [
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        ];


        $returnValueRoman = '';
        while ($month > 0) {
            foreach ($map as $roman => $int) {
                if ($month >= $int) {
                    $month -= $int;
                    $returnValueRoman .= $roman;
                    break;
                }
            }
        }
        return view('inventory_usages.create',
        compact([
        'warehouses',
        'projects',
        'year',
        'returnValueRoman',
        'idxs'])
    );
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
            'iu_no' => 'required',
            'from' => 'required',
            'to' => 'required',
        ]);
        $InventoryUsages = new InventoryUsages;
        $InventoryUsages->iu_no = $request->iu_no;
        $InventoryUsages->from = $request->from;
        $InventoryUsages->to = $request->to;
        $InventoryUsages->notes = $request->notes;
        $InventoryUsages->created_by = $request->created_by;
        $InventoryUsages->status = 'New';
        $InventoryUsages->save();
        return redirect()->route('inventory_usages.index')
        ->with('success','InventoryUsages has been created successfully.');
    }
    /**
    * Display the specified resource.
    *
    * @param  \App\InventoryUsages  $InventoryUsages
    * @return \Illuminate\Http\Response
    */
    public function show(InventoryUsages $InventoryUsages)
    {
    return view('inventory_usages.show',compact('InventoryUsages'));
    }
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\InventoryUsages  $InventoryUsages
    * @return \Illuminate\Http\Response
    */
    public function edit(InventoryUsages $InventoryUsages)
    {
    return view('inventory_usages.edit',compact('InventoryUsages'));
    }
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\InventoryUsages  $InventoryUsages
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
    $request->validate([
        'InventoryUsages_code' => 'required',
        'name' => 'required',
        'type' => 'required',
        'unit' => 'required',
        'updated_by' => 'required'


    ]);
    $InventoryUsages = InventoryUsages::find($id);
    $InventoryUsages->InventoryUsages_code = $request->InventoryUsages_code;
    $InventoryUsages->name = $request->name;
    $InventoryUsages->type = $request->type;
    $InventoryUsages->unit = $request->unit;
    $InventoryUsages->updated_by = $request->updated_by;
    $InventoryUsages->save();
    return redirect()->route('inventory_usages.index')
    ->with('success','InventoryUsages Has Been updated successfully');
    }
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\InventoryUsages  $InventoryUsages
    * @return \Illuminate\Http\Response
    */
    public function destroy(InventoryUsages $InventoryUsages)
    {
    $InventoryUsages->delete();
    return redirect()->route('inventory_usages.index')
    ->with('success','InventoryUsages has been deleted successfully');
    }
}
