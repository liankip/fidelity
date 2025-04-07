<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Project;
use App\Models\Overtime;
use Illuminate\Http\Request;
use DB;

class OvertimeForm extends Component
{
    public function render()
    {
        $userData = User::whereNotNull('status')->get();
        $projectData = Project::all();
        return view('livewire.overtime-form', compact('userData','projectData'));
    }

    public function create(Request $request){
        $data = $request->all();
        
        try {
            DB::beginTransaction();
            $overtimeMax = Overtime::max('overtime_id');
            $overtimeId = $overtimeMax + 1;

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
            return redirect()->back()->with('error', 'An error occurred while processing your request.');
        }
        
        return redirect()->back()->with('success', 'Data has been successfully sent.');
    }
}
