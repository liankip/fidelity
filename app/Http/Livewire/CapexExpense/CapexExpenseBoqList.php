<?php

namespace App\Http\Livewire\CapexExpense;

use App\Models\BOQSpreadsheet;
use App\Models\Setting;
use Livewire\Component;

class CapexExpenseBoqList extends Component
{
    public $project_id;

    public $submittedBOQs;

    public $boqs;

    public $status;

    public function mount($project_id)
    {
        $this->project_id = $project_id;
        $this->status = 0;
        $this->setting = Setting::first();
    }

    public function updateStatus($status)
    {
        if ($status != 0 && $status != 1) {
            return abort(404);
        }

        $this->status = $status;
    }

    public function view($id)
    {
        $boqs = BOQSpreadsheet::findOrfail($id);

        switch ($boqs->status) {
            case 'Reviewed':
               return redirect()->route('capex-expense.boq.result', ['projectId' => $this->project->id, 'id' => $boqs->id]);
                // break;
            case 'Finalized':
            case 'Approved':
            case 'Draft':
            case 'Submitted':
                return redirect()->route('capex-expense.boq.detail', ['project_id' => $this->project_id, 'id' => $boqs->id]);
            //    break;
        }
    }

    public function render()
    {
        if (auth()->user()->hasTopLevelAccess() || auth()->user()->hasK3LevelAccess() || auth()->user()->hasApproveBOQSpreadsheet() || auth()->user()->hasAdminLapanganLevelAccess()) {
            if ($this->status == 0) {
                $this->submittedBOQs = BOQSpreadsheet::where('project_id', $this->project_id)
                    ->where('is_task', '=', 0)
                    ->where(function ($query) {
                        $query->where('status', 'Submitted')->orWhere('status', 'Approved')->orWhere('status', 'Draft');
                    })
                    ->get();
            } else {
                $this->submittedBOQs = BOQSpreadsheet::where('project_id', $this->project_id)->where('status', 'Finalized')->where('is_task', '=', 0)->get();
            }
        } else {
            if ($this->status == 0) {
                $this->submittedBOQs = BOQSpreadsheet::where('project_id', $this->project_id)
                    ->where('user_id', auth()->user()->id)
                    ->where('is_task', '=', 0)
                    ->where(function ($query) {
                        $query->where('status', 'Submitted')->orWhere('status', 'Approved')->orWhere('status', 'Draft');
                    })
                    ->get();
            } else {
                $this->submittedBOQs = BOQSpreadsheet::where('project_id', $this->project_id)
                    ->where('user_id', auth()->user()->id)
                    ->where('is_task', '=', 0)
                    ->where('status', 'Finalized')
                    ->get();
            }
        }

        return view('livewire.capex-expense.capex-expense-boq-list');
    }
}
