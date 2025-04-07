<?php

namespace App\Http\Livewire;

use App\Helpers\NumberToAAConverter;
use App\Models\BOQ;
use App\Models\HistoryPurchase;
use App\Models\Project;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestDetail;
use App\Models\User;
use App\Notifications\PurchaseRequestCreated;
use App\Roles\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class CreatePurchaseRequest extends Component
{
    public $showprojectadd = false;

    //project model
    public $projectnameadd, $projectcompanynameadd, $projectpicadd, $projectemailadd;
    public $projectphoneadd, $projectaddressadd, $projectcityadd, $projectprovinceadd, $projectpostcodeadd;

    public $prtypemodel, $projectmodel, $requestermodel, $notemodel, $city;

    public $projects;

    public $lapangan;

    public $errorarray = false;

    public $userarray = [];

    public $project_exist = true;

    public $partOf;
    public $customPartOf;

    protected $rules = [
        'prtypemodel' => ['required'],
        'projectmodel' => ['required'],
        'requestermodel' => ['required'],
        'partOf' => 'required',
        'customPartOf' => 'required_if:partOf,retail',
        'city' => ['required'],
        'notemodel' => ['nullable'],
    ];

    protected $messages = [
        'prtypemodel.required' => 'PR Type tidak boleh kosong',
        'projectmodel.required' => 'Project tidak boleh kosong',
        'requestermodel.required' => 'Requester tidak boleh kosong',
        'partOf.required' => 'Task tidak boleh kosong',
        'customPartOf.required_if' => 'Retail tidak boleh kosong',
        'city.required' => 'Nama Kota tidak boleh kosong',
    ];

    public function render()
    {
        $this->lapangan = User::role([Role::LAPANGAN])->get();
        $this->projects = Project::where("status", "On going")->get();

        if ($this->projectnameadd != null) {
            if (Project::where('name', 'like', '%' . $this->projectnameadd . '%')->count() > 0) {
                $this->project_exist = true;
            } else {
                $this->project_exist = false;
            }
        } else {
            $this->project_exist = true;
        }

        return view('livewire.create-purchase-request');
    }

    public function savepr()
    {
        $this->validate();
        $arrtodb = [];

        try {
            foreach ($this->userarray as $value) {
                array_push($arrtodb, $value["id"]);
            }

            $currentuser = Auth::user();

            $purchaserequest = PurchaseRequest::create([
                'pr_no' => null,
                'pr_type' => $this->prtypemodel,
                'project_id' => $this->projectmodel,
                'partof' => $this->partOf == 'retail' ? $this->customPartOf : $this->partOf,
                'is_task' => $this->partOf == 'retail' ? 0 : 1,
                'warehouse_id' => 0,
                'requester' => $this->requestermodel,
                'status' => "Draft",
                'remark' => $this->notemodel,
                'city' => $this->city,
                'created_by' => $currentuser->id,
            ]);

            HistoryPurchase::create([
                'action_start' => 'New Draft PR',
                'action_end' => 'New Draft PR',
                'referensi' => null,
                'action_by' => $currentuser->id,
                'created_by' => $currentuser->id,
                'action_date' => Carbon::now(),
                'created_at' => Carbon::now(),
            ]);

            $purches = User::role([Role::PURCHASING, Role::IT])->get();

            foreach ($purches as $pur) {
                Notification::send($pur, new PurchaseRequestCreated([
                    'pr_no' => $purchaserequest->pr_no,
                    'pr_detail' => $purchaserequest->id,
                    "created_by" => $currentuser->name
                ]));
            }

            return redirect()->to("/itemprindex/" . $purchaserequest->id . "?firstcreate=yes")
                ->with('success', "Purchase Request Destination has been created successfully.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function addToArray()
    {
        if ($this->requestermodel && $this->requestermodel != 0) {
            foreach ($this->userarray as $k => $n) {
                if ($this->requestermodel == $n["id"]) {
                    return;
                }
            }
            $getuser = User::where("id", $this->requestermodel)->first();
            $this->userarray[] = ["id" => $getuser->id, "name" => $getuser->name];
            $this->requestermodel = 0;
        }
    }

    public function deleteArray($key)
    {
        unset($this->userarray[$key]);
    }

    public function showaddproject()
    {
        $this->showprojectadd = true;
    }

    public function closeddproject()
    {
        $this->showprojectadd = false;
    }

    public function closeshowai()
    {
        $this->showprojectadd = false;
    }

    public function storeproject()
    {
        $this->validate([
            'projectnameadd' => 'required',
            'projectcompanynameadd' => 'required',
            'projectpicadd' => 'required',
            'projectemailadd' => 'nullable',
            'projectphoneadd' => 'required',
            'projectaddressadd' => 'required',
            'projectcityadd' => 'required',
            'projectprovinceadd' => 'required',
            'projectpostcodeadd' => 'required',
        ]);

        $existproject = Project::where("name", $this->projectnameadd)->get();
        if (count($existproject)) {
            return;
        }

        $project = new Project;
        $project->name = $this->projectnameadd;
        $project->company_name = $this->projectcompanynameadd;
        $project->pic = $this->projectpicadd;
        $project->email = $this->projectemailadd;
        $project->phone = $this->projectphoneadd;
        $project->address = $this->projectaddressadd;
        $project->city = $this->projectcityadd;
        $project->province = $this->projectprovinceadd;
        $project->post_code = $this->projectpostcodeadd;
        $project->created_by = auth()->user()->id;
        $project->save();

        Project::where("id", $project->id)->update([
            "project_code" => NumberToAAConverter::format($project->id)
        ]);

        $this->showprojectadd = false;
    }
}
