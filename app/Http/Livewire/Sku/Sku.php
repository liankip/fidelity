<?php

namespace App\Http\Livewire\Sku;

use App\Models\Inventory;
use App\Models\InventoryDetail;
use App\Models\Sku as ModelsSku;
use Livewire\Component;

class Sku extends Component
{
    public $search = '';

    protected $queryString = ['search'];

    public function delete($id)
    {
        \App\Models\Sku::findOrFail($id)->delete();

        return redirect()->route('sku.index')->with('success', 'SKU deleted successfully');
    }

    public function render()
    {
        $sku = $this->getData();

        return view('livewire.sku.sku', [
            'sku' => $sku,
        ]);
    }

    public function getData()
    {
        $query = ModelsSku::where('name', 'like', '%' . $this->search . '%');
        $inventoryData = Inventory::with(['item', 'details'])
        ->whereHas('details', function ($query) {
            $query->where('warehouse_type', 'READY GOODS');
        })
        ->get();

        $skuData = $query->paginate(10);

        $skuData->getCollection()->transform(function ($sku) use ($inventoryData) {
            $itemName = $sku->name;

            $isItemInInventory = $inventoryData->filter(function ($inventory) use ($itemName) {
                return optional($inventory->item)->name === $itemName;
            })->first();

            if ($isItemInInventory) {
                $sku->setAttribute('inventory_id', $isItemInInventory->id);
                $sku->setAttribute('available_qty', $isItemInInventory->stock);
            }

            $boqItems = $sku->boq;

            $totalItemsPrice = collect($boqItems)->reduce(function ($total, $item) {
                return $total + $item[2] * $item[3];
            });

            $sku->setAttribute('total_items_price', $totalItemsPrice);

            return $sku;
        });

        return $skuData;
    }

    public function checkRawMaterialStock($id)
    {
        $inventoryDetails = InventoryDetail::whereHas('inventory', function($query) use ($id) { $query->where('item_id', $id); })->where('warehouse_type', 'RAW MATERIALS')->first();

        return $inventoryDetails ? $inventoryDetails->stock : 0;
    }
}
