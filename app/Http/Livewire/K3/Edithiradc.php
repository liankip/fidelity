<?php

namespace App\Http\Livewire\K3;

use App\Models\Hiradc;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edithiradc extends Component
{
    use WithFileUploads;

    public $edit_id;
    public $name;
    public $dept;
    public $work_unit;
    public $area;
    public $document_number;
    public $effective_date;
    public $revision_number;
    public $reviewed_date;
    public $next_reviewed;
    public $fileUpload;
    public $new_file;

    protected $rules = [
        'name' => 'required',
        'document_number' => 'required',
        'dept' => 'required',
        'work_unit' => 'required',
        'area' => 'required',
        'effective_date' => 'nullable',
        'revision_number' => 'nullable',
        'reviewed_date' => 'nullable',
        'next_reviewed' => 'nullable',
        'new_file' => 'nullable|file|mimes:pdf'
    ];

    public function mount(Hiradc $hiradc)
    {
        $this->edit_id = $hiradc->id;
        $this->name = $hiradc->name;
        $this->dept = $hiradc->dept;
        $this->work_unit = $hiradc->work_unit;
        $this->area = $hiradc->area;
        $this->document_number = $hiradc->document_number;
        $this->effective_date = $hiradc->effective_date;
        $this->revision_number = $hiradc->revision_number;
        $this->reviewed_date = $hiradc->reviewed_date;
        $this->next_reviewed = $hiradc->next_reviewed;
        $this->fileUpload = $hiradc->file_upload;
    }

    public function update(Hiradc $hiradc)
    {
        $this->validate();

        if ($this->new_file) {
            $fileUrl = $this->new_file->store('files', 'public');
        } else {
            $fileUrl = $hiradc->file_upload;
        }

        $hiradc->update([
            'name' => $this->name,
            'document_number' => $this->document_number,
            'dept' => $this->dept,
            'work_unit' => $this->work_unit,
            'area' => $this->area,
            'file_upload' => $fileUrl,
            'effective_date' => $this->effective_date,
            'revision_number' => $this->revision_number,
            'reviewed_date' => $this->reviewed_date,
            'next_reviewed' => $this->next_reviewed,
        ]);
        
        return redirect('/k3/hiradc')->with('success', 'Berhasil mengedit dokumen');
    }

    public function render()
    {
        return view('livewire.k3.edithiradc');
    }
}
