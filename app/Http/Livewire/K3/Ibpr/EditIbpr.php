<?php

namespace App\Http\Livewire\K3\Ibpr;

use App\Models\Ibpr;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditIbpr extends Component
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
    public $page;
    public $file_upload;
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
        'page' => 'nullable',
        'file_upload' => 'nullable',
        'new_file' => 'nullable',
    ];

    public function mount(Ibpr $ibpr)
    {
        $this->edit_id = $ibpr->id;
        $this->name = $ibpr->name;
        $this->dept = $ibpr->dept;
        $this->work_unit = $ibpr->work_unit;
        $this->area = $ibpr->area;
        $this->document_number = $ibpr->document_number;
        $this->effective_date = $ibpr->effective_date;
        $this->revision_number = $ibpr->revision_number;
        $this->reviewed_date = $ibpr->reviewed_date;
        $this->next_reviewed = $ibpr->next_reviewed;
        $this->page = $ibpr->page;
        $this->file_upload = $ibpr->file_upload;
    }

    public function update(Ibpr $ibpr)
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
        ];

        if ($this->new_file) {
            $ibprData['file_upload'] = $this->new_file->store('ibpr', 'public');
        }

        $ibpr->update($ibprData);
        
        return redirect('/k3/ibpr')->with('success', 'Berhasil mengedit dokumen');
    }

    public function render()
    {
        return view('livewire.k3.ibpr.edit-ibpr');
    }
}
