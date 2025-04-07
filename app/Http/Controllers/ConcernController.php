<?php

namespace App\Http\Controllers;

use App\Constants\PurchaseOrderStatus;
use App\Helpers\GetEmails;
use App\Models\EmailSend;
use App\Models\HistoryPayment;
use App\Models\PurchaseOrder;
use App\Models\User;
use App\Notifications\ConsernCreated;
use App\Notifications\PurchaseOrderListpay;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;


class ConcernController extends Controller
{
    //
    public function concern(Request $request, $id)
    {
        $currentuser = Auth::user();

        $old_status = PurchaseOrder::where('id', $id)->get()->first();
        $history = new HistoryPayment;
        $history->action_start = $old_status->status;
        $history->action_end = 'Concern';
        $history->referensi = $old_status->po_no;
        $history->action_by = $currentuser->id;
        $history->created_by = $currentuser->id;
        $history->action_date = Carbon::now();
        $history->created_at = Carbon::now();
        $history->save();

        PurchaseOrder::where('id', $request->id)->update([
            'status' => 'Concern',
            'remark_concern' => $request->remark_concern
        ]);

        $po = PurchaseOrder::where("id", $request->id)->first();
        $purches = User::where("type", 3)->orWhere("type", 4)->orWhere("type", 2)->orWhere("type", 5)->orWhere("type", 7)->get();

        foreach ($purches as $key => $pur) {
            $podata = [
                'po_no' => $po->po_no,
                'reason' => $request->remark_concern,
                "created_by" => Auth::user()->name
            ];
            //disable sementara
            Notification::send($pur, new ConsernCreated($podata));
        }
        // return redirect()->back();
        return redirect()->route('payment_list_noncash.index')
            ->with('success', 'Anda Telah Mengirim Flag Concern untuk PO ' . $po->po_no . '');
    }
    public function paydir(Request $request, $id)
    {
        // $po = PurchaseOrder::where("id",$request->id)->first();
        // $purches = User::where("type", 3)->orWhere("type",4)->orWhere("type",2)->orWhere("type",5)->orWhere("type",7)->get();

        // foreach ($purches as $key => $pur) {
        //     $podata = [
        //         'po_no' => $po->po_no,
        //         'po_detail' => $po->id,
        //     ];
        //     // dd($podata);

        //     Notification::send($pur, new PurchaseOrderApprove($podata));
        // }

        $currentuser = Auth::user();

        $old_status = PurchaseOrder::where('id', $id)->get()->first();
        $history = new HistoryPayment;
        $history->action_start = $old_status->status;
        $history->action_end = 'Need To Pay';
        $history->referensi = $old_status->po_no;
        $history->action_by = $currentuser->id;
        $history->created_by = $currentuser->id;
        $history->action_date = Carbon::now();
        $history->created_at = Carbon::now();
        $history->save();

        $po = PurchaseOrder::where("id", $request->id)->first();
        $receiver = User::whereIn("type", [2, 5])->get();

        foreach ($receiver as $key => $pur) {
            $podata = [
                'po_no' => $po->po_no,
            ];
            Notification::send($pur, new PurchaseOrderListpay($podata));
        }

        $mainmessage = "";

        foreach ($po->podetail as $no => $barang) {
            $mainmessage = $mainmessage . ($no + 1) . ". " . $barang->prdetail->item_name . "\n Qty: " . $barang->qty . "" . $barang->unit . "\n";
        }

        $messageh3 = url("payment_list");

        foreach ($receiver as $nilai) {
            // $msg = "*".config('app.company', 'SNE')." ERP* \n\nPembelian disetujui\nNo. PO: *" . $old_status->po_no . "* \n\n" . $mainmessage . "\n\n" . $messageh3 . "\nApproved by: *" . auth()->user()->name . "* \n_Ini adalah pesan otomatis. Simpan nomor ini sebagai contact agar URL pada pesan dapat di-klik._";
            $msg = "*" . config('app.company', 'SNE') . " ERP* \n\nNeed to pay\nNo. PO: *" . $po->po_no . "* \nProject: " . $po->pr->project->name . "\nRequested by: " . $po->pr->requester . "\nBagian pekerjaan: " . $po->pr->partof . " \n\n" . $mainmessage . "\n\n" . $messageh3 . "\nApproved by: *" . auth()->user()->name . "* \n\n_Ini adalah pesan otomatis. Simpan nomor ini sebagai contact agar URL pada pesan dapat di-klik._";

            if ($nilai->phone_number) {
                //dimatikan sementara
                // WaMessage::create([
                //     "number"    => $nilai->phone_number,
                //     "message"   => $msg
                // ]);
            }
        }

        $emailreceiver = GetEmails::get();
        foreach ($emailreceiver as $value) {
            EmailSend::create([
                "po_id" => $po->id,
                "type" => "NeedToPay",
                "email" => $value,
                "created_by" => $currentuser->id
            ]);
        }

        PurchaseOrder::where('id', $request->id)->update(['status' => PurchaseOrderStatus::NEED_TO_PAY]);
        return back()->with('success', 'Anda Telah Mengajukan Pembayaran untuk PO ' . $po->po_no . '');
    }
}
