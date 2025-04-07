<?php

namespace App\Http\Controllers\BOQ;

use App\Models\Project;
use App\Http\Controllers\Controller;
use App\Models\BOQSpreadsheet;

class BOQReviewController extends Controller
{
    public function __invoke($projectId, $boqId)
    {
        $project = Project::findOrFail($projectId);
        $boqSpreadsheet = BOQSpreadsheet::findOrFail($boqId);

        if ($boqSpreadsheet->status !== 'Reviewed') {
            abort(404);
        }

        $review = $boqSpreadsheet->review;
        $currentBOQ = $boqSpreadsheet->getJsonDataAsObjectArray();
        $reviewBOQ = collect($review->getJsonDataAsObjectArray());

        $results = [];

        foreach ($currentBOQ as $item) {
            $reviewItem = $reviewBOQ->where('item_id', $item->item_id)->first();

            $results[] = [
                'item_name' => [
                    'reviewed' => $reviewItem?->item_name,
                    'current' => $item->item_name
                ],
                'unit' => [
                    'reviewed' => $reviewItem?->unit,
                    'current' => $item->unit
                ],
                'price' => [
                    'reviewed' => $reviewItem?->price,
                    'current' => $item->price
                ],
                'quantity' => [
                    'reviewed' => $reviewItem?->quantity,
                    'current' => $item->quantity
                ],
                'shipping_cost' => [
                    'reviewed' => $reviewItem?->shipping_cost,
                    'current' => $item->shipping_cost
                ],
            ];
        }

        return view('masterdata.boqs.boq-review-result', compact('project', 'boqSpreadsheet', 'results', 'review'));
    }
}
