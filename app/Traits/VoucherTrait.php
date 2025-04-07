<?php

namespace App\Traits;

use App\Helpers\GenerateVoucherNo;
use App\Models\PurchaseOrder;
use App\Models\Voucher;
use App\Models\VoucherDetail;
use Carbon\Carbon;

trait VoucherTrait
{
    public function insertVoucher(PurchaseOrder $po)
    {
        $voucher = Voucher::whereDate('created_at', Carbon::today())->where('supplier_id', $po->supplier_id)->where('project_id', $po->project_id)->first();

        if (is_null($voucher)) {
            $voucherNo = GenerateVoucherNo::get();
            $voucher = Voucher::create([
                'voucher_no' => $voucherNo,
                'supplier_id' => $po->supplier_id,
                'project_id' => $po->project_id,
            ]);
        }

        VoucherDetail::create([
            'voucher_id' => $voucher->id,
            'purchase_order_id' => $po->id,
        ]);
    }
}
