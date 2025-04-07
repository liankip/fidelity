<?php

namespace App\Http\Livewire\ProjectGroup;

use App\Models\Project;
use App\Models\ProjectGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Livewire\Component;

class AddProject extends Component
{
    public $showModal = false;
    public ProjectGroup $group;
    public $projects;
    public $selected = [];
    public $previous;

    public function mount(ProjectGroup $group)
    {
        $this->group = $group;
        $this->previous = URL::current();
    }

    public function render()
    {
        return view('livewire.project-group.add-project');
    }

    public function toggleModal()
    {
        $this->showModal = !$this->showModal;
        if ($this->showModal) {
            $this->projects = Project::where('project_group_id', null)->orderBy('name')->get();
        }
    }

    public function storeProject(Request $request, $groupId)
    {
        $projectIds = $request->project_id;
        foreach ($projectIds as $projectId) {
            $project = Project::find($projectId);
            $project?->update([
                'project_group_id' => $groupId
            ]);
            $project?->save();
        }

        return redirect()->back()->with('success', "Project succesfully added");
    }
}
