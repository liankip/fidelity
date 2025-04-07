<?php

namespace App\Http\Livewire\K3\Sop;

use App\Models\Sop;
use Livewire\Component;

class AllSop extends Component
{
    public $search;
    public $selectDelete;

    public function deleteModal($id){
        $this->selectDelete = $id;
    }

    public function closeModal()
    {
        $this->reset('selectDelete');
    }

    public function destroy(Sop $item)
    {
        Sop::find($item->id)->delete();
        $this->reset('selectDelete');
        return redirect()->route('k3.sop')
            ->with('success', 'Berhasil menghapus data');

    }

    public function render()
    {
        return view('livewire.k3.sop.all-sop',[
            'sops' => Sop::where('name', 'like', '%' . $this->search . '%')->get(),
        ]);
    }
}
