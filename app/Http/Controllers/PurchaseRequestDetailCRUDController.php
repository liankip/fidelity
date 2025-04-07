<?php

namespace App\Http\Controllers;

use App\Http\Livewire\Log\Purchase;
use App\Models\BOQ;
use App\Models\Item;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseRequestDetailCRUDController extends Controller
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
        // $cartItems = \Cart::getContent();
        $items = \Cart::session($userId)->getContent();
        return view('purchase_request_details.index', compact('items'));
        // $purchase_request_details = purchaserequestdetail::all();
        // return view('purchase_request_details.index', compact(['items','purchase_request_details']));
    }
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $purchaserequest = PurchaseRequest::all();
        $barang = Item::all();

        return view('purchase_request_details.create', compact(['purchaserequest','barang']));
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
        'pr_id' => 'required',
        'item_id' => 'required',
        'item_name' => 'required',
        'type' => 'required',
        'unit' => 'required',
        'qty' => 'required',
        'status' => 'required',
        'notes' => 'required',
        'created_by' => 'required'
    ]);
    $purchaserequestdetail = new purchaserequestdetail;
    $purchaserequestdetail->pr_id = $request->pr_id;
    $purchaserequestdetail->item_id = $request->pr_type;
    $purchaserequestdetail->item_name = $request->project_id;
    $purchaserequestdetail->type = $request->nama_project;
    $purchaserequestdetail->unit = $request->warehouse_id;
    $purchaserequestdetail->qty = $request->nama_warehouse;
    $purchaserequestdetail->status = $request->status;
    $purchaserequestdetail->notes = $request->notes;
    $purchaserequestdetail->created_by = $request->created_by;
    $purchaserequestdetail->save();

    return redirect()->route('purchase_requests.index')
    ->with('success','Item has been added successfully.');
    }
    /**
    * Display the specified resource.
    *
    * @param  \App\purchaserequestdetail  $purchaserequestdetail
    * @return \Illuminate\Http\Response
    */
    public function show(PurchaseRequestDetail $purchaserequestdetail, $pr_id)
    {
        $purchaserequestdetail = PurchaseRequestDetail::with('podetailall.po', 'createdBy')->where('pr_id', $pr_id)->get();
        $statuspr = PurchaseRequest::where('id',$pr_id)->first();

        return view('purchase_request_details.index',
        compact([
            'purchaserequestdetail',
            'statuspr']));
    }
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\purchaserequestdetail  $purchaserequestdetail
    * @return \Illuminate\Http\Response
    */
    public function edit(purchaserequestdetail $purchaserequestdetail)
    {
    return view('purchase_request_details.edit',compact('purchaserequestdetail'));
    }
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\purchaserequestdetail  $purchaserequestdetail
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
    $request->validate([
        'pr_id' => 'required',
        'item_id' => 'required',
        'item_name' => 'required',
        'type' => 'required',
        'unit' => 'required',
        'qty' => 'required',
        'status' => 'required',
        'notes' => 'required',
        'updated_by' => 'required'


    ]);
    $purchaserequestdetail = purchaserequestdetail::find($id);
    $purchaserequestdetail->pr_id = $request->pr_no;
    $purchaserequestdetail->item_id = $request->pr_type;
    $purchaserequestdetail->item_name = $request->project_id;
    $purchaserequestdetail->type = $request->nama_project;
    $purchaserequestdetail->unit = $request->warehouse_id;
    $purchaserequestdetail->qty = $request->nama_warehouse;
    $purchaserequestdetail->status = $request->status;
    $purchaserequestdetail->notes = $request->notes;
    $purchaserequestdetail->updated_by = $request->updated_by;
    $purchaserequestdetail->save();
    return redirect()->route('purchase_request_details.index')
    ->with('success','purchaserequestdetail Has Been updated successfully');
    }
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\purchaserequestdetail  $purchaserequestdetail
    * @return \Illuminate\Http\Response
    */
    public function destroy(purchaserequestdetail $purchaserequestdetail)
    {
    $purchaserequestdetail->delete();
    return redirect()->route('purchase_request_details.index')
    ->with('success','purchaserequestdetail has been deleted successfully');
    }

    public function rejectItem($detailId)
    {
        DB::beginTransaction();
        try {
          
            $detailData = PurchaseRequestDetail::findOrFail($detailId);

            $prData = $detailData->purchaseRequest;
            $itemId = $detailData->item_id;

            $splitPrNo = explode('-', $prData->pr_no);
            
            $isTaskConsumables = count($splitPrNo) > 2 || $detailData->purchaseRequest->task->task == 'Consumables' || $detailData->purchaseRequest->section == 'Consumables' ||
            $detailData->purchaseRequest->task->is_consumables == 1
            ;

            if($isTaskConsumables) { 
                $section = 0;
            } else {
                $section = $splitPrNo[1] - 1;
            }


            $boqData = BOQ::where('project_id', $prData->project_id)->where('item_id', $itemId)->where('task_number', $prData->partof)->where('section', $section)->first();

            if($boqData == null) {
                $section = $splitPrNo[1];
                $boqData = BOQ::where('project_id', $prData->project_id)->where('item_id', $itemId)->where('task_number', $prData->partof)->where('section', $section)->first();
            }

            $boqData->qty = $boqData->qty - $detailData->qty;
            
            $boqData->rejected_by = auth()->user()->id;
            $boqData->save();


            $detailData->status = 'Rejected';
            $detailData->rejected_by = auth()->user()->id;
            $detailData->save();

            DB::commit();

            return redirect()->route('purchase_request_details.show', $detailData->purchaseRequest->id)->with('success', 'Item has been rejected successfully.');

        } catch (\Exception $e) {
            dd($e);
        }
        
    }
}
