<?php

namespace App\Http\Livewire;

use App\Models\PaymentSubmissionModel;
use App\Models\Voucher;
use Livewire\Component;

class PaymentSubmissionApproval extends Component
{
    private $totalSum = 0;
    public $paymentSubmitionList = [];

    public function mount()
    {
        $this->totalPaymentSubmission();
    }

    public function totalPaymentSubmission()
    {
        $paymentSubmitionList = PaymentSubmissionModel::where('status', 'Waiting for approval')->get();

        foreach ($paymentSubmitionList as $paymentSubmission) {
            $this->totalSum = 0; // Reset total sum for each payment submission

            $vouchers = Voucher::with('voucher_details')
                ->with('payment_submission')
                ->where('payment_submission_id', $paymentSubmission->id)
                ->get();

            foreach ($vouchers as $voucher) {
                foreach ($voucher->voucher_details as $detail) {
                    $this->totalSum += $detail->amount_to_pay;
                }

                $additionalInformations = json_decode($voucher->additional_informations, true) ?? [];

                foreach ($additionalInformations as $additionalInformation) {
                    if (isset($additionalInformation['total']) && is_numeric($additionalInformation['total'])) {
                        $this->totalSum += $additionalInformation['total'];
                    }
                }
            }

            $paymentSubmission->totalSum = $this->totalSum; // Store total sum in the payment submission object
        }

        $this->paymentSubmitionList = $paymentSubmitionList;
    }

    public function render()
    {
        return view('livewire.payment-submission-approval', [
            'paymentSubmissionData' => $this->paymentSubmitionList
        ]);
    }
}
