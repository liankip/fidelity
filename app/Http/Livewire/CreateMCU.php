<?php

namespace App\Http\Livewire;

use App\Models\Mcu;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateMCU extends Component
{
    use WithFileUploads;

    public $name, $date, $upload;

    public function render()
    {
        $dataUser = User::where('active', 1)->orderBy('name')->get();
        return view('livewire.create-m-c-u', [
            'dataUser' => $dataUser
        ]);
    }

    public function handleSubmit()
    {
        $this->validate([
            'name' => 'required',
            'date' => 'required',
            'upload' => 'required',
        ], [], [
            'name' => 'Nama',
            'date' => 'Tanggal',
            'upload' => 'Upload'
        ]);

        $this->upload->store('mcu', 'public');

        $data = [
            'user_id' => $this->name,
            'date' => $this->date,
            'attachment' => $this->upload->hashName(),
        ];

        Mcu::create($data);

        return redirect()->route('k3.mcu')->with('success', 'Berhasil menambahkan dokument');
    }
}
