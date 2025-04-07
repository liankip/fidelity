<?php

namespace App\Http\Livewire\Payment;

use App\Models\OfficeExpenseItem;
use App\Models\PaymentSubmissionModel;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentSubmission extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $radioValue;
    public $editValue;

    private $totalSum = 0;
    private $grandTotal = 0;

    public $search = '';
    // public $paymentSubmitionList = [];

    // public function mount()
    // {
    //     $this->totalPaymentSubmission();
    // }

    public function totalPaymentSubmission()
    {
        $paymentSubmitionList = PaymentSubmissionModel::orderBy('created_at', 'desc')->paginate(15);

        if ($this->search != '') {
            $paymentSubmitionList = PaymentSubmissionModel::whereHas('vouchers.voucher_details.purchase_order.podetail.item', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
                ->with([
                    'vouchers' => function ($query) {
                        $query->whereHas('voucher_details.purchase_order.podetail.item', function ($subQuery) {
                            $subQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                    },
                ])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        foreach ($paymentSubmitionList as $paymentSubmission) {
            $this->totalSum = 0; // Reset total sum for each payment submission

            $vouchers = Voucher::with('voucher_details')->with('payment_submission')->where('payment_submission_id', $paymentSubmission->id)->get();

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

            $this->grandTotal += $this->totalSum;
            $paymentSubmission->totalSum = $this->totalSum; // Store total sum in the payment submission object
        }

        return $paymentSubmitionList;
    }

    public function render()
    {
        $data = $this->totalPaymentSubmission();
        return view('livewire.payment.payment-submission', [
            'paymentSubmitionList' => $data,
        ]);
    }

    public function submitFunction()
    {
        DB::beginTransaction();
        try {
            PaymentSubmissionModel::create([
                'status' => 'Draft',
                'type' => $this->radioValue,
            ]);

            DB::commit();
            return redirect()->route('payment-submission')->with('success', 'Payment Submission berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    public function detailPaymentSubmission($paramId)
    {
        return redirect()->route('payment-submission.voucher.index', $paramId);
    }

    public function propose(PaymentSubmissionModel $submission)
    {
        if ($submission->vouchers->count() > 0) {
            foreach ($submission->vouchers as $voucher) {
                if (!is_null($voucher->additional_informations)) {
                    foreach (json_decode($voucher->additional_informations, true) as $additionalInformation) {
                        if (isset($additionalInformation['id'])) {
                            OfficeExpenseItem::where('id', $additionalInformation['id'])->update([
                                'is_approval' => true,
                            ]);
                        }
                    }
                }
            }

            $submission->update([
                'status' => 'Waiting for approval',
            ]);

            return redirect()->route('payment-submission')->with('success', 'Payment Submission berhasil diajukan');
        } else {
            return redirect()->route('payment-submission')->with('danger', 'Payment Submission gagal diajukan');
        }
    }

    public function closeEditModal()
    {
        $this->editValue = null;
        $this->radioValue = null;
    }

    public function print(PaymentSubmissionModel $submission)
    {
        global $grandTotal;
        foreach ($submission->vouchers as $voucher) {
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
        $grandTotal += $this->totalSum;

        return view('livewire.voucher.print-vouchers-new', compact('submission', 'grandTotal'));
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }
}
