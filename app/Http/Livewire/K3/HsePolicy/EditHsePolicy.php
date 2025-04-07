<?php

namespace App\Http\Livewire\K3\HsePolicy;

use Livewire\Component;
use App\Models\HsePolicy;
use Livewire\WithFileUploads;

class EditHsePolicy extends Component
{
    use WithFileUploads;

    public $edit_id;
    public $name;
    public $file_upload;
    public $new_file;

    protected $rules = [
        'name' => 'required',
        'new_file' => 'nullable',
    ];


    public function mount(HsePolicy $policy)
    {
        $this->edit_id = $policy->id;
        $this->name = $policy->name;
        $this->file_upload = $policy->file_upload;
    }

    public function store(HsePolicy $policy)
    {
        $this->validate();

        $data = [
            'name' => $this->name,
        ];

        if ($this->new_file) {
            $data['file_upload'] = $this->new_file->store('work_induction', 'public');
        }

        $policy->update($data);

        return redirect('/k3/hse_policy')->with('success', 'Berhasil mengedit dokumen');
    }

    public function render()
    {
        return view('livewire.k3.hse-policy.edit-hse-policy');
    }
}
