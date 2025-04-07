<?php

namespace App\Http\Livewire\Vendors;

use App\Mail\VendorApproved;
use App\Models\User;
use App\Models\VendorRegistrant;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class VendorNeedApproval extends Component
{
    public $vendors;

    public $checklist = [];
    public $select_all = false;
    public $reasonToReject;

    public function mount()
    {
        $this->vendors = VendorRegistrant::needApproval()->with('items')->get();
    }

    public function render()
    {
        return view('livewire.vendors.vendor-need-approval');
    }

    public function checkAll()
    {
        foreach ($this->vendors as $vendor) {
            $this->checklist[$vendor->id] = $this->select_all;
        }
    }

    public function approve()
    {
        $checked = collect($this->checklist)->filter(function ($value, $key) {
            return $value === true;
        });

        $item_ids = $checked->keys();
        $vendors = VendorRegistrant::whereIn('id', $item_ids)->get();

        foreach ($vendors as $vendor) {
            $vendor->update([
                'is_approved' => 1,
                'aproved_by' => auth()->user()->id,
            ]);

            $email = $vendor->email;
            Mail::to($email)->send(new VendorApproved($vendor));
        }

        return redirect()->route('vendors.need-approval')->with('success', 'Vendors approved successfully');
    }

    public function reject()
    {
        $checked = collect($this->checklist)->filter(function ($value, $key) {
            return $value === true;
        });

        $item_ids = $checked->keys();
        $vendors = VendorRegistrant::whereIn('id', $item_ids)->get();


        foreach ($vendors as $vendor) {
            Mail::to($vendor->email)->send(new \App\Mail\Vendor\RegistrationRejected($vendor->name, $this->reasonToReject));
            $vendor->delete();
        }



        return redirect()->route('vendors.need-approval')->with('success', 'Vendors rejected successfully');
    }
}
