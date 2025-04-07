<?php

namespace App\Mail\Vendor;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationRejected extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $vendor_name;
    public $reject_reason;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($vendor_name, $reject_reason)
    {
        $this->vendor_name = $vendor_name;
        $this->reject_reason = $reject_reason;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('notification@satrianusa.group', env('COMPANY') . ' Notification')->view('emails.vendor.registration-rejected')->subject("Vendor Registration Rejected");
    }
}
