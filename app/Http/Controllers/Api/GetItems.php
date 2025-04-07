<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GetItems extends Controller
{
    public function index(Request $request)
    {

        if ($request->search) {
            return Item::available()->where("name", "like", "%" . $request->search . "%")->take(3)->get();
        }else {
            return [];
        }
    }

    public function getAllItems(Request $request)
    {
        $items = Item::available()->with('item_unit')->get();
        $historyPrices = DB::table('purchase_order_details')
            ->join('purchase_orders', 'purchase_orders.id', '=', 'purchase_order_details.purchase_order_id')
            ->where('purchase_orders.status', '=', 'Approved')
            ->whereIn('purchase_order_details.item_id', $items->pluck('id'))
            ->orderBy('purchase_order_details.price', 'asc')
            ->select('purchase_order_details.price', 'purchase_order_details.item_id')
            ->get();
        $items->map(function ($item) use ($historyPrices) {
            $item->item_unit->map(function ($item_unit) {
                $item_unit->unit_name = $item_unit->unit->name;
                unset($item_unit->unit);
            });
            $price = $historyPrices->where('item_id', $item->id)->sortBy('price')->first();
            $item->price = is_null($price) ? 0 : $price->price;
        });

        return response()->json($items);
    }

    public function getItemPrice($itemId)
    {
        $prices = Item::historyPrices();
        $lowestPrice = $prices->where('item_id', $itemId)->sortBy('price')->first();
        return response()->json([
            'price' => $lowestPrice ? $lowestPrice->price : 0
        ]);
    }
}
