<?php

namespace App\Http\Controllers;

use App\Models\RequestForQuotation;
use Illuminate\Http\Request;

class RequestForQuotationController extends Controller
{
    public function __invoke($id)
    {
        $rfq = RequestForQuotation::with('supplier', 'itemDetail')->findOrFail($id);
        return view('request_for_quotation.index', compact('rfq'));
    }

    public function store(Request $request, $id)
    {
        $rfq = RequestForQuotation::with('itemDetail')->findOrFail($id);
        $itemPrices = $request->price;
        $itemIds = $request->item_id;
        $notes = $request->notes;

        foreach ($itemIds as $key => $itemId) {
            $itemDetail = $rfq->itemDetail->where('item_id', $itemId)->first();

            if ($itemDetail) {
                $itemDetail->update([
                    'price' => $itemPrices[$key],
                    'notes' => $notes[$key],
                ]);
            }
        }

        $rfq->update([
            'is_submitted' => true,
        ]);

        return redirect()->route('request-for-quotation', $rfq->id);
    }
}
