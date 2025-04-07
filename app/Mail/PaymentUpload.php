<?php

namespace App\Mail;

use App\Helpers\PurchaseOrderUtils;
use App\Models\EmailSend;
use App\Models\PurchaseOrder;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentUpload extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->attach(public_path($this->data->payment->payment_pict));

        $subject = PurchaseOrderUtils::getEmailSubject($this->data->po->po_no);
        return $this->from('notification@dcs.group', env('COMPANY') . ' Notification')->subject($subject)->view('emails.paymentUploaded');
    }
}
