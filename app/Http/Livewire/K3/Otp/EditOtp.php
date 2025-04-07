<?php

namespace App\Http\Livewire\K3\Otp;

use App\Models\Otp;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditOtp extends Component
{
    use WithFileUploads;

    public $edit_id;
    public $name;
    public $file_upload;
    public $new_file;

    protected $rules = [
        'name' => 'required',
        'new_file' => 'nullable',
    ];


    public function mount(Otp $otp)
    {
        $this->edit_id = $otp->id;
        $this->name = $otp->name;
        $this->file_upload = $otp->file_upload;
    }

    public function store(Otp $otp)
    {
        $this->validate();

        $data = [
            'name' => $this->name,
        ];

        if ($this->new_file) {
            $data['file_upload'] = $this->new_file->store('otp', 'public');
        }

        $otp->update($data);

        return redirect('/k3/otp')->with('success', 'Berhasil mengedit dokumen');
    }

    public function render()
    {
        return view('livewire.k3.otp.edit-otp');
    }
}
