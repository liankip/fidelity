<?php

namespace App\Mail;

use App\Models\PurchaseOrder;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NearToPEstimate extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PurchaseOrder $data)
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
        foreach ($this->data->invoices as $key => $value) {
            $this->attach(public_path($value->foto_invoice));
        }
        // $this->data->invoices;
        return $this->from('notification@dcs.group', env('COMPANY') . ' Notification')->view('emails.neartopestimate')->subject("Purchase Order with No. PO " . $this->data->po_no);
    }
}
