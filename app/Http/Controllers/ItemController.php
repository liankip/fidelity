<?php

namespace App\Http\Controllers;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function itemList()
    {
        $products = Item::all();

        return view('itemlists', compact('itemlists'));
    }
}
