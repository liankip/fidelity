<?php

namespace App\Http\Livewire\K3\Sop;

use App\Models\Sop;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateSop extends Component
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
            $data['file_upload'] = $this->file_upload->store('sop', 'public');
        }

        Sop::create($data);

        return redirect('/k3/sop')->with('success', 'Berhasil menambahkan dokumen');
    }

    public function render()
    {
        return view('livewire.k3.sop.create-sop');
    }
}
