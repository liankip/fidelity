<?php

namespace App\Http\Livewire\K3;

use App\Models\Hiradc;
use Livewire\Component;

class Allhiradc extends Component
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

    public function destroy(Hiradc $hiradc)
    {
        Hiradc::find($hiradc->id)->delete();
        $this->reset('selectDelete');
        return redirect()->route('k3.hiradc')
            ->with('success', 'Berhasil menghapus data');

    }

    public function render()
    {
        $hiradcs = Hiradc::where('name', 'like', '%' . $this->search . '%')->get();
        return view('livewire.k3.allhiradc',[
            'hiradcs' => $hiradcs,
        ]);
    }
}
