<?php

namespace App\Http\Controllers\Inventory;

use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InventoryController extends Controller
{
    public function history($id)
    {
        $inventory = Inventory::find($id);
        $histories = $inventory->histories()->with('user')->latest()->get();

        return view('inventory.inventory-history', compact('inventory', 'histories'));
    }
}
