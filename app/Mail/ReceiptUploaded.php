<?php

namespace App\Mail;

use App\Helpers\PurchaseOrderUtils;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReceiptUploaded extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $po;
    public $receipt;
    public $poNumber;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($po, $receipt)
    {
        $this->po = $po;
        $this->receipt = $receipt;
        $this->attach(public_path($this->receipt->foto_invoice));
        $num = explode('/', $po->po_no);

        if (count($num) >= 1) {
            $this->poNumber = $num[0];
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = PurchaseOrderUtils::getEmailSubject($this->po->po_no);

        return $this->from('notification@dcs.group', env('COMPANY') . ' Notification')->view('emails.invoice.uploaded')->subject($subject);
    }
}
