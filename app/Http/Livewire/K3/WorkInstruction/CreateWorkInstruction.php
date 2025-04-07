<?php

namespace App\Http\Livewire\K3\WorkInstruction;

use App\Models\WorkInstruction;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateWorkInstruction extends Component
{
    use WithFileUploads;

    public $name;
    public $document_number;
    public $file_upload;

    protected $rules = [
        'name' => 'required',
        'document_number' => 'required',
        'file_upload' => 'required',
    ];

    public function store()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'document_number' => $this->document_number,
        ];

        if ($this->file_upload) {
            $data['file_upload'] = $this->file_upload->store('work_instruction', 'public');
        }

        WorkInstruction::create($data);

        return redirect('/k3/work_instruction')->with('success', 'Berhasil menambahkan dokumen');
    }

    public function render()
    {
        return view('livewire.k3.work-instruction.create-work-instruction');
    }
}
