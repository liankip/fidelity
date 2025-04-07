<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{

    use Queueable, SerializesModels;
    public $data;
    public $createdby;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data,$createdby)
    {
        $this->data = $data;
        $this->createdby = $createdby;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("No. PO ". $this->data->po_no." disetujui")->view('emails.mailapprove');
    }

}
