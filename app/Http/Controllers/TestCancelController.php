<?php

namespace App\Http\Controllers;
use App\Models\PurchaseRequest;
use App\Models\User;
use Illuminate\Support\Carbon;
use App\Notifications\PurchaseReqeustCancel;
use App\Models\HistoryPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class TestCancelController extends Controller
{
    //
    public function cancel_pr(Request $request, $id)
    {
        // $userId = Auth::id();
        // $request->validate([
        //     'status' => 'required',
        // ]);
        // $purchaserequest = PurchaseRequest::find($id);
        // $purchaserequest->status = "Cancel";
        // $purchaserequest->updated_by = $userId;
        // $purchaserequest->save();

        // return redirect()->route('purchase_requests.index')
        // ->with('success','Purchase Request Has Been Canceled Successfully');
        // $data = PurchaseRequest::findOrFail($id);
        // dd($data);
        // $data = PurchaseRequest::findOrFail($id);

        $currentuser = Auth::user();
        $old_pr = PurchaseRequest::where('id', $request->id)->get()->first();

        $pr = PurchaseRequest::where('id', $request->id)->update(['status' => 'Cancel']);

        $history = new HistoryPurchase;
        $history->action_start = $old_pr->status;
        $history->action_end = 'Cancel';
        $history->referensi = $old_pr->pr_no;
        $history->action_by = $currentuser->id;
        $history->created_by = $currentuser->id;
        $history->action_date = Carbon::now();
        $history->created_at = Carbon::now();
        $history->save();

        $purchaserequest = PurchaseRequest::where('id',$request->id)->first();
        $purches = User::where("type",3)->orWhere("type",5)->get();

        foreach ($purches as $key => $pur) {
        $podata = [
            'pr_no' => $purchaserequest->pr_no,
            'pr_detail' => $purchaserequest->id,
            "created_by" => $currentuser->name
        ];
        Notification::send($pur, new PurchaseReqeustCancel($podata));
    }


        return redirect()->back();
    }
}
