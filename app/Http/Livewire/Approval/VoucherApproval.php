<?php

namespace App\Http\Livewire\Approval;

use App\Models\Voucher;
use Livewire\Component;

class VoucherApproval extends Component
{

    public $prarray = [];
    public $checkall;

    //sho consern reject
    public $consernshow = false;
    public $consernshowmultiple = false;

    public $vouchers;

    public $idrevert;
    public $revertconsernmodel;

    public function mount()
    {
        $this->vouchers = Voucher::orderBy("created_at", "DESC")->where('approved_by', null)->where('rejected_by', null)->get();
    }

    public function showconsern($id)
    {
        $this->idrevert = $id;
        $this->consernshow = true;
    }

    public function closeconsern()
    {
        $this->consernshow = false;
        $this->consernshowmultiple = false;
    }

    public function approve()
    {
        $newprarray = [];
        foreach ($this->prarray as $value) {
            if ($value["checked"]) {
                array_push($newprarray, $value["id"]);
            }
        }
    }

    public function allcheck()
    {
        if ($this->checkall) {
            foreach ($this->prarray as $key => $value) {
                $this->prarray[$key]["checked"] = 1;
            }
        } else {
            foreach ($this->prarray as $key => $value) {
                $this->prarray[$key]["checked"] = 0;
            }
        }
    }

    public function render()
    {
        return view('livewire.approval.voucher-approval');
    }
}
