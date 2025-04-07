<?php

namespace App\Jobs;

use App\Mail\MinuteOfMeetingApproval;
use App\Models\MinutesOfMeeting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class MinutesOfMeetingApproval implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $participants = $this->data->participants()->pluck('email')->toArray();
        $staticEmails = ['antony@satrianusa.group', 'feli@satrianusa.group', 'joshua@satrianusa.group'];
        $allRecipients = array_merge($staticEmails, $participants);

        foreach ($allRecipients as $email) {
            Mail::to($email)->send(new MinuteOfMeetingApproval($this->data));
        }
    }
}
