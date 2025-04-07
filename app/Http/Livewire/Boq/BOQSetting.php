<?php

namespace App\Http\Livewire\Boq;

use App\Models\Project;
use App\Models\User;
use Livewire\Component;

class BOQSetting extends Component
{
    public $project;
    public $users;
    public $approver_id;

    public function mount($projectId)
    {
        if (!auth()->user()->hasTopManagerAccess()) {
            abort(403);
        }

        $this->project = Project::findOrfail($projectId);
        $this->users = User::activeUser()->get();
    }

    public function render()
    {
        return view('livewire.boq.boq-setting');
    }

    public function save()
    {
        $this->validate([
            'approver_id' => 'required',
        ]);

        $this->project->purchase_order_approver()->syncWithoutDetaching($this->approver_id);

        return redirect()->route('boq.setting.index', $this->project->id)->with('success', 'BOQ Setting updated successfully');
    }

    public function remove($id)
    {
        $this->project->purchase_order_approver()->detach($id);

        return redirect()->route('boq.setting.index', $this->project->id)->with('success', 'BOQ Setting updated successfully');
    }
}
