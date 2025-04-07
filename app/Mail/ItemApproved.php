<?php

namespace App\Mail;

use App\Exports\ItemApprovedExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class ItemApproved extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $filename = "New Item Approved - " . date('d-m-Y H:i:s') . ".xlsx";

        $content = Excel::raw(new ItemApprovedExport($this->items), \Maatwebsite\Excel\Excel::XLSX);

        return $this->from('notification@dcs.group', env('COMPANY') . ' Notification')->subject('New Item Approved')->attachData($content, $filename, [
            'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])
            ->view('emails.items.item-approved');
    }
}
