<?php

namespace App\Mail;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CompletePurchaseBoqItemsMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $projectData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Project $project)
    {
        $this->projectData = $project;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        try {
            $subject = 'Notifikasi Pembelian Item BOQ';
            return $this->from('notification@dcs.group', env('COMPANY') . ' Notification')->subject($subject)
                ->view('emails.boq.complete-boq-items-purchase');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
