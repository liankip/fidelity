<?php

namespace App\Http\Livewire;

use App\Helpers\NumberToAAConverter;
use App\Models\HistoryPurchase;
use App\Models\Project;
use App\Models\PurchaseRequest;
use App\Models\User;
use App\Notifications\PurchaseRequestCreated;
use App\Roles\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class CreatePrNoBoq extends Component
{
    //show add project
    public $showprojectadd = false;

    //project model
    public $projectnameadd, $projectcompanynameadd, $projectpicadd, $projectemailadd;
    public $projectphoneadd, $projectaddressadd, $projectcityadd, $projectprovinceadd, $projectpostcodeadd;

    public $prtypemodel, $projectmodel, $requestermodel, $bagianmodel, $notemodel, $requesterPhone, $city;

    public $projects;

    public $lapangan;

    public $errorarray = false;

    public $userarray = [];

    public $project_exist = true;

    public function render()
    {
        $user = auth()->user();
        if (!$user->hasPermissionTo('create-pr-no-boq')) {
            return abort(403);
        }

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

        return view('livewire.create-pr-no-boq');
    }

    public function savepr()
    {
        $this->validate([
            'prtypemodel' => ['required'],
            'projectmodel' => ['required'],
            'requestermodel' => ['required'],
            'bagianmodel' => ['required'],
            'requesterPhone' => ['required'],
            'city' => ['required'],
            'notemodel' => ['nullable'],
        ], [], [
            'prtypemodel' => 'PR Type',
            'projectmodel' => 'Project',
            'requestermodel' => 'Requester',
            'rqeuesterPhone' => 'Requester Phone',
            'bagianmodel' => 'Work Section',
            'city' => 'City',
            'notemodel' => 'Note',
        ]);


        $arrtodb = [];

        foreach ($this->userarray as $value) {
            array_push($arrtodb, $value["id"]);
        }

        $purchaserequest = new PurchaseRequest;
        $purchaserequest->pr_no = null;
        $purchaserequest->pr_type = $this->prtypemodel;
        $purchaserequest->project_id = $this->projectmodel;
        $purchaserequest->warehouse_id = 0;
        $purchaserequest->requester = $this->requestermodel;
        $purchaserequest->partof = $this->bagianmodel;
        $purchaserequest->status = "Draft";
        $purchaserequest->remark = $this->notemodel;
        $purchaserequest->requester_phone_number = $this->requesterPhone;
        $purchaserequest->city = $this->city;
        $purchaserequest->created_by = auth()->user()->id;
        $purchaserequest->save();


        $currentuser = Auth::user();

        $history = new HistoryPurchase;
        $history->action_start = 'New Draft PR';
        $history->action_end = 'New Draft PR';
        $history->referensi = null;
        $history->action_by = $currentuser->id;
        $history->created_by = $currentuser->id;
        $history->action_date = Carbon::now();
        $history->created_at = Carbon::now();
        $history->save();

        $purches = User::role([Role::PURCHASING, Role::IT])->get();

        foreach ($purches as $key => $pur) {
            $podata = [
                'pr_no' => $purchaserequest->pr_no,
                'pr_detail' => $purchaserequest->id,
                "created_by" => $currentuser->name
            ];
            Notification::send($pur, new PurchaseRequestCreated($podata));
        }

        $reserved = User::role([Role::PURCHASING, Role::IT])->get();
        $messageh3 = url("purchase_requests");

        return redirect()->to("/itemprcreate/" . $purchaserequest->id . "?firstcreate=yes")
            ->with('success', "Purchase Request Destination has been created successfully.");
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
