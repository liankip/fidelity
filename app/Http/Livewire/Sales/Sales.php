<?php

namespace App\Http\Livewire\Sales;

use App\Models\Inventory;
use App\Models\InventoryDetail;
use App\Models\InventoryHistory;
use App\Models\Item;
use App\Models\Sales as ModelsSales;
use App\Models\Sku;
use Illuminate\Support\Facades\DB;

use Livewire\Component;

class Sales extends Component
{
    public $search = '';

    protected $queryString = ['search'];

    public function render()
    {
        $sales = $this->getDataWithAvailability();


        return view('livewire.sales.sales', [
            'sales' => $sales
        ]);
    }

    public function getDataWithAvailability()
    {
        $query = ModelsSales::whereHas('customer', function ($customerQuery) {
            $customerQuery->where('name', 'like', '%' . $this->search . '%');
        })->orderBy('created_at', 'DESC');

        $salesData = $query->paginate(10);

        // Add availability data and check if all products are available
        $salesData->getCollection()->transform(function ($sales) {
            $products = json_decode($sales->product, true);
            $availabilityStatus = [];
            $isAllAvailable = true;
            $totalPrice = 0;
            $totalModal = 0;

            foreach ($products as $product) {
                $availability = $this->getProductAvailability($product['product'], $product['qty']);
                $availabilityStatus[] = $availability;

                // Check availability for each product
                if (!$availability['is_available']) {
                    $isAllAvailable = false;
                }

                $totalPrice += $availability['total'];
                $totalModal += $availability['total_items_price'] * $product['qty'];
            }

            // Add availability status and overall check to each sales record
            $sales->setAttribute('availability', $availabilityStatus);
            $sales->setAttribute('is_available', $isAllAvailable);
            $sales->setAttribute('total_price', $totalPrice);
            $sales->setAttribute('total_modal', $totalModal);

            return $sales;
        });

        return $salesData;
    }

    private function getProductAvailability($productId, $productQty)
    {
        $productData = Sku::where('id', $productId)->first();
        $productName = $productData->name;
        $itemId = Item::where('name', $productName)->first()?->id;

        $readyGoodsInventory = InventoryDetail::with('inventory')
            ->where('warehouse_type', 'READY GOODS')
            ->whereHas('inventory', function ($query) use ($itemId) {
                $query->where('item_id', $itemId);
            })
            ->first();

        $isAvailable = $readyGoodsInventory && $readyGoodsInventory->stock >= $productQty;
        $total = $productQty * $productData->msrp_price;

        $boqItems = $productData->boq;

        $totalItemsPrice = collect($boqItems)->reduce(function ($total, $item) {
            return $total + $item[2] * $item[3];
        });

        return [
            'product_id' => $productId,
            'status' => $isAvailable ? 'Available' : 'Out of Stock',
            'stock' => $readyGoodsInventory?->stock ?? 0,
            'is_available' => $isAvailable,
            'total' => $total,
            'total_items_price' => $totalItemsPrice
        ];
    }

    public function completeSales($salesId)
    {
        DB::beginTransaction();

        try {
            $sales = ModelsSales::findOrFail($salesId);
            $sales->status = 'COMPLETED';

            $products = json_decode($sales->product, true);

            $readyGoodsData = InventoryDetail::with('inventory')
                ->where('warehouse_type', 'READY GOODS')
                ->get()
                ->keyBy('id');

            $skuData = Sku::pluck('name', 'id');
            $itemData = Item::pluck('id', 'name');

            foreach ($products as $product) {
                $skuName = $skuData[$product['product']] ?? null;
                $itemId = $itemData[$skuName] ?? null;

                if (!$skuName || !$itemId) {
                    throw new \Exception("Invalid SKU or Item mapping.");
                }

                $readyGoodsInventory = $readyGoodsData->firstWhere('inventory.item_id', $itemId);

                if (!$readyGoodsInventory) {
                    throw new \Exception("No stock found for Item ID: {$itemId}");
                }

                if ($readyGoodsInventory->stock < $product['qty']) {
                    throw new \Exception("Insufficient stock for Item ID: {$itemId}");
                }

                // Save inventory history
                InventoryHistory::create([
                    'inventory_detail_id' => $readyGoodsInventory->id,
                    'stock_before' => $readyGoodsInventory->stock,
                    'stock_after' => $readyGoodsInventory->stock - $product['qty'],
                    'stock_change' => $product['qty'],
                    'user_id' => auth()->id(),
                    'sales_id' => $sales->id,
                    'type' => 'OUT',
                ]);

                // Decrease stock safely
                $readyGoodsInventory->decrement('stock', $product['qty']);

                Inventory::where('id', $readyGoodsInventory->inventory_id)->decrement('stock', $product['qty']);
            }

            $sales->save();
            DB::commit();

            return redirect()->route('sales.index')->with('success', 'Sales completed successfully.');
        } catch (\Exception $e) {
            throw new \Exception("Failed to complete sales: " . $e->getMessage());
        }
    }

}
