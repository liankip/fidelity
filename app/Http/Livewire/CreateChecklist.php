<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ChecklistService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;



class CreateChecklist extends Component
{
    public $formData = [];
    public $months;
    public $paramId;


    public function mount(Request $request)
{
    $this->months = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    $this->paramId = $request->query('id');

    if($this->paramId) {
        $serviceData = ChecklistService::where('service_id', intval($this->paramId))->get();
        if($serviceData->isNotEmpty()) {
            foreach ($serviceData as $data) {
                $this->formData[] = [
                    'vehicle_no' => $data->vehicle_no,
                    'vehicle_name' => $data->vehicle_name,
                    'service_type' => $data->service_type,
                    'checklist_months' => json_decode($data->monthly_service, true),
                ];
            }
        }
    } else {
        $this->formData[] = [
            'vehicle_no' => '',
            'vehicle_name' => '',
            'service_type' => '',
            'checklist_months' => array_fill_keys($this->months, false),
        ];
    }
}


    public function render()
    {
        return view('livewire.create-checklist');
    }

    public function addField()
    {
        $this->formData[] = [
            'vehicle_no' => '',
            'vehicle_name' => '',
            'service_type' => '',
            'checklist_months' => array_fill_keys($this->months, false),
        ];
    }

    public function removeField($index)
    {
        unset($this->formData[$index]);
        $this->formData = array_values($this->formData);
    }

    public function handleSubmit()
    {
        DB::beginTransaction();
    
        try {
            $message = '';

            if($this->paramId){
                $deleteData = ChecklistService::where('service_id', intval($this->paramId))->delete();
                $message = 'Service has been successfully updated';            
            } else {
                $message = 'New service has been created';
            }

            $latestId = ChecklistService::max('service_id');
            $serviceId = $latestId ? $latestId + 1 : 1;
            foreach ($this->formData as $data) {
                $checklistMonths = json_encode($data['checklist_months']);

                ChecklistService::create([
                    'service_id' => $serviceId,
                    'vehicle_no' =>strtoupper($data['vehicle_no']),
                    'vehicle_name' => $data['vehicle_name'],
                    'service_type' => $data['service_type'],
                    'monthly_service' => $checklistMonths,
                    'arranged_by' => auth()->user()->id
                ]);
            }
            DB::commit();
            return redirect()->route('service.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }

}
