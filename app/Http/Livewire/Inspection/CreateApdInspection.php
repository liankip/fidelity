<?php

namespace App\Http\Livewire\Inspection;

use App\Models\ApdChecklistInspection;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateApdInspection extends Component
{
    use WithFileUploads;

    public $unit, $work, $name, $date, $note, $upload;

    public function render()
    {
        $dataUser = User::role('k3')->where('active', 1)->orderBy('name')->get();
        return view('livewire.inspection.create-apd-inspection', [
            'dataUser' => $dataUser
        ]);
    }

    public function handleSubmit()
    {
        $this->validate([
            'unit' => 'required',
            'work' => 'required',
            'name' => 'required',
            'date' => 'required',
            'upload' => 'required',
            'note' => 'nullable',
        ], [], [
            'unit' => 'Unit',
            'work' => 'Pekerjaan',
            'name' => 'Nama',
            'date' => 'Tanggal',
            'upload' => 'Upload',
            'note' => 'Catatan',
        ]);

        $this->upload->store('inspection/apd', 'public');

        $data = [
            'unit' => $this->unit,
            'work' => $this->work,
            'inspection_officer' => $this->name,
            'date' => $this->date,
            'attachment' => $this->upload->hashName(),
            'note' => $this->note,
        ];

        ApdChecklistInspection::create($data);

        return redirect()->route('k3.apd-inspection')->with('success', 'Berhasil menambahkan dokument');
    }
}
