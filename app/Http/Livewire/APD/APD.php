<?php

namespace App\Http\Livewire\APD;

use App\Models\ApdHandover;
use App\Models\ApdRequest;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class APD extends Component
{
    use WithFileUploads;

    public $search, $data_apd, $delete_id, $handover_id;

    public $receiver_id, $receiver_name, $description, $date, $attachment, $photo = [], $data_photo = [];

    public function render()
    {
        $this->data_apd = ApdRequest::whereHas('user', function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%');
        })->get();
        return view('livewire.a-p-d.a-p-d');
    }

    public function setDelete($id)
    {
        $data = ApdRequest::find($id);
        $this->delete_id = $data->id;
    }

    public function setHandover($id)
    {
        $data = ApdRequest::find($id);
        $this->handover_id = $data->id;
        $this->receiver_id = $data->user_id;
        $this->receiver_name = $data->user->name;
        $this->description = $data->description;
        $this->date = $data->date;
    }

    public function setPhoto($id)
    {
        $data = ApdRequest::find($id);
        $this->data_photo = $data->apdHandoverPhoto;
    }

    public function handleDelete($paramId)
    {
        DB::beginTransaction();

        try {
            ApdRequest::where('id', intval($paramId))->delete();

            DB::commit();

            return redirect()->route('k3.apd')->with('success', 'APD Request has been deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public function handleHandover($paramId)
    {
        $this->validate([
            'receiver_id' => 'required',
            'date' => 'required',
            'description' => 'nullable',
            'attachment' => 'required',
            'photo.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
        ], [], [
            'receiver_id' => 'Receiver',
            'date' => 'Date',
            'description' => 'Description',
            'attachment' => 'Attachment',
            'photo.*' => 'Photo',

        ]);

        DB::beginTransaction();
        $app_request = ApdRequest::find($paramId);

        $this->attachment->store('apd/handover', 'public');

        foreach ($this->photo as $photo) {
            $photo->store('apd/handover/photo', 'public');
        }

        try {
            $save = $app_request->apdHandover()->create([
                'receiver_id' => $this->receiver_id,
                'handover_by' => auth()->user()->id,
                'date' => $this->date,
                'description' => $this->description,
                'attachment' => $this->attachment->hashName(),
            ]);


            foreach ($this->photo as $photo) {
                $save->apdHandoverPhoto()->create([
                    'photo' => $photo->hashName(),
                ]);
            }

            DB::commit();

            return redirect()->route('k3.apd')->with('success', 'APD Request has been handed over');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }
}
