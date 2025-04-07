<?php

namespace App\Http\Livewire\Settings;

use App\Models\LimitPoSetting as ModelsLimitPoSetting;
use Carbon\Carbon;
use Livewire\Component;

class LimitPoSetting extends Component
{
    public $rules, $active;
    public function render()
    {
        //check ada yang tgl hari ini
        $nowexist = ModelsLimitPoSetting::where("date", Carbon::now())->first();
        if ($nowexist) {
            $this->active = $nowexist;
        } else {
            $this->active = ModelsLimitPoSetting::where("id", 1)->first();
        }
        $this->rules  = ModelsLimitPoSetting::where("id", "!=", $this->active->id)->get();
        return view('livewire.setting.limit-po-settings');
    }
}
