<?php

namespace Database\Seeders;

use App\Models\InventoryDetail;
use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewWarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $newWarehouses = ['Gudang Medan', 'Gudang Jakarta'];

        foreach ($newWarehouses as $warehouse) {
            Warehouse::firstOrCreate(['name' => $warehouse]);
        }

        DB::transaction(function () {
            // Step 1: Assign warehouse_type to 'Gudang Medan' if null
            InventoryDetail::whereNull('project_id')
                ->whereNull('warehouse_type')
                ->orWhere('warehouse_type', 'RAW MATERIALS')
                ->update(['warehouse_type' => 'Gudang Medan']);
        
            // Step 2: Group and merge duplicate inventory_id stock values
            $duplicateStocks = InventoryDetail::select('inventory_id', 'warehouse_type', DB::raw('SUM(stock) as total_stock'))
                ->where('warehouse_type', 'Gudang Medan')
                ->groupBy('inventory_id', 'warehouse_type')
                ->havingRaw('COUNT(*) > 1')
                ->get();
        
            foreach ($duplicateStocks as $stockData) {
                // Step 3: Update one record with the total stock
                InventoryDetail::where('inventory_id', $stockData->inventory_id)
                    ->where('warehouse_type', 'Gudang Medan')
                    ->first()
                    ->update(['stock' => $stockData->total_stock]);
        
                // Step 4: Delete the duplicate records except one
                InventoryDetail::where('inventory_id', $stockData->inventory_id)
                    ->where('warehouse_type', 'Gudang Medan')
                    ->skip(1) // Keep one record, delete the rest
                    ->delete();
            }
        });

        foreach ($newWarehouses as $warehouse) {
            $exists = InventoryDetail::where('warehouse_type', $warehouse)->exists();

            if (!$exists) {
                InventoryDetail::create([
                    'warehouse_type' => $warehouse,
                    'stock' => 0,
                ]);
            }
        }
    }
}
