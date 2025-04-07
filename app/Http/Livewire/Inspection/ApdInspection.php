<?php

namespace App\Http\Livewire\Inspection;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\ApdChecklistInspection;

class ApdInspection extends Component
{
    public $search, $data_inspection, $delete_id;

    public function render()
    {
        $this->data_inspection = ApdChecklistInspection::where('work', 'like', '%' . $this->search . '%')->get();
        return view('livewire.inspection.apd-inspection');
    }

    public function setDelete($id)
    {
        $data = ApdChecklistInspection::find($id);
        $this->delete_id = $data->id;
    }

    public function handleDelete($paramId)
    {
        DB::beginTransaction();

        try {
            ApdChecklistInspection::where('id', intval($paramId))->delete();

            DB::commit();

            return redirect()->route('k3.apd-inspection')->with('success', 'APD Checklist Inspection has been deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }
}
