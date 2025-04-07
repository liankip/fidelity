<?php

namespace App\Traits;

use App\Models\User;
use App\Helpers\Whatsapp;
use App\Jobs\SendWhatsapp;
use Illuminate\Support\Facades\Mail;
use App\Models\NotificationEmailType;
use App\Constants\EmailNotificationTypes;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VoucherApprove as VoucherApproveNotification;

trait VoucherApprove
{

    public function sendApprovedVoucherNotification($po)
    {
        $reserved = User::whereIn("type", [2, 3, 4, 5])->get();
        foreach ($reserved as $key => $pur) {
            $podata = [
                'voucher_no' => $po->voucher_no,
                'voucher_detail' => $po->id,
                'action_by' => auth()->user()->name
            ];

            Notification::send($pur, new VoucherApproveNotification($podata));
        }

        // // Send email to Manager
        // $types = NotificationEmailType::getEmails(EmailNotificationTypes::PO_APPROVED);
        // if ($types->count() > 0) {
        //     $emails = $types->pluck('email');
        //     Mail::cc($emails)->send(new \App\Mail\PurchaseOrderApproved($po));
        // }

        // // Send Whatsapp message to requester
        // $to = $po->pr->requester_phone_number;
        // if ($to) {
        //     $waMessage = Whatsapp::POApprovedMessage($po->pr->requester, $po);
        //     SendWhatsapp::dispatch($waMessage, $to);
        // }
    }
}
