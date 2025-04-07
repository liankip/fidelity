<?php

namespace App\Http\Controllers;

use App\Models\HistoryPayment;
use App\Models\HistoryPurchase;
use App\Models\HistoryInventory;
use App\Models\HistoryMasterData;

use Illuminate\Http\Request;

class HistoryActionController extends Controller
{
    //
    public function indexPayment()
    {
        $history = HistoryPayment::with("user")->get();
        return view('history_actions.payment', compact('history'));
    }
    public function indexPurchase()
    {
        return redirect(route('log-purchase'));
    }
    public function indexInventory()
    {
        $history = HistoryInventory::with("user")->get();
        return view('history_actions.inventory', compact('history'));
    }
    public function indexMasterData()
    {
        $history = HistoryMasterData::with("user")->get();
        return view('history_actions.masterdata', compact('history'));
    }
}
