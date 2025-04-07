<?php

namespace App\Http\Controllers;

use App\Models\Sku;
use App\Models\WorkOrder;

class PrintWorkOrder extends Controller
{
    public function index($id)
    {
        $skuData = Sku::all();
        $workOrderData = $this->modifyData(WorkOrder::find($id), $skuData);
        $workOrder = WorkOrder::find($id);

        return view('print-work-order', compact('workOrderData', 'workOrder'));
    }

    private function modifyData($workOrderData, $skuData)
    {
        $productData = [];

        foreach (json_decode($workOrderData->product, true) as $product) {
            $sku = $skuData->where('id', $product['product'])->first();

            $productData[] = [ 
                'id' => $product['product'],
                'name' => $sku->name,
                'quantity' => $product['qty'],
            ];
            
        }

        return collect($productData);
    }
}
