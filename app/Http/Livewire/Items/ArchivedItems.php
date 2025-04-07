<?php

namespace App\Http\Livewire\Items;

use Livewire\Component;
use App\Models\Item;


class ArchivedItems extends Component
{
    public $searchcompact = "";

    public function render()
    {
        if ($this->searchcompact) {
            $items = Item::removed()
                ->where(function ($query) {
                    $query->where('item_code', 'like', '%' . $this->searchcompact . '%')
                        ->orWhere('name', 'like', '%' . $this->searchcompact . '%')
                        ->orWhere('type', 'like', '%' . $this->searchcompact . '%')
                        ->orWhere('unit', 'like', '%' . $this->searchcompact . '%')
                        ->orWhere('item_code', 'like', '%' . $this->searchcompact . '%');
                })
                ->paginate(8);
            $items->appends(['search' => $this->searchcompact]);
        } else {
            $items = Item::removed()->orderBy('id', 'desc')->paginate(10);
        }

        return view('livewire.items.archived-items', compact('items'));
    }

    public function restore($id)
    {
        $item = Item::find($id);
        $item->update([
            'is_disabled' => 0,
        ]);

        return redirect()->route('items.index', ['tab' => 'removed'])->with('success', 'Item restored successfully');
    }
}
