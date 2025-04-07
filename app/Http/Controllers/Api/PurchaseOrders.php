<?php

namespace App\Http\Controllers\Api;

use App\Models\PurchaseOrder;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;


class PurchaseOrders
{
    public function getSelect(Request $request)
    {
        $query = PurchaseOrder::getProcessed()
            ->select('id', 'po_no as value');

        if (!$request->has('p')) {
            $query->take(10);
        }

        $results = $query->when(
            $request->q,
            fn(Builder $q) => $q->where('po_no', 'like', "%{$request->q}%")
        )->get();

        return $results;
    }
}
