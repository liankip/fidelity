<?php

namespace App\Traits;

use Illuminate\Support\Facades\Redirect;

trait AuthorizesRequests
{
    public function authorizeBOQFromLivewire($project)
    {
        $message = $this->getMessageAuthorizeBOQ($project);

        if ($message) {
            return redirect()->route('boq.index', $project->id)->with('danger', $message);
        }
    }

    public function authorizeBOQ($project)
    {
        $message = $this->getMessageAuthorizeBOQ($project);

        if ($message) {
            return redirect()->route('boq.index', $project->id)->with('danger', $message)->send();
        }
    }

    private function getMessageAuthorizeBOQ($project)
    {
        $message = null;

        if (auth()->user()->hasTopLevelAccess()) {
            return $message;
        }

        if ($project->boq_verification == 1) {
            $message = 'BOQ still waiting for approval.';
        }

        if ($project->status_boq === 0) {
            $message = "Can't create BOQ, please create adendum first.";
        }

        if (!$project->hasBoqAccess()) {
            $message = 'You dont have access for this project.';
        }

        return $message;
    }
}
