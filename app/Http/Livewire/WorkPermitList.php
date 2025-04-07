<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\WorkPermitModel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;

class WorkPermitList extends Component
{
    use WithFileUploads;

    public $paramID;
    public $specificPermit;
    public $editName;
    public $editFile;

    public $deleteId;
    
    public function setDelete($param){
        $this->deleteId = $param;
    }

    public function setParam($id)
    {
        $this->paramID = $id;
        $this->specificPermit = WorkPermitModel::where('id', intval($this->paramID))->first();
        $this->editName = $this->specificPermit->document_name;
    }

    public function render()
    {
        $dataPermit = WorkPermitModel::all();
        return view('livewire.work-permit-list', compact('dataPermit'));
    }

    public function handleSubmit(Request $request)
    {
        DB::beginTransaction();

        try {
            $documentName = $request->document_name;
            $fileUrl = $request->file_upload->store('work_permit', 'public');

            $workPermit = new WorkPermitModel();
            $workPermit->document_name = $documentName;
            $workPermit->file_upload = $fileUrl;
            $workPermit->updated_by = auth()->user()->id;

            $workPermit->save();

            DB::commit();
            return redirect()->route('permit.index')->with('success', 'Data has been uploaded');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('permit.index')->with('fail', 'Error Uploading');
        }
    }

    public function handleUpdate()
    {
        DB::beginTransaction();

        try {
            $dataPermit = WorkPermitModel::findOrFail($this->paramID);

            if ($dataPermit) {
                $dataPermit->document_name = $this->editName;

                if ($this->editFile === null) {
                    $fileUrl = $dataPermit->file_upload;
                } else {
                    $fileUrl = $this->editFile->store('work_permit', 'public');
                }

                $dataPermit->file_upload = $fileUrl;
                $dataPermit->updated_by = auth()->user()->id;
                $dataPermit->save();

                DB::commit();
                return redirect()->route('permit.index')->with('success', 'Data has been updated');
            } else {
                return redirect()->route('permit.index')->with('fail', 'Data not found');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->route('permit.index')->with('fail', 'Error updating');
        }
    }

    public function handleDelete($id)
    {
        DB::beginTransaction();

        try {
            $dataPermit = WorkPermitModel::where('id', intval($id))->delete();

            DB::commit();
            return redirect()->route('permit.index')->with('success', 'Data has been deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->route('permit.index')->with('fail', 'Error deleting');
        }
    }
}
