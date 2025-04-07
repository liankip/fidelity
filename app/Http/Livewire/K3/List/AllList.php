<?php

namespace App\Http\Livewire\K3\List;

use App\Models\Hiradc;
use App\Models\HiradcList;
use Livewire\Component;

class AllList extends Component
{
    public $hiradc;
    public $selectDelete = 0;

    public function mount(Hiradc $hiradc)
    {
        $this->hiradc = $hiradc;
    }
    public function deleteModal($id)
    {
        $this->selectDelete = $id;
    }

    public function closeModal()
    {
        $this->reset('selectDelete');
    }

    public function destroy(HiradcList $data)
    {
        HiradcList::find($data->id)->delete();
        $this->reset('selectDelete');
        session()->flash('success', 'Berhasil menghapus data');
        return redirect()->route('k3.hiradc.allList', $this->hiradc->id)
            ->with('success', 'Berhasil menghapus data');
    }

    public function render()
    {
        $hiradclists = HiradcList::where('hiradc_id', $this->hiradc->id)->get();
        return view('livewire.k3.list.all-list', [
            'hiradclists' => $hiradclists
        ]);
    }
}
