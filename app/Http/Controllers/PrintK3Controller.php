<?php

namespace App\Http\Controllers;

use App\Models\Hiradc;
use App\Models\HiradcList;
use App\Models\Ibpr;
use App\Models\IbprList;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PrintK3Controller extends Controller
{
    public function print(Hiradc $hiradc)
    {
        // $pdf = Pdf::loadView('documents.k3-hiradc')->setPaper('a4', 'landscape');
        // return $pdf->stream();
        $lists = HiradcList::where('hiradc_id', $hiradc->id)->get();
        return view('documents.k3-hiradc', compact('hiradc', 'lists'));
    }

    public function printIbpr(Ibpr $ibpr)
    {
        // $pdf = Pdf::loadView('documents.k3-hiradc')->setPaper('a4', 'landscape');
        // return $pdf->stream();
        $lists = IbprList::where('ibpr_id', $ibpr->id)->get();
        return view('documents.k3-ibpr', compact('ibpr', 'lists'));
    }
}
