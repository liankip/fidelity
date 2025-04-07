<?php

namespace App\Http\Livewire;

use App\Mail\ApprovedVoucher;
use App\Models\OfficeExpenseItem;
use App\Models\Payment;
use App\Models\PaymentSubmissionModel;
use App\Models\Voucher;
use App\Models\VoucherDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class DetailPaymentSubmissionApproval extends Component
{
    public $paramId;
    public $checked = [];
    public $additionalChecked = [];
    public $voucher;

    public $uncheckedVoucherNo = [];
    public $userProceed = false;
    public $showModal;
    public $grandTotal = 0;
    public $totalSum = 0;

    public function mount($paramId)
    {
        $this->paramId = $paramId;
    }

    public function render()
    {
        $dataVoucher = Voucher::with('voucher_details')->with('payment_submission')->where('payment_submission_id', $this->paramId)->orderBy('created_at', 'desc')->get();
        foreach ($dataVoucher as $voucher) {
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

        return view('livewire.detail-payment-submission-approval', [
            'dataVoucher' => $dataVoucher,
        ]);
    }

    public function save()
    {
        DB::beginTransaction();
        $this->showModal = true;
        try {
            $checkedIds = array_keys(array_filter($this->checked));

            $checkedIdsVoucher = [];
            foreach ($checkedIds as $checked) {
                $voucherDetailId = VoucherDetail::find($checked)->voucher->id;
                $checkedIdsVoucher[] = $voucherDetailId;
            }

            $additionalChecked = [];
            foreach ($this->additionalChecked as $voucherId => $indexes) {
                foreach ($indexes as $index => $isChecked) {
                    if ($isChecked) {
                        $additionalChecked[] = ['voucher_id' => $voucherId, 'index' => $index];
                    }
                }
            }

            $checkedIndexes = [];
            foreach ($this->additionalChecked as $voucherId => $indexes) {
                $filteredIndexes = array_keys(array_filter($indexes));
                if (!empty($filteredIndexes)) {
                    $checkedIndexes[$voucherId] = $filteredIndexes;
                }
            }

            $additionalCheckedVoucher = array_keys($checkedIndexes);
            // $idVoucher = Voucher::where('payment_submission_id', $this->paramId)->get();

            if (empty($checkedIds) && empty($additionalChecked)) {
                return redirect()->back()->with('fail', 'You must select at least one voucher detail to proceed.');
            }

            // $uncheckedAdditional = [];
            // foreach($idVoucher as $voucher) {
            //     if($voucher->additional_informations !== null) {
            //         $uncheckedAdditional[] = $voucher->id;
            //     }
            // }
            // $overallUncheckedAdditional = array_diff($uncheckedAdditional, $additionalCheckedVoucher);

            $allVouchers = Voucher::with('voucher_details')->where('payment_submission_id', $this->paramId)->get();
            foreach ($allVouchers as $voucher) {
                if ($voucher->hasDetails()) {
                    Payment::create([
                        'voucher_id' => $voucher->id,
                        'created_by' => auth()->user()->id,
                    ]);

                    foreach ($voucher->voucher_details as $detail) {
                        $poData = \App\Models\PurchaseOrder::find($detail->purchase_order_id);
                        $poTotal = intval($poData->total_amount);
                        $sumAmount = intval(VoucherDetail::where('purchase_order_id', $poData->id)->sum('amount_to_pay'));
                        if ($poTotal === $sumAmount) {
                            $poData->update([
                                'status' => 'Paid',
                            ]);
                        }
                    }
                }
            }

            $allVoucherDetailIds = $allVouchers->pluck('voucher_details.*.id')->flatten()->toArray();

            // Step 3: Calculate the IDs of the unchecked voucher details
            $uncheckedIds = array_diff($allVoucherDetailIds, $checkedIds);

            $uncheckedIdVoucher = [];
            foreach ($uncheckedIds as $unchecked) {
                $voucherDetailId = VoucherDetail::find($unchecked)->voucher->id;
                $uncheckedIdVoucher[] = $voucherDetailId;
            }

            // Check Unchecked Vouchers
            $combineChecked = array_merge($checkedIdsVoucher, $additionalCheckedVoucher);
            $uncheckedVouchers = array_diff($allVouchers->pluck('id')->flatten()->toArray(), $combineChecked);

            $this->uncheckedVoucherNo = [];
            foreach ($uncheckedVouchers as $voucher) {
                $this->uncheckedVoucherNo[] = Voucher::where('id', $voucher)->first()->voucher_no;
            }

            if ((!empty($uncheckedVouchers) && $this->userProceed === true) || empty($uncheckedVouchers)) {
                // if (!empty($uncheckedAdditional)) {
                //     Voucher::whereIn('id', $uncheckedAdditional)->update(['additional_informations' => null]);
                // }

                foreach ($checkedIndexes as $voucherId => $indexes) {
                    $voucher = Voucher::find($voucherId);

                    $jsonData = json_decode($voucher->additional_informations, true) ?? [];

                    $newJsonData = [];
                    foreach ($indexes as $index) {
                        if (isset($jsonData[$index])) {
                            $newJsonData[] = $jsonData[$index];
                        }
                    }

                    foreach ($jsonData as $item) {
                        if (!in_array($item, $newJsonData)) {
                            if (isset($item['id'])) {
                                OfficeExpenseItem::where('id', $item['id'])->update([
                                    'is_approval' => false,
                                ]);
                            }
                        }
                    }

                    $paymentData = Payment::create([
                        'voucher_id' => $voucher->id,
                        'created_by' => auth()->user()->id,
                    ]);

                    $paymentData->refresh();

                    foreach ($newJsonData as $data) {
                        if (isset($data['id'])) {
                            OfficeExpenseItem::where('id', $data['id'])->update([
                                'is_paid' => true,
                            ]);
                        }
                    }

                    $voucher->additional_informations = json_encode($newJsonData);
                    $voucher->save();
                }

                // Step 4: Delete the unchecked voucher details from the database
                Voucher::whereIn('id', $uncheckedVouchers)->delete();
                VoucherDetail::whereIn('id', $uncheckedIds)->delete();
                PaymentSubmissionModel::where('id', $this->paramId)->update([
                    'status' => 'Approved',
                    'approved_by' => auth()->user()->id,
                    'date_approved' => Carbon::now(),
                ]);

                DB::commit();

                $email = [
                    'admin@satrianusa.group',
                    'ops@satrianusa.group',
                ];

                Mail::to(users: $email)->send(new ApprovedVoucher($this->paramId));

                return redirect()->route('payment-submission-approval')
                    ->with('success', 'Payment Submission berhasil di approve');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public function proceed()
    {
        $this->userProceed = true;
        $this->save();
    }

    public function closeModal()
    {
        $this->reset('showModal', 'userProceed', 'uncheckedVoucherNo');
    }
}
