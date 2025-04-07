<?php

namespace App\Mail;

use App\Models\PaymentSubmissionModel;
use App\Models\Voucher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApprovedVoucher extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $paymentSubId;
    public $paymentSubCreated;
    public $voucherData;
    public $paymentSubType;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($paymentSubmissionParam)
    {
        $this->paymentSubId = $paymentSubmissionParam;
        $this->paymentSubCreated = PaymentSubmissionModel::find($this->paymentSubId)->created_at->format('d F Y');
        $this->paymentSubType = PaymentSubmissionModel::find($this->paymentSubId)->type;
        $this->voucherData = Voucher::with('voucher_details')->where('payment_submission_id', $paymentSubmissionParam)->get();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'Instruksi bayar pengajuan payment submission ' . $this->paymentSubType . ' tanggal ' . $this->paymentSubCreated;
        return $this->from('notification@dcs.group', env('COMPANY') . ' Notification')->subject($subject)->view('emails.vouchers.approved-voucher');
    }
}
