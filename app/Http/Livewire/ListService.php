<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ChecklistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ListService extends Component
{
    public $deleteId;
    
    public function setDelete($param){
        $this->deleteId = $param;
    }
    
    public function render()
    {
        $dataService = ChecklistService::all()->groupBy('service_id');
        return view('livewire.list-service', compact('dataService'));
    }

    public function handleDelete($paramId){
        DB::beginTransaction();
        try {
            ChecklistService::where('service_id', intval($paramId))->delete();
            DB::commit();
            return redirect()->route('service.index')->with('success', 'Service has been successfully deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public function handlePrint($paramId){
        $dataService = ChecklistService::where('service_id', intval($paramId))->get();
        $months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        return view('prints.print-service', compact('dataService','months'));
    }

    public function handleSubmit(Request $request)
    {
        DB::beginTransaction();

        try {
            $latestId = ChecklistService::max('service_id');
            $id_no = $latestId ? $latestId + 1 : 1;

            $fileUrl = $request->file_upload->store('files', 'public');

            $checklistService = new ChecklistService([
                'service_id' => $id_no,
                'vehicle_no' =>'-',
                'vehicle_name' => '-',
                'service_type' => '-',
                'monthly_service' => null,
                'file_upload' => $fileUrl,
                'arranged_by' => auth()->user()->id
            ]);
    
            $checklistService->save();
            DB::commit();
            return redirect()->route('service.index')->with('success', 'Data has been uploaded');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('service.index')->with('fail', 'Error Uploading');
        }
    }
}
