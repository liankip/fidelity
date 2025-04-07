<?php


namespace App\Helpers;

use App\Models\IdxVoucher;
use Carbon\Carbon;

class GenerateVoucherNo
{
    public static function set()
    {
        $idxs = IdxVoucher::first();
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

        $idNo = str_pad($idxs->idx, 3, "0", STR_PAD_LEFT);
        return $idNo . "/BBK/" . env("NO_PREFIX") . "/" . $returnValueRoman . "/" . $year;
    }

    public static function get()
    {
        $idxs = IdxVoucher::first();
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
        IdxVoucher::where("id", 1)->update([
            "idx" => $voucherNoIdx
        ]);
        $idNo = str_pad($idxs->idx, 3, "0", STR_PAD_LEFT);
        return $idNo . "/BBK/" . env("NO_PREFIX") . "/" . $returnValueRoman . "/" . $year;
    }
}
