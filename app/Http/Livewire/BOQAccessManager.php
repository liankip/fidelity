<?php

namespace App\Http\Livewire;

use App\Models\Project;
use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\BOQAccess;
use App\Notifications\BOQAccessApproved;
use App\Notifications\BOQAccessRejected;
use Illuminate\Support\Facades\Notification;

class BOQAccessManager extends Component
{
    public Project $project;
    public $showModal = false;
    public $projectId;
    public $user_id;

    protected $rules = [
        'user_id' => 'required',
    ];

    public function mount($projectId)
    {
        if(!auth()->user()->hasTopLevelAccess()) {
            abort(403);
        }

        $this->projectId = $projectId;
        $project = Project::findOrFail($projectId);

        $this->project = $project;
    }

    public function render()
    {
        return view('boq_access.index');
    }

    public function store(Request $request, $projectId){
        $project = Project::findOrFail($projectId);

        BOQAccess::create([
            'project_id' => $project->id,
            'user_id' => $request->user_id,
            'status' => 'approved',
            'action' => '-'
        ]);

        $this->showModal = false;
        return redirect()->route('boq.access.index', $project->id)->with('success', 'User access added successfully');

    }

    public function submitApproval($isApprove, $id)
    {
        $access = BOQAccess::findOrFail($id);
        if ($isApprove) {
            $access->update([
                'status' => 'approved'
            ]);
        } else {
            $access->delete();
        }

        $this->sendNotification($this->project, $access, $isApprove);
        return redirect()->route('boq.access.index', $this->project->id)->with('success', 'BOQ access ' . ($isApprove ? 'approved' : 'rejected') . ' successfully');
    }

    public function storeApproval(Request $request, $projectId) {
        $access = BOQAccess::findOrFail($request->access_id);
        $isApprove = $request->is_approve;

        if ($isApprove) {
            $access->update([
                'status' => 'approved'
            ]);
        } else {
            $access->delete();
        }

        $this->sendNotification($access->project, $access, $isApprove);
        return redirect()->route('boq.access.index', $access->project->id)->with('success', 'BOQ access ' . ($isApprove ? 'approved' : 'rejected') . ' successfully');
    }

    public function sendNotification($project, $access, $isApprove){
        $data = [
            'project_name' => $project->name,
            'action' => $access->action,
            'url' => url('/boq/' . $project->id)
        ];

        $notifyObject = $isApprove ? new BOQAccessApproved($data) : new BOQAccessRejected($data);
        Notification::send($access->user, $notifyObject);
    }

    public function approval($projectId, $id) {
        $access = BOQAccess::where('project_id', $projectId)->where('id', $id)->first();

        if (!$access) {
            return abort(404);
        }

        return view('boq_access.b-o-q-access-manager', compact('access'));
    }

    public function removeAccess($id) {
        $access = BOQAccess::findOrFail($id);

        $access->delete();

        return redirect()->route('boq.access.index', $this->project->id)->with('success', 'BOQ access removed successfully');
    }

    public function addUserAccess() {
        $this->validate();
        dd($this->user_id);
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function showModal() {
        $this->showModal = true;
    }

}
