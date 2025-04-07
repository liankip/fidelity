<?php

namespace App\Http\Controllers;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PengajuanPoController extends Controller
{
    //
    public function pengujanpo(Request $request, $id)
    {
        // $data = PurchaseOrder::where('id', $request->id)->update(['status' => 'Wait For Approval']);
        // dd($data);
        // PurchaseRequest::where('id', $request->id)->update(['status' => 'Approved']);
        PurchaseOrder::where('id', $request->id)->update(['status' => 'Wait For Approval']);
        return redirect()->back();
    }
}
