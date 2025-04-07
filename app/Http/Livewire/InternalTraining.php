<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\InternalTraining as InternalTrainingModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class InternalTraining extends Component
{
    public $deleteId;
    
    public function setDelete($param){
        $this->deleteId = $param;
    }
    
    public function render()
    {
        $dataTraining = InternalTrainingModel::all()->groupBy('id_no');
        return view('livewire.internal-list', compact('dataTraining'));
    }

    public function handleDelete($paramId)
    {
        DB::beginTransaction();

        try {
            
            $deleteData = InternalTrainingModel::where('id_no', intval($paramId))
            ->delete();
            
            DB::commit();
            return redirect()->route('internal.index')->with('success', 'Data has been deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public function handlePrint($id)
    {
        $dataTraining = InternalTrainingModel::where('id_no', intval($id))->get();
        $userData = InternalTrainingModel::with('user')->where('id_no', intval($id))->first()->user;
        return view('prints.print-internal-training', compact('dataTraining', 'userData'));
    }

    public function handleSubmit(Request $request)
    {
        DB::beginTransaction();

        try {
            $docNumber = $request->no_doc;
            $latestId = InternalTrainingModel::max('id_no');
            $id_no = $latestId ? $latestId + 1 : 1;

            $existDocNum = InternalTrainingModel::where('no_doc', $docNumber)->first();
            
            if($existDocNum){
                return redirect()->route('internal.index')->with('fail', 'Document Number Already Exist');
            }

            $fileUrl = $request->file_upload->store('files', 'public');

            $internalTraining = new InternalTrainingModel([
                'id_no' => $id_no,
                'no_doc' => $request->no_doc,
                'aspect_name' => '-',
                'risk_effect' => '-',
                'program_plan' => '-',
                'plan' => '-',
                'realization' => '-',
                'notes' => '-',
                'file_upload' => $fileUrl,
                'revision' => 0,
                'arranged_by' => auth()->user()->id,
            ]);
    
            $internalTraining->save();
            DB::commit();
            return redirect()->route('internal.index')->with('success', 'Data has been uploaded');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('internal.index')->with('fail', 'Error Uploading');
        }
    }
}
