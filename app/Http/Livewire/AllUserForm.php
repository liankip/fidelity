<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class AllUserForm extends Component
{
    public $nik;
    public $name;
    public $email;
    public $password;
    public $position;
    public $education;
    public $status;
    public $gender;
    public $dob;
    public $acc_date;
    public $address;
    public $disability;
    public $tier;
    public $contract;

    protected $rules = [
        'nik' => 'nullable',
        'name' => 'required',
        'email' => 'required',
        'password' => 'required',
        'position' => 'nullable',
        'education' => 'nullable',
        'status' => 'nullable',
        'gender' => 'nullable',
        'dob' => 'nullable',
        'acc_date' => 'nullable',
        'address' => 'nullable',
        'disability' => 'nullable',
        'tier' => 'nullable',
        'contract' => 'nullable',
    ];

    public function store(){
        $validated = $this->validate();
        User::create([
            'nik' => $validated['nik'],
            'tier' => $validated['tier'],
            'contract_no' => $validated['contract'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'username' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'position' => $validated['position'],
            'education' => $validated['education'],
            'status' => $validated['status'],
            'gender' => $validated['gender'],
            'dob' => $validated['dob'],
            'type' => 7,
            'accepted_date' => $validated['acc_date'],
            'address' => $validated['address'],
            'disability' => $validated['disability'],
        ]);
        return redirect('/hrd/user')->with('success', 'Berhasil menambahkan user');
    }

    public function render()
    {
        return view('livewire.all-user-form');
    }
}
