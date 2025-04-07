<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Helpers\PurchaseOrderUtils;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class PurchaseOrderCompleteDocument extends Mailable implements ShouldQueue
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

        if (!is_null($this->data->po->completeDocument)) {
            $filepath = storage_path('app/public/' . $this->data->po->completeDocument->file_path);
            $this->attach($filepath);
        }

        $subject = PurchaseOrderUtils::getEmailSubject($this->data->po->po_no);

        return $this->from('notification@dcs.group', env('COMPANY') . ' Notification')->view('emails.purchase-order.complete-document')->subject($subject);
    }
}
