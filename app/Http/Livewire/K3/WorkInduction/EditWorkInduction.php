<?php

namespace App\Http\Livewire\K3\WorkInduction;

use App\Models\WorkInduction;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditWorkInduction extends Component
{
    use WithFileUploads;

    public $edit_id;
    public $name;
    public $jabatan;
    public $file_upload;
    public $new_file;

    protected $rules = [
        'name' => 'required',
        'jabatan' => 'required',
        'new_file' => 'nullable',
    ];


    public function mount(WorkInduction $work)
    {
        $this->edit_id = $work->id;
        $this->name = $work->name;
        $this->jabatan = $work->jabatan;
        $this->file_upload = $work->file_upload;
    }

    public function store(WorkInduction $work)
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'jabatan' => $this->jabatan,
        ];

        if ($this->new_file) {
            $data['file_upload'] = $this->new_file->store('work_induction', 'public');
        }

        $work->update($data);

        return redirect('/k3/safety_induction')->with('success', 'Berhasil mengedit dokumen');
    }
    public function render()
    {
        return view('livewire.k3.work-induction.edit-work-induction');
    }
}
