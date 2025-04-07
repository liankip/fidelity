<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\User;
use App\Notifications\UpPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Carbon;
use App\Models\HistoryPayment;

class UpPaymentController extends Controller
{
    public function uppayment(Request $request, $id)
    {

        $currentuser = Auth::user();
        $old_status = PurchaseOrder::where('id', $id)->get()->first();
        $history = new HistoryPayment;
        $history->action_start = $old_status->status;
        $history->action_end = 'Waiting For Payment';
        $history->referensi = $old_status->po_no;
        $history->action_by = $currentuser->id;
        $history->created_by = $currentuser->id;
        $history->action_date = Carbon::now();
        $history->created_at = Carbon::now();
        $history->save();

        $po1 = PurchaseOrder::where('id', $request->id)->update(['status' => 'Waiting For Payment']);

        $po = PurchaseOrder::where("id", $request->id)->first();

        $purches = User::where("type", 2)->orWhere("type", 4)->orWhere("type", 5)->get();

        foreach ($purches as $key => $pur) {
            $podata = [
                'po_no' => $po->po_no,
                'po_detail' => $po->id,
                'created_by' => Auth::user()->name,

            ];
            // dd($podata);

            Notification::send($pur, new UpPayment($podata));
        }
        // return redirect()->back();
        return redirect()->route('payment_waiting_lists.index')
            ->with('success', 'Anda Telah Mengajukan Pembayaran untuk PO ' . $po->po_no . '');
    }
}
