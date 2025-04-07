<?php

namespace App\Http\Controllers\Vendors;

use App\Http\Controllers\Controller;
use App\Models\VendorItem;
use Illuminate\Http\Request;

class VendorItemController extends Controller
{
    public function __invoke() {
        return view('data_vendors.items');
    }

    public function itemList()
    {
        $items = VendorItem::approved()->get();
        return view('data_vendors.item-list', compact('items'));
    }

}
