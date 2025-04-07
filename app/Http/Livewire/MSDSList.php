<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\MSDSModel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;


class MSDSList extends Component
{
    use WithFileUploads;

    public $paramID;
    public $specificMSDS;
    public $editName;
    public $editFile;

    public $deleteId;
    
    public function setDelete($param){
        $this->deleteId = $param;
    }

    public function setParam($id)
    {
        $this->paramID = $id;
        $this->specificMSDS = MSDSModel::where('id', intval($this->paramID))->first();
        $this->editName = $this->specificMSDS->document_name;
    }

    public function render()
    {
        $dataMSDS = MSDSModel::all();
        return view('livewire.m-s-d-s-list', compact('dataMSDS'));
    }

    public function handleSubmit(Request $request)
    {
        DB::beginTransaction();

        try {
            $documentName = $request->document_name;
            $fileUrl = $request->file_upload->store('msds', 'public');

            $msds = new MSDSModel();
            $msds->document_name = $documentName;
            $msds->file_upload = $fileUrl;
            $msds->updated_by = auth()->user()->id;

            $msds->save();

            DB::commit();
            return redirect()->route('msds.index')->with('success', 'Data has been uploaded');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('msds.index')->with('fail', 'Error Uploading');
        }
    }

    public function handleUpdate()
    {
        DB::beginTransaction();

        try {
            $dataMSDS = MSDSModel::findOrFail($this->paramID);

            if ($dataMSDS) {
                $dataMSDS->document_name = $this->editName;

                if ($this->editFile === null) {
                    $fileUrl = $dataMSDS->file_upload;
                } else {
                    $fileUrl = $this->editFile->store('msds', 'public');
                }

                $dataMSDS->file_upload = $fileUrl;
                $dataMSDS->updated_by = auth()->user()->id;
                $dataMSDS->save();

                DB::commit();
                return redirect()->route('msds.index')->with('success', 'Data has been updated');
            } else {
                return redirect()->route('msds.index')->with('fail', 'Data not found');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->route('msds.index')->with('fail', 'Error updating');
        }
    }

    public function handleDelete($id)
    {
        DB::beginTransaction();

        try {
            $dataMSDS = MSDSModel::where('id', intval($id))->delete();

            DB::commit();
            return redirect()->route('msds.index')->with('success', 'Data has been deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->route('msds.index')->with('fail', 'Error deleting');
        }
    }
}
