<?php

namespace App\Http\Livewire\BulkPurchase;

use App\Models\Project;
use Livewire\Component;

class BulkPurchase extends Component
{
    public $bulkOptions = 'project';
    public function render()
    {
        $projects = Project::where('deleted_at', null)->where('status', 'On going')->where('project_type', 'project')->orderBy('id', 'desc')->get();

        $userId = auth()->user()->id;
        $status = "On going";

        return view('livewire.bulk-purchase.bulk-purchase', [
            'projects' => $projects,
            'userId' => $userId,
            'status' => $status
        ]);
    }
}
