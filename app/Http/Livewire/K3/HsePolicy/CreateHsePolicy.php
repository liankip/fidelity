<?php

namespace App\Http\Livewire\K3\HsePolicy;

use Livewire\Component;
use App\Models\HsePolicy;
use Livewire\WithFileUploads;

class CreateHsePolicy extends Component
{
    use WithFileUploads;

    public $name;
    public $jabatan;
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
            $data['file_upload'] = $this->file_upload->store('work_induction', 'public');
        }

        HsePolicy::create($data);

        return redirect('/k3/hse_policy')->with('success', 'Berhasil menambahkan dokumen');
    }

    public function render()
    {
        return view('livewire.k3.hse-policy.create-hse-policy');
    }
}
