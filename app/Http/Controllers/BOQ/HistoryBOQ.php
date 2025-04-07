<?php

namespace App\Http\Controllers\BOQ;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;

class HistoryBOQ extends Controller
{
    public function __invoke($id)
    {
        $project = Project::where('id', $id)->first();
        $histories = collect([]);

        $boq = $project->boqs_not_approved()->first();
        if ($boq) {
            $histories->push([
                'date' => $boq->created_at,
                'action_by' => User::find($boq->created_by)->name,
                'version' => 'Original'
            ]);
        }
        $maxRevision = $project->maxEditRevision();

        if ($maxRevision) {
            for ($i = 1; $i <= $maxRevision; $i++) {
                $boq = $project->boqs_edit_not_approved()->where('revision', $i)->first();
                if ($boq) {
                    $histories->push([
                        'date' => $boq->created_at,
                        'action_by' => User::find($boq->created_by)->name,
                        'version' => 'Version ' . $i
                    ]);
                }
            }
        }

        return view('masterdata.boqs.history', compact('project', 'histories'));
    }
}
