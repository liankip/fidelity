<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VendorApproved extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $vendor;
    public $password;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($vendor)
    {
        $this->vendor = $vendor;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('notification@satrianusa.group', env('COMPANY') . ' Notification')->view('emails.vendor-registration-approved')->subject("Vendor Registration Approved");
    }
}
