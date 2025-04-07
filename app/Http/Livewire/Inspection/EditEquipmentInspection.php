<?php

namespace App\Http\Livewire\Inspection;

use App\Models\EquipmentInspection;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditEquipmentInspection extends Component
{
    use WithFileUploads;

    public $unit, $work, $name, $date, $note, $upload, $paramId, $existingUpload;
    public $equipment = [];

    public function mount($id)
    {
        $this->paramId = $id;

        $dataInspection = EquipmentInspection::where('id', $this->paramId)->first();

        $this->unit = $dataInspection->unit;
        $this->work = $dataInspection->work;
        $this->name = $dataInspection->inspection_officer;
        $this->date = $dataInspection->date;
        $this->existingUpload = $dataInspection->attachment;
        $this->note = $dataInspection->note;

        if ($dataInspection->equipment_list) {
            $equipmentArray = json_decode($dataInspection->equipment_list, true);
            if (!empty($equipmentArray)) {
                $this->equipment = $equipmentArray;
            } else {
                $this->equipment[] = '';
            }
        } else {
            $this->equipment[] = '';
        }
    }

    public function render()
    {
        $dataEquipInspection = EquipmentInspection::where('id', $this->paramId)->first();
        $dataUser = User::role('k3')->where('active', 1)->orderBy('name')->get();
        return view(
            'livewire.inspection.edit-equipment-inspection',
            [
                'dataInspection' => $dataEquipInspection,
                'dataUser' => $dataUser
            ]
        );
    }

    public function handleSubmit()
    {
        DB::beginTransaction();
        try {
            $rules = [
                'unit' => 'required',
                'work' => 'required',
                'equipment' => 'required',
                'name' => 'required',
                'date' => 'required',
                'note' => 'nullable',
            ];
            

            if ($this->upload !== null) {
                $fileUrl = $this->upload;
                $fileUrl->store('inspection/equipment', 'public');
            } else {
                $fileUrl = $this->existingUpload;
            }


            $this->validate($rules, [], [
                'unit' => 'Unit',
                'work' => 'Pekerjaan',
                'equipment' => 'Peralatan',
                'name' => 'Nama',
                'date' => 'Tanggal',
                'note' => 'Catatan',
            ]);

            $data = [
                'unit' => $this->unit,
                'work' => $this->work,
                'equipment_list' => json_encode($this->equipment),
                'inspection_officer' => $this->name,
                'date' => $this->date,
                'attachment' => $fileUrl,
                'note' => $this->note,
            ];

            EquipmentInspection::where('id', $this->paramId)->update($data);

            DB::commit();
            return redirect()->route('k3.equipment-inspection')->with('success', 'Berhasil mengupdate dokument');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public function addEquipmentField()
    {
        $this->equipment[] = '';
    }

    public function removeField($index)
    {
        unset($this->equipment[$index]);
        $this->equipment = array_values($this->equipment);
    }
}
