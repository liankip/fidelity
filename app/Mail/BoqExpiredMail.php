<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BoqExpiredMail extends Mailable
{
    use Queueable, SerializesModels;

    public $todayDate;

    public $projectDetails;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $projectDetails)
    {
        $this->todayDate = Carbon::today();

        $this->projectDetails = $projectDetails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'Notifikasi BOQs Expired ' . Carbon::parse($this->todayDate)->translatedFormat('j F Y');

        return $this->from('notification@dcs.group', env('COMPANY') . ' Notification')->subject($subject)
            ->view('emails.boq.boq-expired')
            ->with(['projectDetails' => $this->projectDetails]);
    }
}
