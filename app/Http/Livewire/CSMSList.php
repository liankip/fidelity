<?php

namespace App\Http\Livewire;

use App\Models\CSMSModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class CSMSList extends Component
{
    use WithFileUploads;

    public $paramID;
    public $specificCSMS;
    public $editName;
    public $editFile;
    public $docName;
    public $fileUpload;

    public $deleteId;

    public function setDelete($param)
    {
        $this->deleteId = $param;
    }

    public function setParam($id)
    {
        $this->paramID = $id;
        $this->specificCSMS = CSMSModel::where('id', intval($this->paramID))->first();
        $this->editName = $this->specificCSMS->document_name;
    }

    public function handleSubmit()
    {
        DB::beginTransaction();

        try {
            $documentName = $this->docName;
            $fileUrl = $this->fileUpload->store('csms', 'public');

            $csms = new CSMSModel();
            $csms->document_name = $documentName;
            $csms->file_upload = $fileUrl;
            $csms->updated_by = auth()->user()->id;

            $csms->save();

            DB::commit();
            return redirect()->route('csms.index')->with('success', 'Data has been uploaded');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('csms.index')->with('fail', 'Error Uploading');
        }
    }

    public function handleUpdate()
    {
        DB::beginTransaction();

        try {
            $dataCSMS = CSMSModel::findOrFail($this->paramID);

            if ($dataCSMS) {
                $dataCSMS->document_name = $this->editName;

                if ($this->editFile === null) {
                    $fileUrl = $dataCSMS->file_upload;
                } else {
                    $fileUrl = $this->editFile->store('csms', 'public');
                }

                $dataCSMS->file_upload = $fileUrl;
                $dataCSMS->updated_by = auth()->user()->id;
                $dataCSMS->save();

                DB::commit();
                return redirect()->route('csms.index')->with('success', 'Data has been updated');
            } else {
                return redirect()->route('csms.index')->with('fail', 'Data not found');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->route('csms.index')->with('fail', 'Error updating');
        }
    }

    public function handleDelete($id)
    {
        DB::beginTransaction();

        try {
            CSMSModel::where('id', intval($id))->delete();

            DB::commit();
            return redirect()->route('csms.index')->with('success', 'Data has been deleted');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('csms.index')->with('fail', 'Error deleting');
        }
    }

    public function render()
    {
        $dataCSMS = CSMSModel::all();
        return view('livewire.c-s-m-s-list', compact('dataCSMS'));
    }
}
