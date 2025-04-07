<?php

namespace App\Http\Livewire;

use App\Models\Item;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class BulkItems extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $Items = [];

    public function render()
    {
        $itemData = $this->getData();
        return view('livewire.bulk-items', [
            'itemData' => $itemData
        ]);
    }

    public function getData()
    {
        if ($this->search != '') {
            return Item::where('name', 'like', '%' . $this->search . '%')->paginate(15);
        }
        return Item::paginate(15);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function continueHandler()
    {
        try {
            $sessionData = Session::get('checkedItems');

            if (empty($sessionData)) {
                $itemsData = Item::whereIn('id', $this->Items)->get()->map(function ($item) {
                    $item->is_stock = true;
                    return $item;
                });

                Session::put('checkedItems', $itemsData);
                return redirect()->route('bulk-purchase-order.create');
            }

            return redirect()->route('bulk-purchase-order.create');
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
