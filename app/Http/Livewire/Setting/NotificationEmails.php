<?php

namespace App\Http\Livewire\Setting;

use App\Models\NotificationEmail;
use App\Models\NotificationEmailType;
use Livewire\Component;

class NotificationEmails extends Component
{
    public $isOpen = false;
    public $emails;
    public $name;
    public $email;
    public $type_id;
    public $types;
    public $checked_types = [];
    public $selectedEmail;

    public function mount()
    {
        $this->emails = NotificationEmail::with('types')->get();
        $this->types = NotificationEmailType::with('emails')->get();

        if ($this->types->count() > 0) {
            $this->type_id = $this->types->first()->id;
        }
    }
    public function render()
    {
        return view('livewire.setting.notification-emails')->layout(null);
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function edit($id)
    {
        $email = NotificationEmail::find($id);
        $this->selectedEmail = $email->id;
        $this->name = $email->name;
        $this->email = $email->email;
        foreach ($email->types as $type) {
            $this->checked_types[$type->id] = true;
        }
        $this->openModal();
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email'
        ]);

        $email = NotificationEmail::where('email', $this->email)->first();

        if (is_null($email)) {
            $email = NotificationEmail::create([
                'name' => $this->name,
                'email' => $this->email,
                'type_id' => $this->type_id
            ]);
        }

        $email->types()->syncWithoutDetaching($this->type_id);

        return redirect()->route('settings')->with('success', 'Notification Email added successfully');
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email'
        ]);

        $email = NotificationEmail::where('id', $this->selectedEmail)->first();
        if ($email) {
            $email->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);

            $checked = [];
            foreach ($this->checked_types as $key => $value) {
                if ($value) {
                    $checked[] = $key;
                }
            }

            $email->types()->sync($checked);
        }

        return redirect()->route('settings')->with('success', 'Notification Email updated successfully');
    }

    public function delete($id)
    {
        NotificationEmail::find($id)->delete();
        return redirect()->route('settings')->with('success', 'Notification Email deleted successfully');
    }
}
