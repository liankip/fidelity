<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\JSAModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class JSAView extends Component
{
    public $deleteId;
    
    public function setDelete($param){
        $this->deleteId = $param;
    }

    public function render()
    {
        $jsaData = JSAModel::all();
        return view('livewire.j-s-a-view', ['jsaData' => $jsaData]);
    }

    public function handleDelete($paramId)
    {
        DB::beginTransaction();

        try {
            
            $deleteData = JSAModel::where('id', intval($paramId))
            ->delete();
            
            DB::commit();

            return redirect()->route('jsa-view.index')->with('success', 'JSA has been deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public function printJSA($paramId)
    {
        $id = $paramId;
        $dataJsa = JSAModel::where('id', $id)->first();

        if ($dataJsa->file_upload !== null) {
            $filePath = Storage::disk('public')->path($dataJsa->file_upload);
            return response()->file($filePath, ['Content-Disposition' => 'inline']);
        }

        return view('prints.print-jsa', compact('dataJsa'));
    }
}
