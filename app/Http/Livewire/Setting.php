<?php

namespace App\Http\Livewire;

use App\Models\Project;
use App\Models\Setting as ModelsSetting;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Setting extends Component
{
    public $setting, $projects;

    public $global_boq,
        $individual_boq = [],
        $po_limit,
        $pr_number,
        $po_number,
        $leave_request_limit,
        $po_limit_switch,
        $multiple_po_approval,
        $multiple_approval,
        $multiple_k3_approval,
        $multiple_mom_approval,
        $multiple_item_approval,
        $multiple_pr_approval,
        $voucher_number,
        $multiple_wbs_revision_approval,
        $multiple_wbs_approval;

    public function mount()
    {
        if (!auth()->user()->hasTopLevelAccess()) {
            abort(403);
        }

        $this->setting = ModelsSetting::first();
        $this->po_limit_switch = $this->setting->po_limit > 0 ? true : false;
        $this->po_limit = $this->setting->po_limit;
        $this->leave_request_limit = $this->setting->leave_request_limit;
        $this->multiple_mom_approval = $this->setting->multiple_mom_approval;
        $this->multiple_po_approval = $this->setting->multiple_po_approval;
        $this->multiple_pr_approval = $this->setting->multiple_pr_approval;
        $this->multiple_item_approval = $this->setting->multiple_item_approval;
        $this->multiple_wbs_revision_approval = $this->setting->multiple_wbs_revision_approval;
        $this->multiple_wbs_approval = $this->setting->multiple_wbs_approval;
        $this->pr_number = DB::table('idx_purchase_requests')->pluck('idx')->first();
        $this->po_number = DB::table('idx_purchase_orders')->pluck('idx')->first();
        $this->voucher_number = DB::table('idx_vouchers')->pluck('idx')->first();
    }

    public function render()
    {
        $this->setting = ModelsSetting::first();
        $this->projects = Project::orderBy('id')->get();

        foreach ($this->projects as $project) {
            $this->individual_boq[$project->id] = $project->boq;
        }

        $this->global_boq = $this->setting->boq;
        $this->multiple_approval = $this->setting->multiple_approval;
        $this->multiple_k3_approval = $this->setting->multiple_k3_approval;
        return view('livewire.setting');
    }

    public function global_boq()
    {
        $this->setting->boq = $this->global_boq;
        $this->setting->save();

        $this->emit('success', 'Global BOQ updated successfully');
    }

    public function multiple_approval()
    {
        $this->setting->multiple_approval = $this->multiple_approval;
        $this->setting->save();

        $this->emit('success', 'Multiple Approval updated successfully');
    }

    public function multiple_k3_approval()
    {
        $this->setting->multiple_k3_approval = $this->multiple_k3_approval;
        $this->setting->save();

        $this->emit('success', 'Multiple K3 Approval updated successfully');
    }

    public function multiple_mom_approval()
    {
        $this->setting->multiple_mom_approval = $this->multiple_mom_approval;
        $this->setting->save();

        $this->emit('success', 'Multiple MOM Approval updated successfully');
    }

    public function multiple_item_approval()
    {
        $this->setting->multiple_item_approval = $this->multiple_item_approval;
        $this->setting->save();

        $this->emit('success', 'Multiple Item Approval updated successfully');
    }

    public function individual_boq(Project $project)
    {
        $project->boq = $this->individual_boq[$project->id];
        $project->save();

        $this->emit('success', 'BOQ updated successfully');
    }

    public function multiple_po_approval()
    {
        $this->setting->multiple_po_approval = $this->multiple_po_approval;
        $this->setting->save();

        $this->emit('success', 'Multiple PO Approval updated successfully');
    }

    public function multiple_pr_approval()
    {
        $this->setting->multiple_pr_approval = $this->multiple_pr_approval;
        $this->setting->save();

        $this->emit('success', 'Multiple PR Approval updated successfully');
    }

    public function multiple_wbs_revision_approval()
    {
        $this->setting->multiple_wbs_revision_approval = $this->multiple_wbs_revision_approval;
        $this->setting->save();

        $this->emit('success', 'Multiple WBS revision approval updated successfully');
    }
    
    public function multiple_wbs_approval()
    {
        $this->setting->multiple_wbs_approval = $this->multiple_wbs_approval;
        $this->setting->save();

        $this->emit('success', 'Multiple WBS Approval updated successfully');
    }

    public function update_po_limit()
    {
        $this->validate(
            [
                'po_limit' => ['required', 'numeric', 'min:1'],
            ],
            [],
            [
                'po_limit' => 'PO Limit',
            ],
        );

        $this->setting->po_limit = $this->po_limit;
        $this->setting->save();

        $this->po_limit = $this->setting->po_limit;

        $this->emit('success', 'PO Limit updated successfully');
    }

    public function po_limit_switch()
    {
        if ($this->po_limit_switch) {
            $this->setting->po_limit = 1;
            $this->setting->save();

            $this->po_limit_switch = true;
            $this->po_limit = $this->setting->po_limit;
        } else {
            $this->setting->po_limit = 0;
            $this->setting->save();

            $this->po_limit_switch = false;
            $this->po_limit = $this->setting->po_limit;
        }

        $this->emit('success', 'PO Limit Switch updated successfully');
    }

    public function leave_request_limit_switch()
    {
        if ($this->leave_request_limit) {
            $this->setting->leave_request_limit = 1;
            $this->setting->save();
        } else {
            $this->setting->leave_request_limit = 0;
            $this->setting->save();
        }

        $this->emit('success', 'PO Limit Switch updated successfully');
    }

    public function update_pr_number()
    {
        $this->validate(
            [
                'pr_number' => [
                    'required',
                    'numeric',
                    'min:1',
                    function ($attribute, $value, $fail) {
                        $currentPrNumber = DB::table('idx_purchase_requests')->max('idx');
                        if ($value < $currentPrNumber) {
                            $fail('The PR Number cannot be below the current number in the database.');
                        }
                    },
                ],
            ],
            [],
            [
                'pr_number' => 'PR Number',
            ],
        );

        DB::table('idx_purchase_requests')->update(['idx' => $this->pr_number]);

        $this->pr_number = DB::table('idx_purchase_requests')->pluck('idx')->first();

        $this->emit('success', 'PR Number updated successfully');
    }

    public function update_po_number()
    {
        $this->validate(
            [
                'po_number' => [
                    'required',
                    'numeric',
                    'min:1',
                    function ($attribute, $value, $fail) {
                        $currentPrNumber = DB::table('idx_purchase_orders')->max('idx');
                        if ($value < $currentPrNumber) {
                            $fail('The PO Number cannot be below the current number in the database.');
                        }
                    },
                ],
            ],
            [],
            [
                'po_number' => 'PO Number',
            ],
        );

        DB::table('idx_purchase_orders')->update(['idx' => $this->po_number]);

        $this->po_number = DB::table('idx_purchase_orders')->pluck('idx')->first();

        $this->emit('success', 'PO Number updated successfully');
    }

    public function update_voucher_number()
    {
        $this->validate(
            [
                'voucher_number' => [
                    'required',
                    'numeric',
                    'min:1',
                    function ($attribute, $value, $fail) {
                        $currentVoucherNumber = DB::table('idx_vouchers')->max('idx');
                        if ($value < $currentVoucherNumber) {
                            $fail('The Voucher Number cannot be below the current number in the database.');
                        }
                    },
                ],
            ],
            [],
            [
                'voucher_number' => 'Voucher Number',
            ],
        );

        DB::table('idx_vouchers')->update(['idx' => $this->voucher_number]);

        $this->voucher_number = DB::table('idx_vouchers')->pluck('idx')->first();

        $this->emit('success', 'Voucher Number updated successfully');
    }
}
