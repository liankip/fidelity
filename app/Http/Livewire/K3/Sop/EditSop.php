<?php

namespace App\Http\Livewire\K3\Sop;

use App\Models\Sop;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditSop extends Component
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


    public function mount(Sop $sop)
    {
        $this->edit_id = $sop->id;
        $this->name = $sop->name;
        $this->document_number = $sop->document_number;
        $this->file_upload = $sop->file_upload;
    }

    public function store(Sop $sop)
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'document_number' => $this->document_number,
        ];

        if ($this->new_file) {
            $data['file_upload'] = $this->new_file->store('sop', 'public');
        }

        $sop->update($data);

        return redirect('/k3/sop')->with('success', 'Berhasil mengedit dokumen');
    }

    public function render()
    {
        return view('livewire.k3.sop.edit-sop');
    }
}
