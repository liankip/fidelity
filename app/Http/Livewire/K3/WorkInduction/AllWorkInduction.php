<?php

namespace App\Http\Livewire\K3\WorkInduction;

use App\Models\WorkInduction;
use Livewire\Component;

class AllWorkInduction extends Component
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

    public function destroy(WorkInduction $item)
    {
        WorkInduction::find($item->id)->delete();
        $this->reset('selectDelete');
        return redirect()->route('k3.workInduction')
            ->with('success', 'Berhasil menghapus data');
    }

    public function render()
    {
        return view('livewire.k3.work-induction.all-work-induction',[
            'work_inductions' => WorkInduction::where('name', 'like', '%' . $this->search . '%')->get(),
        ]);
    }
}
