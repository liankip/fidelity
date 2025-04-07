<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\InternalTraining;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class CreateInternalTraining extends Component
{
    public $fields = [];
    public $isSubmitting = false;
    public $paramId;
    public $message;

    public function mount(Request $request)
    {
        $this->paramId = $request->query('id');

        if ($this->paramId) {
            $dataTraining = InternalTraining::where('id_no', intval($this->paramId))->get();
            foreach ($dataTraining as $training) {
                $this->fields[] = [
                    'aspect_name' => $training->aspect_name,
                    'risk_effect' => $training->risk_effect,
                    'program_plan' => $training->program_plan,
                    'plan' => $training->plan,
                    'realization' => $training->realization,
                    'notes' => $training->notes
                ];
            }
        } else {
            $this->fields[] = [
                'aspect_name' => '',
                'risk_effect' => '',
                'program_plan' => '',
                'plan' => '',
                'realization' => '',
                'notes' => ''
            ];
        }
    }

    public function addField()
    {
        $this->fields[] = [
            'aspect_name' => '',
            'risk_effect' => '',
            'program_plan' => '',
            'plan' => '',
            'realization' => '',
            'notes' => ''
        ];
    }

    public function removeField($index)
    {
        unset($this->fields[$index]);
        $this->fields = array_values($this->fields);
    }

    public function render()
    {
        return view('livewire.create-internal-training');
    }

    public function handleSubmit()
    {
        $this->isSubmitting = true;
        DB::beginTransaction();

        try {

            if ($this->paramId) {
                $maxRevision = InternalTraining::where('id_no', intval($this->paramId))->max('revision');
                InternalTraining::where('id_no', intval($this->paramId))->delete();
    
                foreach ($this->fields as $field) {
                    $training = new InternalTraining([
                        'id_no' => intval($this->paramId), 
                        'aspect_name' => $field['aspect_name'],
                        'risk_effect' => $field['risk_effect'],
                        'program_plan' => $field['program_plan'],
                        'plan' => $field['plan'],
                        'realization' => $field['realization'],
                        'notes' => $field['notes'],
                        'revision' => $maxRevision + 1,
                        'arranged_by' => auth()->user()->id
                    ]);
                    $training->save();
                }
                $this->message = 'Internal Training has been updated';
            } else {
                $latestId = InternalTraining::max('id_no');
                $id_no = $latestId ? $latestId + 1 : 1;
                foreach ($this->fields as $field) {
                    $training = new InternalTraining([
                        'id_no' => $id_no,
                        'aspect_name' => $field['aspect_name'],
                        'risk_effect' => $field['risk_effect'],
                        'program_plan' => $field['program_plan'],
                        'plan' => $field['plan'],
                        'realization' => $field['realization'],
                        'notes' => $field['notes']
                    ]);
                    $training->revision = 0;
                    $training->arranged_by = auth()->user()->id;
                    $training->save();
                    $this->message = 'New Internal Training has been created';
                }
            }
            DB::commit();
            $this->isSubmitting = false;
            return redirect()->route('internal.index')->with('success', $this->message);
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }
}
