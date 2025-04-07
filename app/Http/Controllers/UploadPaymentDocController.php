<?php

namespace App\Http\Controllers;

use App\Constants\PurchaseOrderStatus;
use App\Jobs\GenerateCompleteDocument;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\HistoryPayment;
use Illuminate\Support\Carbon;
use App\Models\NotificationTop;
use Illuminate\Support\Facades\Auth;
use App\Notifications\PurchaseOrderPaid;
use App\Traits\NotificationEmailManager;
use App\Traits\PurchaseOrderDocuments;
use Illuminate\Support\Facades\Notification;

class UploadPaymentDocController extends Controller
{
    use NotificationEmailManager;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($po_id)
    {
        $po = PurchaseOrder::where('id', $po_id)->get()->first();

        if ($po->status === PurchaseOrderStatus::PAID || $po->status === PurchaseOrderStatus::COMPLETED) {
            return redirect()->route('payment-list')->with('error', 'PO sudah lunas');
        }

        return view('PaymentUpload', compact('po'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // store upload payment
        $request->validate([
            'payment_pict' => 'required|mimes:jpeg,png,jpg,pdf|max:5000',
            'status' => 'required',
            'po_id' => 'required'
        ], [
            'payment_pict' => 'proof of payment',
            'status' => 'Status of payment'
        ]);

        $imageName = time() . '.' . $request->payment_pict->extension();

        $request->payment_pict->move(public_path('images/payment'), $imageName);

        $path = 'images/payment/' . $imageName;

        $today = Carbon::now()->format('Y-m-d');
        // $est_cod    = date('Y-m-d', strtotime('+3 days', strtotime($today)));
        // $est_net7    = date('Y-m-d', strtotime('+7 days', strtotime($today)));
        $est_etc    = date('Y-m-d', strtotime('+30 days', strtotime($today)));
        // $est_paid_off =
        $userId = auth()->user()->id;

        $save = new Payment;

        // dd($request->po_id);
        $save->po_id = $request->po_id;
        $save->payment_pict = $path;
        $save->status = $request->status;
        $save->notes = $request->notes;

        $save->save();

        // Email payment
        $save_status = PurchaseOrder::find($request->po_id);
        $data = (object)[
            'po' => $save_status,
            'pr' => $save_status->pr,
            'payment' => $save,
            'uploadedby' => Auth::user()->name,
        ];

        $this->sendEmailPaymentUploaded($data);

        // $qty_item_po = PurchaseOrderDetail::where('purchase_order_id',$request->po_id)->where('item_id',$request->item_id)->get()->first();

        $cek_po = NotificationTop::where('purchase_order_id', $request->po_id)->get()->first();
        // $save_date = NotificationTop::where('purchase_order_id',$request->po_id)->get()->first();
        if ($request->status == "Lunas") {
            $currentuser = Auth::user();

            $old_status = PurchaseOrder::where('id', $request->po_id)->get()->first();
            $history = new HistoryPayment;
            $history->action_start = $old_status->status;
            $history->action_end = 'Paid';
            $history->referensi = $old_status->po_no;
            $history->action_by = $currentuser->id;
            $history->created_by = $currentuser->id;
            $history->action_date = Carbon::now();
            $history->created_at = Carbon::now();
            $history->save();

            if ($old_status->status_barang == "Arrived") {
                $save_status->status = 'Completed';

                if ($save_status->completeDocument?->count() == 0) {
                    // Generate complete document
                    GenerateCompleteDocument::dispatch($request->po_id);

                    // Send email
                    $data = (object)[
                        'po' => $save_status,
                    ];
                    $this->sendEmailCompleteDocument($data);
                }
            } else {
                $save_status->status = 'Paid';
            }
            $save_status->save();

            NotificationTop::where('purchase_order_id', $request->po_id)->update([
                'up_pay_date' => $today,
                'paid_off_date' => $today,
                'updated_by' => $currentuser->id,
            ]);
        } else {
            $currentuser = Auth::user();
            $old_status = PurchaseOrder::where('id', $request->po_id)->get()->first();
            $history = new HistoryPayment;
            $history->action_start = $old_status->status;
            $history->action_end = 'Partially Paid';
            $history->referensi = $old_status->po_no;
            $history->action_by = $currentuser->id;
            $history->created_by = $currentuser->id;
            $history->action_date = Carbon::now();
            $history->created_at = Carbon::now();
            $history->save();

            $save_status->status = 'Partially Paid';
            $save_status->remark = $save->notes;
            $save_status->save();

            NotificationTop::where('purchase_order_id', $request->po_id)->update([
                'up_pay_date' => $today,
                'paid_off_date' => $today,
                'updated_by' => $currentuser->id,

            ]);

            // $save_date->up_pay_date = $today;
            // $save_date->est_pay_date = $est_etc;
            // $save_date->updated_by = $userId;
            // $save_date->save();
        }

        $po = PurchaseOrder::where("id", $request->po_id)->first();
        $reserved = User::whereIn("type", [2, 3, 4, 5])->get();

        foreach ($reserved as $key => $pur) {
            $podata = [
                'po_no' => $po->po_no,
                'po_detail' => $po->id,
                'status' => $request->status,
                'created_by' => Auth::user()->name,

            ];

            Notification::send($pur, new PurchaseOrderPaid($podata));
        }

        return back()
            ->with('success', 'You have successfully upload image.')
            ->with('image', $imageName);
    }
}
