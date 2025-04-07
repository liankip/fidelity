<?php

namespace App\Traits;

use App\Models\HistoryPurchase;
use App\Models\PurchaseOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

trait HistoryPurchases
{
    public function pushHistory($purchaseOrder, $status): void
    {
        $history = new HistoryPurchase;
        $history->action_start = $purchaseOrder->status;
        $history->action_end = $status;
        $history->referensi = $purchaseOrder->po_no;
        $history->action_by = auth()->user()->id;
        $history->created_by = auth()->user()->id;
        $history->action_date = Carbon::now();
        $history->created_at = Carbon::now();
        $history->save();
    }
}
