<?php

namespace App\Http\Controllers\Vendors;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\RequestForQuotation;
use App\Models\RequestForQuotationDetail;
use Illuminate\Http\Request;

class PriceQuotationController extends Controller
{
    public function __invoke()
    {
        $rfqs = RequestForQuotation::with('purchaseRequest', 'itemDetail', 'supplier')->orderByDesc('created_at')->get();

        $groupedRfqs = [];
        foreach ($rfqs->groupBy('purchase_request_id') as $purchaseRequestId => $rfqs) {

            $purchaseRequest = $rfqs->first()->purchaseRequest;

            $groupedRfqs[$purchaseRequestId] = [
                'pr_id' => $purchaseRequestId,
                'purchase_request' => $purchaseRequest,
                'rfqs' => $rfqs
            ];
        }

        return view('data_vendors.price-quotation', compact('groupedRfqs'));
    }

    public function show($id)
    {
        $rfq = RequestForQuotation::with('purchaseRequest', 'itemDetail', 'supplier')->findOrFail($id);

        return view('data_vendors.price-quotation-show', compact('rfq'));
    }

    public function quotation($itemId)
    {
        $item = Item::findOrFail($itemId);
        $quotations = RequestForQuotationDetail::with('requestForQuotation', 'requestForQuotation.supplier')->where('item_id', $itemId)->whereNotNull('price')->take(3)->get();

        return view('data_vendors.quotation', compact('item', 'quotations'));
    }
}
