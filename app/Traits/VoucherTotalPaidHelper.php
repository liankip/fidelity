<?php

namespace App\Traits;

use App\Models\Payment;
use App\Models\VoucherDetail;

trait VoucherTotalPaidHelper
{
    public function addTotalPaidAttribute($dataCollection)
    {
        foreach ($dataCollection as $voucher) {
            if ($voucher->hasVoucherDetails()) {
                $voucher->total_paid_amount = 0;
                $sumTotalDetail = 0;

                foreach ($voucher->voucherDetail as $detail) {
                    // Check Approval
                    if($detail->voucher !== null && $detail->voucher->payment_submission !== null) {
                        if($detail->voucher->payment_submission->status !== 'Approved') {
                            $voucher->incomplete_approval = 'Menunggu approval';
                            break;
                        }
                    }

                    $existInPayment = Payment::where('voucher_id', $detail->voucher_id)->first();
                    // Check record in payment
                    if ($existInPayment) {
                        $voucher->total_paid_amount += VoucherDetail::where('voucher_id', $detail->voucher_id)
                            ->where('purchase_order_id', $detail->purchase_order_id)
                            ->sum('amount_to_pay');

                        $totalDetail = VoucherDetail::where('voucher_id', $detail->voucher_id)
                            ->where('purchase_order_id', $detail->purchase_order_id)
                            ->count();
                        $sumTotalDetail += $totalDetail;

                        if ($voucher->totalInvoice() < $sumTotalDetail + 1) {
                            $voucher->incomplete_invoice = 'Harap upload invoice ke- ' . ($sumTotalDetail + 1);
                        }
                    } else {
                        $voucher->incomplete_approval = 'Menunggu pembayaran';
                    }
                }
            }

        }

        return $dataCollection;
    }
}
