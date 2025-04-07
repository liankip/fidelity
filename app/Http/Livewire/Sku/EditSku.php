<?php

namespace App\Http\Livewire\Sku;

use App\Models\Item;
use App\Models\Sku;
use Livewire\Component;

class EditSku extends Component
{
    public $items;
    public $name;
    // public $grosir_price;
    // public $distributor_price;
    // public $msrp_price;
    public $boqs = [];
    public $currentBOQ = [];
    public $listeners = ['edit', 'export', 'save', 'autosave'];

    public function mount(Sku $sku)
    {
        $this->items = Item::available()->select('id', 'name as label')->get();

        $boqs = Sku::where('id', $sku->id)->first();
        $this->name = $boqs->name;
        // $this->grosir_price = $boqs->grosir_price;
        // $this->distributor_price = $boqs->distributor_price;
        // $this->msrp_price = $boqs->msrp_price;

        $this->currentBOQ = $boqs;

        if ($boqs) {
            if (is_string($boqs->boq)) {
                $this->boqs = json_decode($boqs->boq, true);
            } elseif (is_array($boqs->boq)) {
                $this->boqs = $boqs->boq;
            } else {
                $this->boqs = [];
            }
        }
    }

    public function edit($data)
    {
        $this->validate(
            [
                'name' => 'required|string',
                // 'grosir_price' => 'required|integer',
                // 'distributor_price' => 'required|integer',
                // 'msrp_price' => 'required|integer',
            ],
            [
                'name.required' => 'Nama produk harus diisi',
                'name.string' => 'Nama produk harus berupa teks',
                // 'grosir_price.required' => 'Harga grosir harus diisi',
                // 'grosir_price.integer' => 'Harga grosir harus berupa angka',
                // 'distributor_price.required' => 'Harga distributor harus diisi',
                // 'distributor_price.integer' => 'Harga distributor harus berupa angka',
                // 'msrp_price.required' => 'Harga MSRP harus diisi',
                // 'msrp_price.integer' => 'Harga MSRP harus berupa angka',
            ],
        );

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

        Sku::where('id', $this->currentBOQ->id)->update([
            'name' => $this->name,
            'boq' => $data,
            'total_modal_price' => $totalModalPrice,
            // 'grosir_price' => (int) $this->grosir_price,
            // 'distributor_price' => (int) $this->distributor_price,
            // 'msrp_price' => (int) $this->msrp_price,
            'updated_at' => now(),
        ]);

        $this->emit('productEdit');
        session()->flash('success', 'Product updated successfully');
    }

    public function render()
    {
        return view('livewire.sku.edit-sku');
    }
}
