<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\MeetingFormModel;
use Illuminate\Support\Facades\DB;


class ListMeeting extends Component
{
    public $deleteId;
    
    public function setDelete($param){
        $this->deleteId = $param;
    }
    
    public function render()
    {
        $dataMeeting = MeetingFormModel::all();
        return view('livewire.list-meeting', compact('dataMeeting'));
    }

    public function handleDelete($paramId)
    {
        DB::beginTransaction();
        try {
            $dataMeeting = MeetingFormModel::where('id', intval($paramId))->delete();
            DB::commit();
            return redirect()->route('meeting.index')->with('success', 'Data has been deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('meeting.index')->with('fail', 'Error deleting data.');
        }
    }

    public function handlePrint($paramId){
        $dataMeeting = MeetingFormModel::where('id', intval($paramId))->first();
        return view('prints.print-meeting', compact('dataMeeting'));
    }
}
