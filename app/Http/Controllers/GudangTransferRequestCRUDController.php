<?php

namespace App\Http\Controllers;
use App\Models\GudangTransferRequest;
use App\Models\Warehouse;
use App\Models\IdxGudangTransferRequest;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class GudangTransferRequestCRUDController extends Controller
{
    //
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $gudang_transfers = GudangTransferRequest::with("warehousefrom","warehouseto","user")->orderBy('id','desc')->paginate(5);
        return view('gudang_transfer_requests.index', compact('gudang_transfers'));
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
        $idxs = IdxGudangTransferRequest::all();
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
        return view('gudang_transfer_requests.create',
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
            $gudangtransferrequest = new GudangTransferRequest();
            $gudangtransferrequest->gt_no = $request->gt_no;
            $gudangtransferrequest->from = $request->from;
            $gudangtransferrequest->to = $request->to;
            $gudangtransferrequest->notes = $request->notes;
            $gudangtransferrequest->created_by = $request->created_by;
            $gudangtransferrequest->status = 'New';
            $gudangtransferrequest->save();
            // dd($request->from);



            $idx_pr = IdxGudangTransferRequest::find(1);
            $idx_pr->idx = $request->idx_next;
            $idx_pr->save();
            return redirect()->route('gudang_transfer_requests.index')
            ->with('success','GudangTransferRequest has been created successfully.');
    }
    /**
    * Display the specified resource.
    *
    * @param  \App\GudangTransferRequest  $GudangTransferRequest
    * @return \Illuminate\Http\Response
    */
    public function show(GudangTransferRequest $GudangTransferRequest)
    {
    return view('gudang_transfer_requests.show',compact('GudangTransferRequest'));
    }
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\GudangTransferRequest  $GudangTransferRequest
    * @return \Illuminate\Http\Response
    */
    public function edit(GudangTransferRequest $GudangTransferRequest)
    {
    return view('gudang_transfer_requests.edit',compact('GudangTransferRequest'));
    }
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\GudangTransferRequest  $GudangTransferRequest
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
    $request->validate([
        'GudangTransferRequest_code' => 'required',
        'name' => 'required',
        'type' => 'required',
        'unit' => 'required',
        'updated_by' => 'required'


    ]);
    $GudangTransferRequest = GudangTransferRequest::find($id);
    $GudangTransferRequest->GudangTransferRequest_code = $request->GudangTransferRequest_code;
    $GudangTransferRequest->name = $request->name;
    $GudangTransferRequest->type = $request->type;
    $GudangTransferRequest->unit = $request->unit;
    $GudangTransferRequest->updated_by = $request->updated_by;
    $GudangTransferRequest->save();
    return redirect()->route('gudang_transfer_requests.index')
    ->with('success','GudangTransferRequest Has Been updated successfully');
    }
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\GudangTransferRequest  $GudangTransferRequest
    * @return \Illuminate\Http\Response
    */
    public function destroy(GudangTransferRequest $GudangTransferRequest)
    {
    $GudangTransferRequest->delete();
    return redirect()->route('gudang_transfer_requests.index')
    ->with('success','GudangTransferRequest has been deleted successfully');
    }
}
