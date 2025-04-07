<?php

namespace App\Http\Livewire\Boq;

use App\Models\BOQSpreadsheet;
use App\Models\BOQSpreadsheetReview;
use App\Models\Project;
use App\Models\Setting;
use Livewire\Component;


class BoqReviewList extends Component
{
    public $project;
    public $submittedBOQs;
    public Setting $setting;

    public $status;

    public function updateStatus($status)
    {
        if ($status != 0 && $status != 1) {
            return abort(404);
        }

        $this->status = $status;
    }

    public function mount($projectId)
    {
        $this->status = 0;
        $this->setting = Setting::first();
        $this->project = Project::findOrFail($projectId);
    }

    public function render()
    {
        if (auth()->user()->hasTopLevelAccess() || auth()->user()->hasK3LevelAccess() || auth()->user()->hasApproveBOQSpreadsheet() || auth()->user()->hasAdminLapanganLevelAccess()) {
            if ($this->status == 0) {
                $this->submittedBOQs = BOQSpreadsheet::where('project_id', $this->project->id)
                    ->where('wbs_type', '=', 0)
                    ->where(function ($query) {
                        $query->where('status', 'Submitted')
                            ->orWhere('status', 'Approved');
                    })
                    ->get();
            } else {
                $this->submittedBOQs = BOQSpreadsheet::where('project_id', $this->project->id)
                    ->where('wbs_type', '=', 0)
                    ->where('status', 'Finalized')
                    ->get();
            }
        } else {
            if ($this->status == 0) {
                $this->submittedBOQs = BOQSpreadsheet::where('project_id', $this->project->id)
                    ->where('user_id', auth()->user()->id)
                    ->where('wbs_type', '=', 0)
                    ->where(function ($query) {
                        $query->where('status', 'Submitted')
                            ->orWhere('status', 'Approved');
                    })
                    ->get();
            } else {
                $this->submittedBOQs = BOQSpreadsheet::where('project_id', $this->project->id)
                    ->where('user_id', auth()->user()->id)
                    ->where('wbs_type', '=', 0)
                    ->where('status', 'Finalized')
                    ->get();
            }
        }
        return view('livewire.boq.boq-review-list');
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
                return redirect()->route('boq.review.result', ['projectId' => $this->project->id, 'boqId' => $boqs->id]);
                break;
            case 'Finalized':
            case 'Approved':
            case 'Submitted':
                return redirect()->route('boq.review.detail', ['projectId' => $this->project->id, 'boqId' => $boqs->id]);
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
        return redirect()->route('boq.review.index', ['projectId' => $this->project->id])->with('success', 'BOQ has been deleted successfully');
    }
}
