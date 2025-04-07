<?php

namespace App\Http\Livewire\K3\Ibpr;

use App\Models\Ibpr;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class CreateIbpr extends Component
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
    public $page;
    public $file_upload;

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
        'page' => 'nullable',
        'file_upload' => 'nullable',
    ];

    public function store()
    {
        $this->validate();

        $ibprData = [
            'name' => $this->name,
            'document_number' => $this->document_number,
            'dept' => $this->dept,
            'work_unit' => $this->work_unit,
            'area' => $this->area,
            'effective_date' => $this->effective_date,
            'revision_number' => $this->revision_number,
            'reviewed_date' => $this->reviewed_date,
            'next_reviewed' => $this->next_reviewed,
            'page' => $this->page,
            'created_by' => Auth::user()->id,
        ];
        
        if ($this->file_upload) {
            $ibprData['file_upload'] = $this->file_upload->store('ibpr', 'public');
        }

        Ibpr::create($ibprData);

        return redirect('/k3/ibpr')->with('success', 'Berhasil menambahkan dokumen');
    }
    public function render()
    {
        return view('livewire.k3.ibpr.create-ibpr');
    }
}
