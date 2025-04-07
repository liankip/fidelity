<?php

namespace App\Jobs;

use Mail;
use App\Mail\NearToPEstimate;
use Illuminate\Bus\Queueable;
use App\Models\NotificationTop;
use App\Helpers\GenerateVoucherNo;
use Illuminate\Support\Facades\Log;
use App\Models\NotificationEmailType;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Constants\EmailNotificationTypes;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Helpers\TermOfPayment\GenerateEstimate;
use App\Models\Voucher;

class SendToPNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $notifications = NotificationTop::with('purchaseorder')->get();
        $receivers = NotificationEmailType::getEmails(EmailNotificationTypes::PAYMENT_NOTIFICATION);

        // foreach ($notifications as $key => $notification) {
        //     $po = $notification->purchaseorder;

        //     if (GenerateEstimate::isNotifDue($notification->est_pay_date, $notification->top_type)) {
        //         foreach ($receivers as $receiver) {
        //             Mail::to($receiver->email)->send(new NearToPEstimate($po));
        //         }

        //         $notification->delete();
        //     }

        // Generate voucher
        // $isDueToday = GenerateEstimate::isDueToday($notification->est_pay_date);
        // if ($isDueToday) {
        //     $voucherNo = GenerateVoucherNo::get();
        //     $voucher = Voucher::create([
        //         'voucher_no' => $voucherNo,
        //         'creteated_at' => now(),
        //     ]);

        //     $voucher->voucher_details()->create([
        //         'purchase_order_id' => $po->id,
        //         'supplier_id' => $po->supplier_id,
        //         'project_id' => $po->project_id,
        //     ]);
        // }
        // }
    }
}
