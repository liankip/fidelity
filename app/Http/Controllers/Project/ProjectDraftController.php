<?php

namespace App\Http\Controllers\Project;

use App\Models\Project;
use App\Models\BOQAccess;
use Illuminate\Http\Request;
use App\Helpers\NumberToAAConverter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class ProjectDraftController extends Controller
{
    public function __invoke()
    {
        $response = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
        $provinces = $response->json();
        return view('masterdata.projects.create-draft', compact('provinces'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => ['required', 'unique:projects'],
            'company_name'  => ['required'],
            'project_value' => ['required'],
            'start_date'    => ['nullable', 'date'],
            'pic'           => ['required'],
            'email'         => ['nullable', 'email'],
            'phone'         => ['required'],
            'address'       => ['required'],
            'city'          => ['nullable'],
            'province'      => ['nullable'],
            'post_code'     => ['nullable'],

        ], [], [
            'name'          => 'Project Name',
            'company_name'  => 'Company Name',
            'project_value' => 'Project Value',
            'start_date'    => 'Start Date',
            'pic'           => 'PIC',
            'email'         => 'Email',
            'phone'         => 'Phone',
            'address'       => 'Address',
            'city'          => 'City',
            'province'      => 'Province',
            'post_code'     => 'Post Code',
        ]);

        $existproject = Project::where("name", $request->name)->get();
        if (count($existproject)) {
            return back()->withInput()->with("danger", "Project name is exist, please change with other name");
        }

        $project               = new Project;
        $project->name         = $request->name;
        $project->company_name = $request->company_name;
        $project->value        = $request->project_value;
        $project->start_date   = $request->start_date;
        $project->pic          = $request->pic;
        $project->email        = $request->email;
        $project->phone        = $request->phone;
        $project->address      = $request->address;
        $project->status       = 'Draft';
        $project->created_by   = auth()->user()->id;
        $project->save();

        Project::where("id", $project->id)->update([
            "project_code" => NumberToAAConverter::format($project->id)
        ]);

        BOQAccess::create([
            'user_id' => auth()->user()->id,
            'project_id' => $project->id,
            'status' => 'approved',
            'action' => '-'
        ]);

        return redirect()->route('projects.index')->with('success', 'Project has been created successfully.');
    }
}
