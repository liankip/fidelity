<?php

namespace App\Http\Controllers\Prints;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PrintBOQController extends Controller
{
    public function print($id)
    {
        $project = Project::findOrfail($id);
        $boqs = $project->b_o_q_s();

        return view('prints.print-boq', compact('project', 'boqs'));
    }
}
