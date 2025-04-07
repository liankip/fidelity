<?php

namespace App\Http\Controllers;
use App\Models\GudangTransfer;
use App\Models\Warehouse;
use App\Models\IdxGudangTransfer;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class GudangTransferCRUDController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $gudang_transfers = GudangTransfer::with("warehousefrom","warehouseto","user")->orderBy('id','desc')->paginate(5);
        return view('gudang_transfers.index', compact('gudang_transfers'));
    }
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $warehouses = warehouse::all()->where('deleted_at',null);
        // dd($projects);
        // ddd($warehouses);
        $idxs = IdxGudangTransfer::all();
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
        return view('gudang_transfers.create',
        compact([
        'warehouses',
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
        // dd($request->from);
        $request->validate([
        'gt_no' => 'required',
        'from' => 'required',
        'to' => 'required',

        ]);
        $gudangtransfer = new GudangTransfer();
        $gudangtransfer->gt_no = $request->gt_no;
        $gudangtransfer->from = $request->from;
        $gudangtransfer->to = $request->to;
        $gudangtransfer->notes = $request->notes;
        $gudangtransfer->created_by = $request->created_by;
        $gudangtransfer->status = 'New';
        $gudangtransfer->save();
        // dd($request->from);



        $idx_pr = IdxGudangTransfer::find(1);
        $idx_pr->idx = $request->idx_next;
        $idx_pr->save();


        return redirect()->route('gudang_transfers.index')
        ->with('success','Gudang Transfer Destination has been created successfully.');
    }
    /**
    * Display the specified resource.
    *
    * @param  \App\gudangtransfer  $gudangtransfer
    * @return \Illuminate\Http\Response
    */
    public function show(gudangtransfer $gudangtransfer)
    {
    return view('gudang_transfers.show',compact('gudangtransfer'));
    }
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\gudangtransfer  $gudangtransfer
    * @return \Illuminate\Http\Response
    */
    public function edit(gudangtransfer $gudangtransfer)
    {
    return view('gudang_transfers.edit',compact('gudangtransfer'));
    }
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\gudangtransfer  $gudangtransfer
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
    $request->validate([
        'gudangtransfer_code' => 'required',
        'name' => 'required',
        'type' => 'required',
        'unit' => 'required',
        'updated_by' => 'required'


    ]);
    $gudangtransfer = gudangtransfer::find($id);
    $gudangtransfer->gudangtransfer_code = $request->gudangtransfer_code;
    $gudangtransfer->name = $request->name;
    $gudangtransfer->type = $request->type;
    $gudangtransfer->unit = $request->unit;
    $gudangtransfer->updated_by = $request->updated_by;
    $gudangtransfer->save();
    return redirect()->route('gudang_transfers.index')
    ->with('success','gudangtransfer Has Been updated successfully');
    }
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\gudangtransfer  $gudangtransfer
    * @return \Illuminate\Http\Response
    */
    public function destroy(gudangtransfer $gudangtransfer)
    {
    $gudangtransfer->delete();
    return redirect()->route('gudang_transfers.index')
    ->with('success','gudangtransfer has been deleted successfully');
    }
}
