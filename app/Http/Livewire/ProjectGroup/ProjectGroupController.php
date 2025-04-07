<?php

namespace App\Http\Livewire\ProjectGroup;

use App\Models\Project;
use App\Models\ProjectGroup;
use Illuminate\Support\Facades\URL;
use Livewire\Component;

class ProjectGroupController extends Component
{
    public $previous;
    public $search;

    public function mount()
    {
        $this->previous = URL::current();
    }

    public function render()
    {
        if ($this->search) {
            $groups = ProjectGroup::where('name', 'like', '%' . $this->search . '%')->paginate(10);
        } else {
            $groups = ProjectGroup::paginate(10);
        }

        return view('livewire.project-group.project-group-controller', [
            'groups' => $groups,
        ]);
    }

    public function removeProject($project)
    {
        $project = Project::find($project['id']);
        $project?->update([
            'project_group_id' => null,
        ]);

        return redirect($this->previous)->with('success', 'Project removed from group successfully');
    }

    public function deleteGroup($groupId)
    {
        $group = ProjectGroup::find($groupId);
        $group?->delete();

        $projects = Project::where('project_group_id', $group['id'])->get();
        foreach ($projects as $project) {
            $project->update([
                'project_group_id' => null,
            ]);
        }

        return redirect($this->previous)->with('success', 'Group deleted successfully');
    }
}
