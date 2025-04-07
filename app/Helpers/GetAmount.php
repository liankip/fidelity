<?php

namespace App\Helpers;

class GetAmount
{
    public $po;

    public static function get($purchaseorder): array
    {
        $totalamount = 0;
        foreach ($purchaseorder->podetail as $sajs) {
            $totalamount += $sajs->amount;
        }

        $ongkir = 0;

        if ($purchaseorder->deliver_status == 2) {
            $ongkir = $purchaseorder->tarif_ds;
        }

        $ppn = 0;

        if ($purchaseorder->podetail->first()->tax_status == 2) {
            $ppn = 0;
        } else {
            if ($purchaseorder->tax_custom) {
                $ppn = $purchaseorder->tax_custom;
            } else {
                $ppn = round($totalamount * 0.11);
            }
        }

        if ($purchaseorder->tax_custom) {
            $ppn = $purchaseorder->tax_custom;
        }

        if ($purchaseorder->tax_custom) {
            $ppn = $purchaseorder->tax_custom;
        }

        // if ($purchaseorder->total_amount && $purchaseorder->total_amount != "0") {
        //     $total = $purchaseorder->total_amount;
        // } else {
        //     $total = round($totalamount + $ongkir + $ppn);
        // }
        $total = round($totalamount + $ongkir + $ppn);

        return ["total" => $total, "ongkir" => $ongkir, "ppn" => $ppn];
    }
}
