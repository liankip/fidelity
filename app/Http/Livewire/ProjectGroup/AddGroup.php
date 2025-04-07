<?php

namespace App\Http\Livewire\ProjectGroup;

use App\Models\ProjectGroup;
use Illuminate\Support\Facades\URL;
use Livewire\Component;

class AddGroup extends Component
{
    public $name;
    public $showModal = false;
    public $currentUrl;

    public function mount()
    {
        $this->currentUrl = URL::current();
    }

    public function render()
    {
        return view('livewire.project-group.add-group')->layout(null);
    }

    public function toggleModal()
    {
        $this->showModal = !$this->showModal;
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|unique:project_groups,name',
        ]);

        ProjectGroup::create([
            'name' => $this->name,
        ]);

        return redirect($this->currentUrl)->with('success', 'Project Group Created Successfully');

    }

}
