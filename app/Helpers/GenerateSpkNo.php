<?php

namespace App\Helpers;

use App\Models\IdxPurchaseOrder;
use App\Models\PurchaseOrder;
use Carbon\Carbon;

class GenerateSpkNo
{
    public static function get($project_code, $po_id = null)
    {
        $idxpo = IdxPurchaseOrder::orderBy('idx', 'desc')->first();

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

        $idx1 = $idxpo->idx;

        $idx = $idx1;
        IdxPurchaseOrder::where("id", 1)->update([
            "idx" => $idx + 1
        ]);
        $postring = '';
        $postatus = PurchaseOrder::where('id', $po_id)
            ->first()->po_type;

        if ($postatus == 'Supply'){
            $postring = 'P';
        } else {
            $postring = 'NP';
        };

        $pono = "8" . str_pad($idx, env("PO_DIGIT"), "0", STR_PAD_LEFT) . "/SPK/" . $postring . "/" . env("NO_PREFIX") . "-" . $project_code . "/" . $returnValueRoman . "/" . $year;
        return $pono;
    }
}
