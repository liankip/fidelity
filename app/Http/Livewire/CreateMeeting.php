<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\MeetingFormModel;
use Illuminate\Http\Request;


class CreateMeeting extends Component
{
    public $paramId;
    public $selectedOptions = [];
    public $notulenRapat = [];
    public $guestList = [];
    public $meetingDate;
    public $meetingLocation;
    public $meetingAttendant = [
        'employee' => [],
        'guest' => []
    ];
    public $notulensi;

    protected $listeners = ['addGuest'];

    public function mount(Request $request)
    {
        $this->paramId = $request->query('id');

        if ($this->paramId) {
            $dataMeeting = MeetingFormModel::where('id', intval($this->paramId))->first();
            $meetingAttendant = json_decode($dataMeeting->meeting_attendant, true);
            $meetingNotulen = json_decode($dataMeeting->meeting_notulen, true);

            $this->selectedOptions = $meetingAttendant['employee'] ?? [];
            $this->guestList = $meetingAttendant['guest'] ?? [];
            $this->notulenRapat = $meetingNotulen ?? [];
            $this->meetingDate = $dataMeeting->meeting_date;
            $this->meetingLocation = $dataMeeting->meeting_location;
            $this->notulensi = $dataMeeting->notulensi;
        } else {
            $this->notulenRapat[] = '';
        }
    }

    public function render()
    {

        $dataUser = User::where('active', 1)->get();
        return view('livewire.create-meeting', compact('dataUser'));
    }

    public function updateSelected($selectedOption)
    {
        if (!in_array($selectedOption, $this->selectedOptions) && $selectedOption !== '') {
            $this->selectedOptions[] = $selectedOption;
        }
    }

    public function addGuest($guest)
    {
        if (!in_array($guest, $this->guestList) && $guest !== '') {
            $this->guestList[] = $guest;
        }
    }

    public function removeSelected($index)
    {
        unset($this->selectedOptions[$index]);
        $this->selectedOptions = array_values($this->selectedOptions);
    }

    public function removeGuest($index)
    {
        unset($this->guestList[$index]);
        $this->guestList = array_values($this->guestList);
    }

    public function addNotulenRapat()
    {
        $this->notulenRapat[] = '';
    }

    public function removeNotulenRapat($index)
    {
        unset($this->notulenRapat[$index]);
    }

    public function handleSubmit()
    {
        DB::beginTransaction();

        try {
            $this->meetingAttendant['employee'] = array_merge($this->meetingAttendant['employee'], $this->selectedOptions);
            $this->meetingAttendant['guest'] = array_merge($this->meetingAttendant['guest'], $this->guestList);

            if ($this->paramId) {
                $meeting = MeetingFormModel::findOrFail($this->paramId);
                $message = 'Data has been updated';
            } else {
                $meeting = new MeetingFormModel();
                $message = 'Data has been added';
            }

            $meeting->meeting_notulen = $this->notulenRapat;
            $meeting->meeting_date = $this->meetingDate;
            $meeting->meeting_location = $this->meetingLocation;
            $meeting->notulensi = $this->notulensi;
            $meeting->meeting_attendant = $this->meetingAttendant;

            $meeting->meeting_notulen = json_encode($meeting->meeting_notulen);
            $meeting->meeting_attendant = json_encode($meeting->meeting_attendant);

            $meeting->save();

            DB::commit();
            return redirect()->route('meeting.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('meeting.index')->with('fail', 'Data submission failed');
        }
    }
}
