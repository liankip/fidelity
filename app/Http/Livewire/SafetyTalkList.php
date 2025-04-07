<?php

namespace App\Http\Livewire;

use App\Models\SafetyTalkModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class SafetyTalkList extends Component
{
    use WithFileUploads;

    public $activityDate;
    public $locationName;
    public $jobName;
    public $executorStatus;
    public $fileUpload;

    public $paramID;
    public $specificSafetyTalk;
    public $editDate;
    public $editLocation;
    public $editJob;
    public $editExecutor;
    public $editFile;

    public $deleteId;

    public function setDelete($param)
    {
        $this->deleteId = $param;
    }

    public function setParam($id)
    {
        $this->paramID = $id;
        $this->specificSafetyTalk = SafetyTalkModel::where('id', intval($this->paramID))->first();
        $this->editDate = $this->specificSafetyTalk->activity_date;
        $this->editJob = $this->specificSafetyTalk->job_status;
        $this->editExecutor = $this->specificSafetyTalk->executor;
        $this->editLocation = $this->specificSafetyTalk->location;
    }

    public function handleSubmit()
    {
        DB::beginTransaction();

        try {
            $activityDate = $this->activityDate;
            $fileUrl = $this->fileUpload->store('safety_talk', 'public');

            $safetyTalk = new SafetyTalkModel();
            $safetyTalk->activity_date = $activityDate;
            $safetyTalk->location = $this->locationName;
            $safetyTalk->job_status = $this->jobName;
            $safetyTalk->executor = $this->executorStatus;
            $safetyTalk->file_upload = $fileUrl;
            $safetyTalk->updated_by = auth()->user()->id;

            $safetyTalk->save();

            DB::commit();
            return redirect()->route('safety-talk.index')->with('success', 'Data has been created');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('safety-talk.index')->with('fail', 'Error Uploading');
        }
    }

    public function handleUpdate()
    {
        DB::beginTransaction();

        try {
            $dataSafetyTalk = SafetyTalkModel::findOrFail($this->paramID);

            if ($dataSafetyTalk) {
                $dataSafetyTalk->activity_date = $this->editDate;
                $dataSafetyTalk->location = $this->editLocation;
                $dataSafetyTalk->job_status = $this->editJob;
                $dataSafetyTalk->executor = $this->editExecutor;

                if ($this->editFile === null) {
                    $fileUrl = $dataSafetyTalk->file_upload;
                } else {
                    $fileUrl = $this->editFile->store('safety_talk', 'public');
                }

                $dataSafetyTalk->file_upload = $fileUrl;
                $dataSafetyTalk->updated_by = auth()->user()->id;
                $dataSafetyTalk->save();

                DB::commit();
                return redirect()->route('safety-talk.index')->with('success', 'Data has been updated');
            } else {
                return redirect()->route('safety-talk.index')->with('fail', 'Data not found');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->route('safety-talk.index')->with('fail', 'Error updating');
        }
    }

    public function handleDelete($id)
    {
        DB::beginTransaction();

        try {
            $dataSafetyTalk = SafetyTalkModel::where('id', intval($id))->delete();

            DB::commit();
            return redirect()->route('safety-talk.index')->with('success', 'Data has been deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->route('safety-talk.index')->with('fail', 'Error deleting');
        }
    }

    public function render()
    {
        $dataSafetyTalk = SafetyTalkModel::all();
        return view('livewire.safety-talk-list', compact('dataSafetyTalk'));
    }
}
