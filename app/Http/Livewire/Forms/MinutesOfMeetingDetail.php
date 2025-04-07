<?php

namespace App\Http\Livewire\Forms;

use Livewire\Component;

class MinutesOfMeetingDetail extends Component
{
    public $mom;
    public $meeting;
    public $comments = [];

    public function mount(\App\Models\MinutesOfMeeting $mom)
    {
        $this->mom = $mom;
        $this->meeting = $mom::with(['points', 'participants'])->findOrFail($this->mom->id);

        $this->comments = is_string($this->meeting->comment) && json_decode($this->meeting->comment) !== null ? $this->meeting->comment : '[]';
    }

    public function render()
    {
        return view('livewire.forms.minutes-of-meeting-detail');
    }
}
