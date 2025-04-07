<?php

namespace Database\Seeders;

use App\Helpers\GenerateVoucherNo;
use App\Models\PurchaseOrder;
use App\Models\Voucher;
use App\Models\VoucherDetail;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $purchaseOrders = PurchaseOrder::whereNotIn('status', PurchaseOrder::unprocessedStatus())->get();

        foreach ($purchaseOrders as $po) {
            $date = Carbon::parse($po->approved_at)->format('Y-m-d');
            $voucher = Voucher::whereDate('created_at', $date)->where('supplier_id', $po->supplier_id)->where('project_id', $po->project_id)->first();

            if (is_null($voucher)) {
                $voucherNo = GenerateVoucherNo::get();
                $voucher = Voucher::create([
                    'voucher_no' => $voucherNo,
                    'supplier_id' => $po->supplier_id,
                    'project_id' => $po->project_id,
                    'created_at' => $po->approved_at,
                ]);
            }

            VoucherDetail::create([
                'voucher_id' => $voucher->id,
                'purchase_order_id' => $po->id,
                'created_at' => $po->approved_at,
            ]);
        }
    }
}
