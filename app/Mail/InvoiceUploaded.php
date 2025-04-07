<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Helpers\PurchaseOrderUtils;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class InvoiceUploaded extends Mailable implements ShouldQueue
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
        $this->attach($this->receipt->foto_invoice);

        if ($this->receipt->tax_invoice_photo != null) {
            $this->attach($this->receipt->tax_invoice_photo);
        }

        $subject = PurchaseOrderUtils::getEmailSubject($this->po->po_no);

        return $this->from('notification@dcs.group', env('COMPANY') . ' Notification')->view('emails.invoice.uploaded')->subject($subject);
    }
}
