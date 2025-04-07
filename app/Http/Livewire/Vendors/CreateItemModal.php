<?php

namespace App\Http\Livewire\Vendors;

use App\Helpers\ItemType;
use App\Models\CategoryItem;
use App\Models\Unit;
use App\Models\VendorItem;
use Livewire\Component;

class CreateItemModal extends Component
{
    public $categories;
    public $units;
    public $item_name, $category_id, $price, $unit_id, $brand, $type, $selectedItem;
    public $isEdit = false;
    public $itemId = null;

    public $listeners = ['editItem'];

    public function mount()
    {
        $this->categories = CategoryItem::all();
        $this->category_id = $this->categories->first()->id;
        $this->type = ItemType::get()['inv'];

        $this->units = Unit::query()->select('id', 'name')->get();
    }

    public function render()
    {
        return view('livewire.vendors.create-item-modal');
    }

    public function editItem($id)
    {
        $item = VendorItem::find($id);

        $this->itemId = $id;
        $this->item_name = $item->item_name;
        $this->category_id = $item->category_id;
        $this->price = $item->price;
        $this->unit_id = $item->unit_id;
        $this->brand = $item->brand;
        $this->type = $item->type;
        $this->isEdit = true;

        $this->dispatchBrowserEvent('openModal');
    }

    public function closeModal()
    {
        $this->reset(['item_name', 'category_id', 'price', 'unit_id', 'brand', 'type']);
    }

    public function submit()
    {
        $this->validate([
            'item_name' => 'required',
            'category_id' => 'required',
            'price' => 'required',
            'unit_id' => 'required',
            'brand' => 'required',
            'type' => 'required'
        ]);

        $data = [
            'vendor_id' => auth()->user()->vendorRegistrant?->id,
            'item_name' => $this->item_name,
            'category_id' => $this->category_id,
            'price' => $this->price,
            'unit_id' => $this->unit_id,
            'brand' => $this->brand,
            'type' => $this->type
        ];

        if ($this->isEdit) {
            VendorItem::find($this->itemId)->update($data);
            $this->isEdit = false;
            return redirect()->route('vendors.items')->with('success', 'Item berhasil diubah');
        }
        VendorItem::create($data);

        return redirect()->route('vendors.items')->with('success', 'Item berhasil ditambahkan');

    }

}
