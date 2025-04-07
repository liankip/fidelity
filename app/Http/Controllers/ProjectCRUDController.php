<?php

namespace App\Http\Controllers;

use DB;
use App\Roles\Role;
use App\Models\User;
use App\Models\Project;
use App\Models\Customer;
use App\Models\BOQAccess;
use App\Models\ProjectGroup;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Exports\ProjectsExport;
use App\Imports\ProjectsImport;
use App\Models\ProjectDocument;
use Illuminate\Validation\Rule;
use App\Imports\CollectionImport;
use App\Traits\NotificationManager;
use App\Exports\ProjectReportExport;
use App\Helpers\NumberToAAConverter;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use App\Notifications\ProjectApproved;


class ProjectCRUDController extends Controller
{
    use NotificationManager;
    public function index(Request $request)
    {

        $projects = Project::where('deleted_at', null)->where('status', 'On going')->where('project_type', 'project')->orderBy('id', 'desc')->get();

        $userId = auth()->user()->id;
        $status = "On going";

        return view('masterdata.projects.index', compact(['projects', 'status']));
    }

    public function finished(Request $request)
    {
        if ($request->search) {
            $searchcompact = $request->search;
            $projects = Project::where(function ($query) use ($request) {
                $query->where("project_code", "like", "%" . $request->search . "%")
                    ->orWhere("name", "like", "%" . $request->search . "%")
                    ->orWhere("pic", "like", "%" . $request->search . "%")
                    ->orWhere("email", "like", "%" . $request->search . "%")
                    ->orWhere("company_name", "like", "%" . $request->search . "%");
            })
                ->where('status', 'Finished')
                ->whereNull('deleted_at')
                ->paginate(10);
            $projects->appends(['search' => $request->search]);
        } else {
            $searchcompact = "";
            $projects = Project::where('deleted_at', null)->where('status', 'Finished')->orderBy('id', 'desc')->paginate(10);
        }

        $userId = auth()->user()->id;
        $status = "Finished";

        return view('masterdata.projects.index', compact(['projects', 'status', 'searchcompact']));
    }

    public function draft(Request $request)
    {
        if ($request->search) {
            $searchcompact = $request->search;
            $projects = Project::where(function ($query) use ($request) {
                $query->where("project_code", "like", "%" . $request->search . "%")
                    ->orWhere("name", "like", "%" . $request->search . "%")
                    ->orWhere("pic", "like", "%" . $request->search . "%")
                    ->orWhere("email", "like", "%" . $request->search . "%")
                    ->orWhere("company_name", "like", "%" . $request->search . "%");
            })
                ->where('status', 'Draft')
                ->whereNull('deleted_at')
                ->paginate(10);
            $projects->appends(['search' => $request->search]);
        } else {
            $searchcompact = "";
            $projects = Project::where('deleted_at', null)->where('status', 'Draft')->orderBy('id', 'desc')->paginate(10);
        }

        $userId = auth()->user()->id;
        $status = "Draft";

        return view('masterdata.projects.index', compact(['projects', 'status', 'searchcompact']));
    }

    public function create()
    {
        $this->authorize('create', Project::class);

        $groups = ProjectGroup::all();
        $documents = ProjectDocument::all();

        $documentsData = [];
        // foreach ($documents as $document) {
        //     $import = Excel::toCollection(new CollectionImport, storage_path('app/' . $document->path));
        //     $data = $import->first();
        //     $data = $data->get(1);

        //     try {
        //         $newData = [
        //             'project_name' => $data[0],
        //             "po_number" => $data[1],
        //             "budget" => $data[2],
        //             "company_name" => $data[3],
        //             "address" => $data[4],
        //         ];
        //     } catch (\Exception $e) {
        //         $newData = [];
        //     }

        //     $documentsData[] = [
        //         'id' => $document->id,
        //         'name' => $document->file_name,
        //         'data' => $newData
        //     ];
        // }

        $response = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
        $provinces = $response->json();
        $userData = User::all()->where('active', 1)->where('is_disabled', false);

        return view('masterdata.projects.create', compact('groups', 'documentsData', 'provinces', 'userData'));
    }

