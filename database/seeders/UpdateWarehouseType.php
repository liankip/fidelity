<?php

namespace Database\Seeders;

use App\Models\InventoryDetail;
use App\Models\PurchaseOrderDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateWarehouseType extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $poRawMaterialItemIds = PurchaseOrderDetail::where('is_raw_materials', 1)
            ->where('percent_complete', '>', 0)
            ->pluck('item_id')
            ->toArray();

        $matchingItems = InventoryDetail::where('warehouse_type', 'Gudang Medan')
            ->whereHas('inventory', function ($query) use ($poRawMaterialItemIds) {
                $query->whereIn('item_id', $poRawMaterialItemIds);
            })
            ->get();

        foreach ($matchingItems as $item) {
            $item->update(['warehouse_type' => 'RAW MATERIALS']);
        }
    }
}
