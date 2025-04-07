<?php

namespace App\Http\Livewire\K3;

use App\Models\Hiradc;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class Createhiradc extends Component
{
    use WithFileUploads;

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
        'fileUpload' => 'nullable|file|mimes:pdf'
    ];

    public function store()
    {
        $this->validate();

        if ($this->fileUpload) {
            $fileUrl = $this->fileUpload->store('files', 'public');
        } else {
            $fileUrl = null;
        }

        Hiradc::create([
            'name' => $this->name,
            'document_number' => $this->document_number,
            'dept' => $this->dept,
            'work_unit' => $this->work_unit,
            'area' => $this->area,
            'file_upload'=> $fileUrl,
            'effective_date' => $this->effective_date,
            'revision_number' => $this->revision_number,
            'reviewed_date' => $this->reviewed_date,
            'next_reviewed' => $this->next_reviewed,
            'created_by' => Auth::user()->id,
        ]);

        return redirect('/k3/hiradc')->with('success', 'Berhasil menambahkan dokumen');

    }
    public function render()
    {
        return view('livewire.k3.createhiradc');
    }
}
