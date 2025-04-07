<?php

namespace App\Http\Livewire;

use App\Models\BOQSpreadsheet;
use App\Models\BOQSpreadsheetReview;
use App\Models\Project;
use App\Models\Setting;
use App\Models\Task;
use Livewire\Component;

class BoqProjectList extends Component
{
    public $project;
    public $submittedBOQs;
    public $status;
    public $task;
    public int $taskId;

    public function updateStatus($status)
    {
        if ($status != 0 && $status != 1) {
            return abort(404);
        }

        $this->status = $status;
    }

    public function review($id)
    {
        $review = BOQSpreadsheetReview::where('b_o_q_spreadsheet_id', $id)->first();
        $boqs = BOQSpreadsheet::findOrfail($id);

        if (is_null($review)) {
            $review = BOQSpreadsheetReview::create([
                'b_o_q_spreadsheet_id' => $id,
                'reviewed_by' => auth()->user()->id,
                'data' => json_encode($boqs->getJsonDataAsObjectArray())
            ]);
        }

        return redirect()->route('boq.review.detail', ['projectId' => $this->project->id, 'boqId' => $review->id]);
    }

    public function view($id)
    {
        $boqs = BOQSpreadsheet::findOrfail($id);

        switch ($boqs->status) {
            case 'Reviewed':
                return redirect()->route('boq.project.result', ['projectId' => $this->project->id, 'boqId' => $boqs->id]);
                break;
            case 'Finalized':
            case 'Approved':
            case 'Submitted':
                return redirect()->route('boq.project.detail', ['projectId' => $this->project->id, 'boqId' => $boqs->id]);
                break;
            case 'Draft':
                return redirect()->route('boq.project.detail', ['projectId' => $this->project->id, 'boqId' => $boqs->id]);
                break;
            default:
                return abort(404);
                break;
        }
    }

    public function delete($id)
    {
        $boqs = BOQSpreadsheet::findOrfail($id);

        $boqs->delete();
        return redirect()->route('boq.project.index', ['projectId' => $this->project->id, 'taskId' => $this->taskId])->with('success', 'BOQ Project has been deleted successfully');
    }

    public function mount($projectId, $taskId)
    {
        $this->status = 0;
        $this->setting = Setting::first();
        $this->project = Project::findOrFail($projectId);
        $this->task = Task::find($taskId);
        $this->taskId = $taskId;
    }

    public function render()
    {
        if (auth()->user()->hasTopLevelAccess() || auth()->user()->hasK3LevelAccess() || auth()->user()->hasApproveBOQSpreadsheet() || auth()->user()->hasAdminLapanganLevelAccess()) {
            if ($this->status == 0) {
                $this->submittedBOQs = BOQSpreadsheet::where('project_id', $this->project->id)
                    ->where('task_id', $this->taskId)
                    ->where('is_task', '=', 1)
                    ->where(function ($query) {
                        $query->where('status', 'Submitted')
                            ->orWhere('status', 'Approved')
                            ->orWhere('status', 'Draft');
                    })
                    ->get();
            } else {
                $this->submittedBOQs = BOQSpreadsheet::where('project_id', $this->project->id)
                    ->where('status', 'Finalized')
                    ->where('task_id', $this->taskId)
                    ->where('is_task', '=', 1)
                    ->get();
            }
        } else {
            if ($this->status == 0) {
                $this->submittedBOQs = BOQSpreadsheet::where('project_id', $this->project->id)
                    ->where('user_id', auth()->user()->id)
                    ->where('task_id', $this->taskId)
                    ->where('is_task', '=', 1)
                    ->where(function ($query) {
                        $query->where('status', 'Submitted')
                            ->orWhere('status', 'Approved')
                            ->orWhere('status', 'Draft');
                    })
                    ->get();
            } else {
                $this->submittedBOQs = BOQSpreadsheet::where('project_id', $this->project->id)
                    ->where('user_id', auth()->user()->id)
                    ->where('task_id', $this->taskId)
                    ->where('is_task', '=', 1)
                    ->where('status', 'Finalized')
                    ->get();
            }
        }

        return view('livewire.boq-project-list');
    }
}
