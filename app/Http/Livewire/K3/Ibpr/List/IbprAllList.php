<?php

namespace App\Http\Livewire\K3\Ibpr\List;

use App\Models\Ibpr;
use Livewire\Component;
use App\Models\IbprList;

class IbprAllList extends Component
{
    public $ibpr;
    public $selectDelete;

    public function mount(Ibpr $ibpr)
    {
        $this->ibpr = $ibpr;
    }

    public function deleteModal($id)
    {
        $this->selectDelete = $id;
    }

    public function closeModal()
    {
        $this->reset('selectDelete');
    }

    public function destroy(IbprList $data)
    {
        IbprList::find($data->id)->delete();
        $this->reset('selectDelete');
        session()->flash('success', 'Berhasil menghapus data');
        return redirect()->route('k3.ibpr.ibprList', $this->ibpr->id)
            ->with('success', 'Berhasil menghapus data');
    }

    public function render()
    {
        $ibprLists = IbprList::where('ibpr_id', $this->ibpr->id)->get();
        return view('livewire.k3.ibpr.list.ibpr-all-list',[
            'ibprLists' => $ibprLists
        ]);
    }
}
