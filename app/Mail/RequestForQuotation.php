<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestForQuotation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $supplier;
    public $rfq;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($supplier, $rfq)
    {
        $this->supplier = $supplier;
        $this->rfq = $rfq;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('notification@satrianusa.group', env('COMPANY') . ' Notification')->view('view.emails.request-for-quotation')->subject("Request For Quotation");
    }
}
