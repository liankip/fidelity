<?php

namespace App\Http\Controllers\Prints;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Http\Controllers\Controller;

class PrintReceipt extends Controller
{
    public function __invoke($id)
    {
        $po_data = PurchaseOrder::with('podetail')->where('id', $id)->first();

        if (is_null($po_data)) {
            return abort(404);
        }

        return view('prints.print-receipt', compact('po_data'));
    }
}
