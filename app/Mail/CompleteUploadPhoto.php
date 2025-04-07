<?php

namespace App\Mail;

use App\Helpers\PurchaseOrderUtils;
use App\Models\PurchaseOrder;
use App\Models\SubmitionHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompleteUploadPhoto extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $poData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PurchaseOrder $po)
    {
        $this->poData = $po;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = PurchaseOrderUtils::getEmailSubject($this->poData->po_no);

        $email = $this->from('notification@dcs.group', env('COMPANY') . ' Notification')
            ->subject($subject)
            ->view('emails.purchase-order.upload-photo-complete');

        return $email;
    }
}
