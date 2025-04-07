<?php

namespace App\Http\Livewire\K3\Ibpr\List;

use App\Models\Ibpr;
use App\Models\IbprList;
use Livewire\Component;

class EditIbprList extends Component
{
    public $ibpr;
    public $ibprlist;

    public $activity;
    public $cases = [];

    public function mount(Ibpr $ibpr, IbprList $list)
    {
        $this->ibpr = $ibpr;

        $this->ibprlist = $list;
        $this->activity = $list->activity;
        $this->cases = json_decode($list->data, true);
    }

    public function addCase()
    {
        $newCase = [
            'id' => uniqid(), // Generate a unique identifier for each case
        ];
    
        $this->cases[] = $newCase;
        // No need to dump here, as Livewire will handle the reactivity
    }

    public function removeCase($key)
    {
        if (isset($this->cases[$key])) {
            unset($this->cases[$key]);
            $this->cases = array_values($this->cases); // Re-index the array
        }

    }

    public function update()
    {
        $this->ibprlist->update([
            'activity' => $this->activity,
            'data' => json_encode($this->cases),
        ]);

        return redirect('/k3/ibpr/' . $this->ibpr->id . '/list')->with('success', 'Berhasil mengedit item');
    }
    

    public function render()
    {
        return view('livewire.k3.ibpr.list.edit-ibpr-list');
    }
}
