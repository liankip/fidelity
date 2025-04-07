<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;

class EditUser extends Component
{
    public $userId;

    protected $rules = [
        'nik' => 'nullable',
        'name' => 'required',
        'email' => 'nullable',
        'password' => 'nullable',
        'position' => 'nullable',
        'education' => 'nullable',
        'status' => 'nullable',
        'gender' => 'nullable',
        'dob' => 'nullable',
        'accepted_date' => 'nullable',
        'address' => 'nullable',
        'disability' => 'nullable',
    ];

    public function mount($id){
        $this->userId = $id;
    }

    public function editData(Request $request){

        try {
            $validated = $request->validate($this->rules);
    
            DB::beginTransaction();
    
            $updateData = [];
    
            foreach ($validated as $key => $value) {
                if ($value !== null && $value !== '') {
                    $updateData[$key] = $value;
                }
            }
    
            if (isset($updateData['password'])) {
                $updateData['password'] = Hash::make($updateData['password']);
            }
    
            User::where('id', $request->user_id)->update($updateData);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }

        return redirect('/hrd/user')->with('success', 'Berhasil mengupdate user');
    }

    public function render()
    {
        $userData = User::where('id', $this->userId)->first();
        return view('livewire.edit-user', ['userData' => $userData]);
    }
}