    public function make_finish(Project $project)
    {
        $status = 'Finished';
        $project->update([
            'status' => $status
        ]);

        return redirect()->route('projects.index')->with('success', 'Project has been move to Finished.');
    }

    public function store(Request $request)
    {
        // dd($request->documents);
        $request->validate([
            'name' => ['required', 'unique:projects'],
            'project_type' => ['required'],
            // 'user_id' => ['required'],
            // 'project_code' => ['required', 'unique:projects'],
            'company_name' => ['required'],
            'pic' => ['required'],
            'address' => ['required'],
            // 'city' => ['required'],
            'province' => ['required'],
            'post_code' => ['nullable'],
            'status' => ['nullable'],
            'documents.*' => ['file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'nullable'], // Adjust the file validation rules as needed
        ], [], [
            'name' => 'Project Name',
            // 'user_id' => 'User',
            'company_name' => 'Company Name',
            'pic' => 'PIC',
            'address' => 'Address',
            // 'city' => 'City',
            'province' => 'Province',
            'post_code' => 'Post Code',
            'status' => 'Status',
            'project_type' => 'Project Type',
        ]);

        $existproject = Project::where("name", $request->name)->get();
        if (count($existproject)) {
            return back()->withInput()->with("danger", "Project name is exist, please change with other name");
        }

        $project = new Project;
        $project->name = $request->name;
        $project->company_name = $request->company_name;
        $project->value = 1;
        $project->start_date = $request->start_date;
        $project->end_date = $request->end_date;
        $project->project_code = $request->project_code ? $request->project_code : NumberToAAConverter::format($project->id);
        $project->pic = $request->pic;
        $project->sm = $request->sm;
        $project->logistic = $request->logistic;
        $project->ehs = $request->ehs;
        $project->director = $request->director;
        // $project->pic = $request->pic;
        $project->email = $request->email;
        $project->phone = $request->phone;
        $project->address = $request->address;
        // $project->city = $request->city;
        $project->province = $request->province;
        $project->post_code = $request->post_code;
        $project->status = 'On going';
        $project->created_by = auth()->user()->id;
        $project->project_group_id = $request->group_id;
        $project->po_number = $request->po_number;
        $project->project_type = $request->project_type;

        if ($request->customer_id != null) {
            $project->customer_id = $request->customer_id;
        }

        $project->save();

        // Project::where("id", $project->id)->update([
        //     "project_code" => NumberToAAConverter::format($project->id)
        // ]);

        // Handle document uploads
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $path = $document->store('documents', 'public');
                $project->project_documents()->create([
                    'path' => $path,
                    'file_name' => $document->getClientOriginalName(),
                    'uploaded_by' => auth()->user()->id,
                ]);
            }
        }

        BOQAccess::create([
            // 'user_id' => $request->user_id,
            'project_id' => $project->id,
            'status' => 'approved',
            'action' => '-'
        ]);

        if ($request->has('route_type')) {
            return redirect()->route('retail.index')->with('success', 'Retail project has been created successfully.');
        }

