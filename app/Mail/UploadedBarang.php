<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Helpers\PurchaseOrderUtils;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UploadedBarang extends Mailable implements ShouldQueue
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
        if (count($this->data->arrivedImagesPath) > 0) {
            foreach ($this->data->arrivedImagesPath as $image) {
                $this->attach(public_path($image));
            }
        }

        $subject = PurchaseOrderUtils::getEmailSubject($this->data->po->po_no);
        return $this->from('notification@dcs.group', env('COMPANY') . ' Notification')->subject($subject)->view('emails.uploadedbarang');
    }
}
