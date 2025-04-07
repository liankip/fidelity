<?php

namespace App\Http\Livewire\Inventory;

use Livewire\Component;
use App\Models\Inventory;

class UpdateStockModal extends Component
{
    public $inventoryId;
    public $selectedInventory;
    public $stock;
    public $notes;
    public $maxStock;

    public function mount($inventoryId)
    {
        $this->selectedInventory = Inventory::find($inventoryId);
        $this->stock = $this->selectedInventory->stock;
        $this->maxStock = $this->selectedInventory->stock;
    }

    public function render()
    {
        return view('livewire.inventory.update-stock-modal');
    }

    public function updateStock()
    {
        $maxStock = $this->selectedInventory->stock;
        $this->validate([
            'stock' => "required|numeric|min:0|max:$maxStock"
        ]);

        $this->selectedInventory->histories()->create([
            'type' => 'OUT',
            'stock_before' => $this->selectedInventory->stock,
            'stock_after' => $this->stock,
            'stock_change' => $this->selectedInventory->stock - $this->stock,
            'user_id' => auth()->id(),
            'notes' => $this->notes
        ]);

        $this->selectedInventory->update([
            'stock' => $this->stock
        ]);

        return redirect()->route('inventory.index')->with('success', 'Stock has been updated');
    }
}
