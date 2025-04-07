<?php

namespace App\Http\Livewire\K3\Otp;

use App\Models\Otp;
use Livewire\Component;

class AllOtp extends Component
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

    public function destroy(Otp $item)
    {
        Otp::find($item->id)->delete();
        $this->reset('selectDelete');
        return redirect()->route('k3.otp')
            ->with('success', 'Berhasil menghapus data');
    }

    public function render()
    {
        return view('livewire.k3.otp.all-otp',[
            'otps' => Otp::where('name', 'like', '%' . $this->search . '%')->get(),
        ]);
    }
}
