<?php

namespace App\Mail;

use App\Models\PurchaseRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PurchaseRequestCreated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $pr;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PurchaseRequest $pr)
    {
        $this->pr = $pr;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('notification@dcs.group', env('COMPANY') . ' Notification')->view('emails.purchase-request.created')->subject("Purchase Request Created with No. PR " . $this->pr->pr_no);
    }
}
