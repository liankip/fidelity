<?php

namespace App\Http\Livewire\K3\WorkInstruction;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\WorkInstruction;

class EditWorkInstruction extends Component
{

    use WithFileUploads;

    public $edit_id;
    public $name;
    public $document_number;
    public $file_upload;
    public $new_file;

    protected $rules = [
        'name' => 'required',
        'document_number' => 'required',
        'new_file' => 'nullable',
    ];


    public function mount(WorkInstruction $work)
    {
        $this->edit_id = $work->id;
        $this->name = $work->name;
        $this->document_number = $work->document_number;
        $this->file_upload = $work->file_upload;
    }

    public function store(WorkInstruction $work)
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'document_number' => $this->document_number,
        ];

        if ($this->new_file) {
            $data['file_upload'] = $this->new_file->store('work_instruction', 'public');
        }

        $work->update($data);

        return redirect('/k3/work_instruction')->with('success', 'Berhasil mengedit dokumen');
    }
    
    public function render()
    {
        return view('livewire.k3.work-instruction.edit-work-instruction');
    }
}
