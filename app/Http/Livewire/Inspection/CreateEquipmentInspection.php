<?php

namespace App\Http\Livewire\Inspection;

use App\Models\EquipmentInspection;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateEquipmentInspection extends Component
{
    use WithFileUploads;

    public $unit, $work, $name, $date, $note, $upload;
    public $equipment = [];

    public function mount()
    {
        $this->equipment[] = '';
    }

    public function render()
    {
        $dataUser = User::role('k3')->where('active', 1)->orderBy('name')->get();
        return view('livewire.inspection.create-equipment-inspection', [
            'dataUser' => $dataUser
        ]);
    }

    public function handleSubmit()
    {
        $this->validate([
            'unit' => 'required',
            'work' => 'required',
            'equipment' => 'required',
            'name' => 'required',
            'date' => 'required',
            'upload' => 'required',
            'note' => 'nullable',
        ], [], [
            'unit' => 'Unit',
            'work' => 'Pekerjaan',
            'equipment' => 'Peralatan',
            'name' => 'Nama',
            'date' => 'Tanggal',
            'upload' => 'Upload',
            'note' => 'Catatan',
        ]);

        $this->upload->store('inspection/equipment', 'public');

        $data = [
            'unit' => $this->unit,
            'work' => $this->work,
            'equipment_list' => json_encode($this->equipment),
            'inspection_officer' => $this->name,
            'date' => $this->date,
            'attachment' => $this->upload->hashName(),
            'note' => $this->note,
        ];

        EquipmentInspection::create($data);

        return redirect()->route('k3.equipment-inspection')->with('success', 'Berhasil menambahkan dokument');
    }

    public function addEquipmentField(){
        $this->equipment[] = '';
    }

    public function removeField($index)
    {
        unset($this->equipment[$index]);
        $this->equipment = array_values($this->equipment);
    }
    
}
