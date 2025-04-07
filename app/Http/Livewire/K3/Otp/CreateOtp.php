<?php

namespace App\Http\Livewire\K3\Otp;

use App\Models\Otp;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateOtp extends Component
{
    use WithFileUploads;

    public $name;
    public $file_upload;

    protected $rules = [
        'name' => 'required',
        'file_upload' => 'required',
    ];

    public function store()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
        ];

        if ($this->file_upload) {
            $data['file_upload'] = $this->file_upload->store('otp', 'public');
        }

        Otp::create($data);

        return redirect('/k3/otp')->with('success', 'Berhasil menambahkan dokumen');
    }
    public function render()
    {
        return view('livewire.k3.otp.create-otp');
    }
}
