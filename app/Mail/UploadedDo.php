<?php

namespace App\Mail;

use App\Models\DeliveryOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UploadedDo extends Mailable
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
    public function __construct($data, $createdby, $emailsend)
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

        $this->attach(public_path($this->emailsend->do->do_pict));
        return $this->subject("New Delivery Order Uploaded with No. PO " . $this->data->po_no)->view('emails.douploaded');
    }
}
