<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Overtime;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use DB;

class OvertimeEdit extends Component
{
    public $overtimeId;


    public function mount($id){
        $this->overtimeId = $id;
    }

    public function render()
    {
        $data = Overtime::with('user')->where('overtime_id',$this->overtimeId)->get();
        $userData = User::whereNotNull('status')->get();
        $projectData = Project::all();

        return view('livewire.overtime-edit', ['overtimeData' => $data, 'projectData' => $projectData, 'userData' => $userData]);
    }

    public function editOvertime(Request $request){
        $data = $request->all();

        try {
            DB::beginTransaction();
            $overtimeId = $data['overtime_id'];

            Overtime::where('overtime_id',$overtimeId)->delete();

            foreach ($data['user_id'] as $userId) {
                Overtime::create([
                    'overtime_id' => $overtimeId,
                    'user_id' => $userId,
                    'project_id' => $data['project_id'],
                    'overtime_date' => $data['overtime_date'],
                    'start_time' => $data['start_time'],
                    'finish_time' => $data['finish_time'],
                    'overtime_report' => $data['overtime_report'],
                    'est_cost' => intval(str_replace(',', '', $data['est-cost'])),
                    'assigned_by' => $data['assigned_by'],
                    'status' => 'New',
                    'updated_by' => auth()->user()->id,
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
        
        return redirect()->route('overtime-request.index');
    }
}

