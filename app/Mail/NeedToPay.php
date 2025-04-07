<?php

namespace App\Mail;

use App\Models\EmailSend;
use App\Models\PurchaseOrder;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NeedToPay extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $createdby;
    public $emailsend;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PurchaseOrder $data, User $createdby, EmailSend $emailsend)
    {
        $this->data = $data;
        $this->createdby = $createdby;
        $this->emailsend = $emailsend;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.needToPay')->subject("Payment Notification for project " . $this->data->pr->project->name);
    }
}
