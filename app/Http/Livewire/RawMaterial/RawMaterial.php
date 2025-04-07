<?php

namespace App\Http\Livewire\RawMaterial;

use App\Models\Inventory;
use App\Models\Item;
use App\Models\Sku;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class RawMaterial extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['saveHandler'];

    public $search = '';
    public $Items = [];

    public function render()
    {
        $itemData = $this->getData();

        return view('livewire.raw-material.raw-material', [
            'itemData' => $itemData,
        ]);
    }

    public function getData()
    {
        $sku = Sku::all();
        $result = [];

        $inventoryData = Inventory::with(['details' => function ($query) {
            $query->where('warehouse_type', 'RAW MATERIALS');
        }])->get();

        $calculatedStock = [];
        foreach ($sku as $s) {
            $data = collect($s->boq)->map(function ($boq) use ($inventoryData, $s) {
                $query = Item::where('id', $boq[0]);

                if ($this->search) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                }

                $item = $query->first();

                if (!$item) {
                    return null;
                }

                if (!isset($calculatedStock[$boq[0]])) {
                    $calculatedStock[$boq[0]] = $inventoryData->where('item_id', $boq[0])
                        ->flatMap->details
                        ->sum('stock');
                }

                return [
                    'id' => $boq[0],
                    'item_name' => $item->name,
                    'stock' => $calculatedStock[$boq[0]], 
                    'product_name' => $s->name
                ];
            })->filter();

            $result = array_merge($result, $data->toArray());
        }

        $grouped = collect($result)
            ->groupBy('item_name')
            ->map(function ($items, $key)  {
                return [
                    'product_name' => $items->pluck('product_name')->toArray(),
                    'item_name' => $key,
                    'ids' => $items->pluck('id')->all(),
                    'stock' => $items->first()['stock'], 
                ];
            })->values();

        return $grouped->isEmpty() ? [] : $grouped;
    }


    public function saveHandler($selectedItems)
    {
        try {
            $sessionData = Session::get('checkedItems');

            if (!empty($sessionData)) {
                Session::forget('checkedItems');
            }

            $itemsData = Item::whereIn('id', $selectedItems)->get()->map(function ($item) {
                $item->is_raw_materials = true;
                return $item;
            });

            Session::put('checkedItems', $itemsData);

            $this->dispatchBrowserEvent('dataSaved');

        } catch (\Exception $e) {
            dd($e);
        }
    }
}
