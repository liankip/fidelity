<?php

namespace App\Http\Livewire\Boq;

use App\Roles\Role;
use App\Models\User;
use App\Models\Project;
use Livewire\Component;
use App\Models\BOQAccess;
use App\Traits\NotificationManager;
use App\Notifications\BOQAccessApproval;

class RequestAccessModal extends Component
{
    use NotificationManager;

    public $project_id;
    public $project;
    public $title = "Akses Ditolak";
    public $content = "Anda tidak memiliki akses untuk membuat adendum";

    public function mount($project_id, $title = null, $content = null)
    {
        $this->project_id = $project_id;
        $this->project = Project::find($project_id);

        if ($title) {
            $this->title = $title;
        }

        if ($content) {
            $this->content = $content;
        }
    }

    public function render()
    {
        return view('livewire.boq.request-access-modal');
    }

    public function requestAccessAdendum()
    {
        $datauser = User::role([Role::MANAGER, Role::IT])->get();

        $access = BOQAccess::where("project_id", $this->project->id)->where("user_id", auth()->user()->id)->first();

        $data = [
            "project_name" => $this->project->name,
            "location" => $this->project->id,
            "editor" => auth()->user()->name,
            "category" => "",
            'action' => 'create an adendum',
        ];

        if ($access) {
            if ($access->status == 'rejected') {
                return redirect()->route('boq.index', $this->project->id)->with('danger', 'Access request has been rejected.');
            }

            $data['url'] = url('boq/' . $this->project->id . '/access' . '/' . $access->id);

            $this->sendNotification($data, $datauser, BOQAccessApproval::class);
            $this->sendNotification($data, auth()->user(), BOQAccessApproval::class);

            return redirect()->route('boq.index', $this->project->id)->with('danger', 'Access request already sent.');
        }

        $boqAccess = BOQAccess::create([
            "project_id" => $this->project->id,
            'user_id' => auth()->user()->id,
            'action' => 'create an adendum'
        ]);
        $data['url'] = url('boq/' . $this->project->id . '/access' . '/' . $boqAccess->id);

        $this->sendNotification($data, $datauser, BOQAccessApproval::class);
        $this->sendNotification($data, auth()->user(), BOQAccessApproval::class);

        return redirect()->route('boq.index', $this->project->id)->with('success', 'Access request has been sent.');
    }
}