        return redirect()->route('projects.index')->with('success', 'Project has been created successfully.');
    }

    public function show(Project $project)
    {

        $datas = PurchaseOrder::with("podetail", "supplier")->where('project_id', $project->id)->where(function ($query) {
                $query->where("status", "Approved")
                    ->orWhere("status", "Paid")
                    ->orWhere("status", "Partially Paid");
            })->get();

        // Calculate Grand Total
        $grandTotal = 0;

        foreach ($datas as $po) {
            $totalAmount = collect($po->podetail)->sum('amount'); // Sum item amounts
            $ongkir = $po->deliver_status == 1 ? $po->tarif_ds : 0; // Shipping cost
            $ppn = isset($po->podetail->first()->tax_status) && $po->podetail->first()->tax_status == 2 ? 0 : round($totalAmount * 0.11);

            if ($po->tax_custom) {
                $ppn = $po->tax_custom;
            }

            $grandTotal += $totalAmount + $ppn + $ongkir;
        }
        
        return view('masterdata.projects.show', compact('project', 'grandTotal'));
    }

    public function uploadFile(Request $request, $project_id)
    {
        $request->validate([
            'documents.*' => ['file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'required'], // Adjust the file validation rules as needed
        ]);

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $path = $document->store('documents', 'public');
                ProjectDocument::create([
                    'path' => $path,
                    'project_id' => $project_id,
                    'file_name' => $document->getClientOriginalName(),
                    'uploaded_by' => auth()->user()->id,
                ]);
            }
        }
        return redirect()->route('projects.show', $project_id);
    }

    public function edit(Project $project)
    {
        $urlType = request()->get('type');
        $customers = null;

        if ($urlType == "retail") {
            $customers = Customer::all();
        }

        $groups = ProjectGroup::all();

        $response = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
        $provinces = $response->json();
        $projectStatus = $project->status;
        $userData = User::where('active', 1)->where('is_disabled', false)->get();
        return view('masterdata.projects.edit', compact('project', 'groups', 'provinces', 'projectStatus', 'userData', 'urlType', 'customers'));
    }

    public function update(Request $request, Project $project)
    {

        $rules = [
            'name' => ['required', Rule::unique('projects')->ignore($project->id)],
            'company_name' => ['required'],
            'start_date' => ['nullable', 'date'],
            'pic' => ['required'],
            'email' => ['nullable', 'email'],
            // 'phone' => ['required'],
            'address' => ['required'],
            'city' => [$project->status === 'Draft' ? 'nullable' : 'required'],
            'province' => [$project->status === 'Draft' ? 'nullable' : 'required'],
            // 'project_type' => ['required'],
        ];

        $request->validate($rules, [], [
            'name' => 'Project Name',
            'company_name' => 'Company Name',
            'pic' => 'PIC',
            'email' => 'Email',
            // 'phone' => 'Phone',
            'address' => 'Address',
            'city' => 'City',
            'province' => 'Province',
            // 'post_code'     => 'Post Code'
            // 'project_type' => 'Project Type',
        ]);

        $project->name = $request->name;
        $project->company_name = $request->company_name;
        $project->value = 1;
        $project->start_date = $request->start_date;
        $project->project_group_id = $request->group_id;
        $project->pic = $request->pic;
        $project->sm = $request->sm;
        $project->logistic = $request->logistic;
        $project->ehs = $request->ehs;
        $project->director = $request->director;
        $project->email = $request->email;
        // $project->phone = $request->phone;
        $project->address = $request->address;
        $project->city = $request->city;
        $project->province = $request->province;
        // $project->project_type = $request->project_type;
        // $project->post_code    = $request->post_code;
        if ($project->status === 'Draft') {
            $project->status = "On going";
        } else {
            $project->status = $request->status;
        }
        $project->updated_by = auth()->user()->id;

        if ($request->customer_id != null) {
            $project->customer_id = $request->customer_id;
        }
        $project->save();

        $recerver = User::withoutRole(Role::MANAGER);
        $this->sendNotification($project, $recerver, ProjectApproved::class);
        // if ($request->status == "On going") {
        if ($request->route_type == "retail") {
            return redirect()->route('retail.index')->with('success', 'Retail project has been updated successfully.');
        }
        return redirect()->route('projects.index')->with('success', 'Project has been updated successfully.');
        // } else {
        //     return redirect()->route('projects.finished')->with('success', 'Project has been updated successfully.');
        // }
    }

    public function destroy(Project $project)
    {
        $project->deleted_by = auth()->user()->id;
        $project->save();
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project has been deleted successfully.');
    }

    public function export()
    {
        return Excel::download(new ProjectsExport, 'sne-master-data-projects.xlsx');
    }

    public function import()
    {
        Excel::import(new ProjectsImport, request()->file('file'));

        return back();
    }

    public function finishedProjects()
    {
        $projects = Project::with([
            'purchase_orders',
            'boqs' => function ($query) {
                $query->select('project_id', DB::raw('SUM(price_estimation) as total_price_estimation'))
                    ->groupBy('project_id');
            }
        ])
            ->where('status', 'Finished')
            ->whereNull('deleted_at')
            ->groupBy('id', 'name', 'status')
            ->get();

        return view('masterdata.projects.project-reports', compact('projects'));
    }

    public function reportExport($projectId)
    {
        $project = Project::find($projectId);

        if (!$project) {
            abort(404);
        }

        $fileName = $project->name . ' Project Report.xlsx';

        return Excel::download(new ProjectReportExport($projectId), $fileName);
    }
}
