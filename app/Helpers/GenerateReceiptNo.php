<?php

namespace App\Helpers;

use App\Models\IdxReceipt;
use Illuminate\Support\Carbon;

class GenerateReceiptNo
{
    public static function get()
    {
        $idxs = IdxReceipt::first();
        $year = Carbon::now()->format('Y');
        $month = Carbon::now()->format('m');
        $map = [
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        ];

        $returnValueRoman = '';
        while ($month > 0) {
            foreach ($map as $roman => $int) {
                if ($month >= $int) {
                    $month -= $int;
                    $returnValueRoman .= $roman;
                    break;
                }
            }
        }

        $voucherNoIdx = $idxs->idx + 1;
        IdxReceipt::where("id", 1)->update([
            "idx" => $voucherNoIdx
        ]);
        $idNo = str_pad($idxs->idx, 3, "0", STR_PAD_LEFT);
        return $idNo . "/STN/" . env("NO_PREFIX") . "/" . $returnValueRoman . "/" . $year;
    }
}
