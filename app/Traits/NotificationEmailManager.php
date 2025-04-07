<?php

namespace App\Traits;

use Illuminate\Support\Facades\Mail;
use App\Models\NotificationEmailType;
use App\Constants\EmailNotificationTypes;

trait NotificationEmailManager
{
    public function sendEmailItemArrived($data): void
    {
        $types = NotificationEmailType::getEmails(EmailNotificationTypes::ITEM_ARRIVED);
        if ($types->count() > 0) {
            $emails = $types->pluck('email');
            Mail::cc($emails)->send(new \App\Mail\UploadedBarang($data));
        }
    }

    public function sendEmailPaymentUploaded($data): void
    {
        $types = NotificationEmailType::getEmails(EmailNotificationTypes::PAYMENT_UPLOADED);
        if ($types->count() > 0) {
            $emails = $types->pluck('email');
            Mail::cc($emails)->send(new \App\Mail\PaymentUpload($data));
        }
    }

    public function sendEmailCompleteDocument($data): void
    {
        $types = NotificationEmailType::getEmails(EmailNotificationTypes::PO_COMPLETE_DOCUMENT);
        if ($types->count() > 0) {
            $emails = $types->pluck('email');
            Mail::cc($emails)->send(new \App\Mail\PurchaseOrderCompleteDocument($data));
        }
    }
}
