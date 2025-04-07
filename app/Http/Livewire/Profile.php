<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class Profile extends Component
{
    public $user;
    public $name, $phone_number, $email;
    public $update_loading;
    public $old_password, $new_password, $confirm_new_password;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        # code...
        $this->user = User::Where('id', auth()->user()->id)->first();
        $this->name = $this->user->name;
        $this->phone_number = $this->user->phone_number;
        $this->email = $this->user->email;
    }

    public function render()
    {
        return view('livewire.profile');
    }

    public function update()
    {
        try {
            $this->update_loading = true;
            User::Where('id', auth()->user()->id)->update(['name' => $this->name, 'phone_number' => $this->phone_number, 'email' => $this->email]);
            session()->flash('message', 'Update berhasil.');
            $this->loadData();
            $this->update_loading = false;
        } catch (\Exception $e) {
            $this->update_loading = false;
            session()->flash('message', 'Update gagal.');
            $this->loadData();
        }
    }

    public function updatePassword()
    {

        #Match The Old Password
        if (!Hash::check($this->old_password, auth()->user()->password)) {
            return session()->flash('message_change_password', 'Password lama salah');
        }

        if ($this->new_password !== $this->confirm_new_password) {
            return session()->flash('message_change_password', 'Password baru tidak cocok');
        }

        try {
            #Update the new Password
            User::whereId(auth()->user()->id)->update([
                'password' => Hash::make($this->new_password)
            ]);

            session()->flash('message_change_password', 'password berhasil diganti');
        } catch (\Exception $e) {
            session()->flash('message_change_password', 'password gagal diganti');
        }
    }
}
