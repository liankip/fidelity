<?php

namespace App\Http\Livewire\K3\WorkInstruction;

use App\Models\WorkInstruction;
use Livewire\Component;

class AllWorkInstruction extends Component
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

    public function destroy(WorkInstruction $item)
    {
        WorkInstruction::find($item->id)->delete();
        $this->reset('selectDelete');
        return redirect()->route('k3.workInstruction')
            ->with('success', 'Berhasil menghapus data');

    }
    public function render()
    {
        return view('livewire.k3.work-instruction.all-work-instruction',[
            'work_instructions' => WorkInstruction::where('name', 'like', '%' . $this->search . '%')->get(),
        ]);
    }
}
