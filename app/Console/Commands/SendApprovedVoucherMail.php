<?php

namespace App\Console\Commands;

use App\Mail\ApprovedVoucher;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\PaymentSubmissionModel;

class SendApprovedVoucherMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:approved-voucher {payment_submission_id} {recipients}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an approved voucher email for a specific payment submission ID to multiple recipients';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $paymentSubmissionId = $this->argument('payment_submission_id');
        $recipients = array_map('trim', explode(',', $this->argument('recipients'))); // Trim and convert to an array

        // Check if the payment submission exists
        $paymentSubmission = PaymentSubmissionModel::find($paymentSubmissionId);

        if (!$paymentSubmission) {
            $this->error("Payment Submission with ID {$paymentSubmissionId} not found.");
            return;
        }

        // Send one email to multiple recipients
        Mail::to($recipients)->send(new ApprovedVoucher($paymentSubmissionId));

        $this->info("Approved Voucher email sent to: " . implode(', ', $recipients));
    }
}
