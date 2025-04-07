<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class Items extends Controller
{
    public function index(Request $request)
    {
        $items = Item::query()
            ->select('id', 'name');

        if ($search = $request->input('search')) {
            $items->where('name', 'like', "%$search%");
        }

        if ($sort = $request->input('sort')) {
            $items->orderBy('name', $sort);
        }

        $perPage = 9;
        $page = $request->input('page', 1);
        $total = $items->count();

        $result = $items->offset(($page - 1) * $perPage)->limit($perPage)->get();

        return response()->json([
            'data' => $result,
            'total' => $total,
            'page' => $page,
            'last_page' => ceil($total / $perPage),
        ], 200);
    }

    public function dropdown(Request $request)
    {
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);

        $items = Item::available()
            ->select('id', 'name as label')
            ->skip($offset)
            ->take($limit)
            ->get();

        return response()->json($items, 200);
    }
}
