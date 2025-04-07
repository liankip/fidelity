<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Facades\DB;

class BOQUpdatePriceController extends Controller
{
    public function __invoke($projectId)
    {
        try {
            $project = Project::findOrFail($projectId);
            $maxRevision = $project->maxEditRevision();

            if ($maxRevision) {
                $boqs = $project->boqs_edit_not_approved()
                    ->where('revision', $maxRevision)
                    ->where('price_estimation', 0)
                    ->get();
            } else {
                $boqs = $project->boqs_not_approved()
                    ->where('price_estimation', 0)
                    ->get();
            }

            $historyPrices = DB::table('purchase_order_details')
                ->join('purchase_orders', 'purchase_orders.id', '=', 'purchase_order_details.purchase_order_id')
                ->where('purchase_orders.status', '=', 'Approved')
                ->whereIn('purchase_order_details.item_id', $boqs->pluck('item_id'))
                ->orderBy('purchase_order_details.price', 'asc')
                ->select('purchase_order_details.price', 'purchase_order_details.item_id')
                ->get();

            foreach ($boqs as $boq) {
                $boq->price_estimation = $historyPrices->where('item_id', $boq->item_id)->first()->price ?? 0;
                $boq->save();
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
