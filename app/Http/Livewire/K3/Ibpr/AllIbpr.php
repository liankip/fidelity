<?php

namespace App\Http\Livewire\K3\Ibpr;

use App\Models\Ibpr;
use Livewire\Component;

class AllIbpr extends Component
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

    public function destroy(Ibpr $ibpr)
    {
        Ibpr::find($ibpr->id)->delete();
        $this->reset('selectDelete');
        return redirect()->route('k3.ibpr')
            ->with('success', 'Berhasil menghapus data');

    }

    public function render()
    {
        $ibprs = Ibpr::where('name', 'like', '%' . $this->search . '%')->get();
        return view('livewire.k3.ibpr.all-ibpr',[
            'ibprs' => $ibprs
        ]);
    }
}
