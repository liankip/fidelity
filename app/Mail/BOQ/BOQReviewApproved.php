<?php

namespace App\Mail\BOQ;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Exports\BOQTableExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BOQReviewApproved extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $filename = "BOQ Approved - " . date('d-m-Y H:i:s') . ".xlsx";
        $content = Excel::raw(new BOQTableExport($this->data['boqs']), \Maatwebsite\Excel\Excel::XLSX);

        return $this->from('notification@dcs.group', env('COMPANY') . ' Notification')->subject($this->data['project_name'] . " - BOQ Approved")->attachData($content, $filename, [
            'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->view('emails.boq.review-approved');
    }
}
