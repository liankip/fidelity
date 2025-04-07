<?php

namespace App\Helpers;

use App\Models\IdxMemo;
use App\Models\IdxPurchaseRequest;
use App\Models\PurchaseRequest;
use Carbon\Carbon;

class GeneratePrNo
{
    public static function get()
    {
        $idxs = IdxPurchaseRequest::first();
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

        $prnoidx = $idxs->idx + 1;
        IdxPurchaseRequest::where("id", 1)->update([
            "idx" => $prnoidx
        ]);
        $prno = $idxs->idx . "/PR/" . env("NO_PREFIX") . "/" . $returnValueRoman . "/" . $year;
        return $prno;
    }

    public static function newPR($paramId)
    {
        $prData = PurchaseRequest::where("id", $paramId)->first();
        // $checkLatestPrNumber = PurchaseRequest::where("project_id", $prData->project_id)->where('pr_no', '!=', null)->orderBy("id", "desc")->first()->pr_no;
        $prTask = $prData->partof;

        $totalPr = PurchaseRequest::where("project_id", $prData->project_id)->where('partof', $prTask)->where('pr_no', '!=', null)->orderBy("id", "desc")->get()->count();

        // $latestPrSegments = explode("-", $checkLatestPrNumber);
        $prTaskSegments = explode("/", $prTask);

        $prFormat = $prTaskSegments[0] . '/' . $prTaskSegments[2];
        $latestIndex = $totalPr + 1;

        // if ($latestPrSegments[0] == $prFormat) {
        //     $latestIndex = $latestPrSegments[1];
        //     $newIndex = str_pad($latestIndex + 1, 2, '0', STR_PAD_LEFT);
        //     return $prFormat . '-' . $newIndex;
        // }

        return $prFormat . '-' . $latestIndex;
    }

    public static function GenerateMemo()
    {
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

        $idxs = IdxMemo::first();

        if (!$idxs) {
            $idxs = IdxMemo::create([
                "idx" => 1
            ]);
        } else {
            $idxs->idx += 1;
            $idxs->save();
        }

        // Generate the memo number
        $prno = $idxs->idx . "/MEMO/" . env("NO_PREFIX") . "/" . $returnValueRoman . "/" . $year;

        return $prno;
    }
}
