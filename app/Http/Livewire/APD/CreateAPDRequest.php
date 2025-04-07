<?php

namespace App\Http\Livewire\APD;

use App\Models\ApdRequest;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateAPDRequest extends Component
{
    use WithFileUploads;

    public $name, $date, $description, $upload;

    public function render()
    {
        $dataUser = User::where('active', 1)->orderBy('name')->get();
        return view('livewire.a-p-d.create-a-p-d-request', [
            'dataUser' => $dataUser
        ]);
    }

    public function handleSubmit()
    {
        $this->validate([
            'name' => 'required',
            'date' => 'required',
            'upload' => 'required',
            'description' => 'nullable',
        ], [], [
            'name' => 'Nama',
            'date' => 'Tanggal',
            'upload' => 'Upload',
            'description' => 'Deskripsi',
        ]);

        $this->upload->store('apd/request', 'public');

        $data = [
            'user_id' => $this->name,
            'date' => $this->date,
            'attachment' => $this->upload->hashName(),
            'description' => $this->description,
        ];

        ApdRequest::create($data);

        return redirect()->route('k3.apd')->with('success', 'Berhasil menambahkan dokument');
    }
}
