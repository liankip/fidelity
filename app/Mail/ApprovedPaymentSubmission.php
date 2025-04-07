<?php

namespace App\Mail;

use App\Models\PaymentSubmissionModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApprovedPaymentSubmission extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $paymentSubData;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PaymentSubmissionModel $paramId)
    {
        $this->paymentSubData = $paramId;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'Instruksi bayar pengajuan payment submission ' . $this->paymentSubData->type . ' tanggal ' . $this->paymentSubData->created_at->format('d F Y');
        return $this->from('notification@dcs.group', env('COMPANY') . ' Notification')->subject($subject)->view('emails.payment-submission.approved-payment-submission');
    }
}
