<?php

namespace App\Http\Livewire\Vendors;

use App\Helpers\ItemType;
use App\Models\CategoryItem;
use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\VendorItem;

class VendorItems extends Component
{
    public $items;
    public $isEdit = false;
    public $selectedItem;

    public function mount()
    {
        $this->items = VendorItem::where('vendor_id', auth()->user()->vendorRegistrant?->id)->get();
    }

    public function render()
    {
        return view('livewire.vendors.vendor-items')->layout('components.vendors.app');
    }

    public function delete($id)
    {
        VendorItem::find($id)->delete();
        return redirect()->route('vendors.items')->with('success', 'Item berhasil dihapus');
    }
}
