<?php

namespace App\Http\Controllers;
use App\Models\Supplier;
use Illuminate\Http\Request;

class BlacklistSupplierController extends Controller
{
    //
    public function blacklistsupplier(Request $request, $id)
    {
        Supplier::where('id', $request->id)->update(['blacklist' => 1]);
        return redirect()->back();
    }
}
