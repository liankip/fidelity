<?php

namespace App\Http\Livewire\K3\WorkInduction;

use App\Models\WorkInduction;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateWorkInduction extends Component
{
    use WithFileUploads;

    public $name;
    public $jabatan;
    public $file_upload;

    protected $rules = [
        'name' => 'required',
        'jabatan' => 'required',
        'file_upload' => 'required',
    ];

    public function store()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'jabatan' => $this->jabatan,
        ];

        if ($this->file_upload) {
            $data['file_upload'] = $this->file_upload->store('work_induction', 'public');
        }

        WorkInduction::create($data);

        return redirect('/k3/safety_induction')->with('success', 'Berhasil menambahkan dokumen');
    }
    
    public function render()
    {
        return view('livewire.k3.work-induction.create-work-induction');
    }
}
