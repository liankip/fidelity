<?php

namespace App\Http\Livewire\Sku;

use App\Models\Item;
use App\Traits\NotificationManager;
use FontLib\Table\Type\name;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class InsertSku extends Component
{
    use NotificationManager;

    public $name;
    public $items;
    public $listeners = ['insert', 'export', 'save', 'autosave'];
    public $boqs = [];
    public $saving = false;
    public $jobName;

    public $reviewResult = [];

    public function insert($data)
    {
        $this->validate(
            [
                'name' => 'required|string',
            ],
            [
                'name.required' => 'Nama produk harus diisi',
                'name.string' => 'Nama produk harus berupa teks',
            ],
        );

        DB::beginTransaction();
        try {

            $filtered = collect(json_decode($data))->unique(0)->values()->toArray();

            if (count($filtered) == 0) {
                $this->emit('showAlert', ['message' => "BOQ Can't empty must be fill", 'type' => 'danger']);
                return;
            }

            $totalModalPrice = 0;

            foreach($filtered as $item) {
                $unitPrice = $item[2];
                $qty = $item[3];
                $totalModalPrice += $unitPrice * $qty;
            }

            \App\Models\Sku::insert([
                'name' => $this->name,
                'boq' => $data,
                'total_modal_price' => $totalModalPrice,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();
            $this->emit('productInserted');
            session()->flash('success', 'Product created successfully');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function mount()
    {
        // $this->items = Item::available()->select('id', 'name as label')->get();
        $this->items = Item::available()
        ->select('id', 'name', 'brand')
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'label' => $item->name . ' (Brand: ' . ($item->brand ?? '-') . ')', // Use '-' if brand is null
                'name' => $item->name,
                'brand' => $item->brand ?? '-', // Ensure brand is not null
            ];
        });
    }

    public function render()
    {
        return view('livewire.sku.insert-sku');
    }
}
