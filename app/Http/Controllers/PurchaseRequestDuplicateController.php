<?php

namespace App\Http\Controllers;

use App\Http\Livewire\Log\Purchase;
use App\Models\IdxPurchaseRequest;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestDetail;
use App\Models\HistoryPurchase;
use App\Models\PurchaseOrderDetail;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseRequestDuplicateController extends Controller
{
    public function duplicate_pr(Request $request, $id)
    {
        $old_pr = PurchaseRequest::where("id", $id)->first();
        $new_pr = $old_pr->replicate();
        $new_pr->pr_no = $old_pr->pr_no . "D";
        $new_pr->status = 'New Duplicate';
        $new_pr->created_at = Carbon::now();
        $new_pr->remark = 'Duplicate from ' . $old_pr->pr_no;
        $new_pr->save();
        // dd($new_pr->id);

        PurchaseRequest::where("id", $id)->update([
            "status" => "Duplicated"
        ]);

        foreach ($old_pr->prdetail as $key => $prdetail) {
            $olddetail = PurchaseRequestDetail::where("id",$prdetail->id)->first();
            $detail = $olddetail->replicate();

            $detail->pr_id = $new_pr->id;
            $detail->save();
            // dd($detail);
            # code...
        }

        $new_pr_list = PurchaseRequest::where("pr_no", $old_pr->pr_no . "D")->get()->first();
        $currentuser = Auth::user();

        $history = new HistoryPurchase;
        $history->action_start = $old_pr->status;
        $history->action_end = 'New Duplicate';
        $history->referensi = $new_pr->pr_no;
        $history->action_by = $currentuser->id;
        $history->created_by = $currentuser->id;
        $history->action_date = Carbon::now();
        $history->created_at = Carbon::now();
        $history->save();
        // dd($new_pr_list);

        // PurchaseRequestDetail::where("pr_id",$old_pr->id)->update([
        //     'pr_id' => $new_pr_list->id
        // ]);

        // $old_detail = PurchaseRequestDetail::where("pr_id",$old_pr->id)->get();

        // foreach($old_detail as $detail)
        //     {

        //         $data = array ([


        //         ]);

        //     }



        return redirect()->route('purchase_requests.index')
            ->with('success', 'Anda Telah Berhasil Menduplicate PR ' . $old_pr->po_no . ' menjadi ' . $old_pr->pr_no . "D");
    }
    //
}
