<?php

namespace App\Http\Livewire\Dashboard;

use App\Helpers\Exchangerateapi;
use App\Models\Exchangerate;
use Livewire\Component;

class Exchange extends Component
{
    public $kurs;
    public function render()
    {
        $this->kurs = Exchangerate::whereIn("convert",["IDR","MYR","THB","SGD"])->get();
        return view('livewire.dashboard.exchange');
    }
    public function Refresh()
    {
        $exchnage = Exchangerateapi::getallexchnagebyusd();
        $rates = collect($exchnage->rates);
        // dd($exchnage);
        // dd($rates);
        foreach ($rates as $key => $value) {
            $check = Exchangerate::where("base",$exchnage->base_code)->where("convert",$key)->first();
            if ($check) {
                Exchangerate::where("base",$exchnage->base_code)->where("convert",$key)->update([
                    "converted_value" => $value
                ]);
            }else {
                Exchangerate::create([
                    "base" => $exchnage->base_code,
                    "convert" => $key,
                    "base_value" => 1,
                    "converted_value" => $value
                ]);
            }

        }
    }
}
