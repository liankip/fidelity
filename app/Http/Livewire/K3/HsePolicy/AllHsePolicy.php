<?php

namespace App\Http\Livewire\K3\HsePolicy;

use App\Models\HsePolicy;
use Livewire\Component;

class AllHsePolicy extends Component
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

    public function destroy(HsePolicy $item)
    {
        HsePolicy::find($item->id)->delete();
        $this->reset('selectDelete');
        return redirect()->route('k3.hsePolicy')
            ->with('success', 'Berhasil menghapus data');
    }

    public function render()
    {
        return view('livewire.k3.hse-policy.all-hse-policy',[
            'hse_policies' => HsePolicy::where('name', 'like', '%' . $this->search . '%')->get(),
        ]);
    }
}
