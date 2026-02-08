<?php

namespace App\Livewire;

use App\Models\Wish;
use Livewire\Component;

class WishesFeed extends Component
{
    public $invitationId;

    public $name;

    public $message;

    protected $rules = [
        'name' => 'required|min:3',
        'message' => 'required|min:5',
    ];

    public function mount($invitationId)
    {
        $this->invitationId = $invitationId;
    }

    public function submit()
    {
        $this->validate();

        Wish::create([
            'invitation_id' => $this->invitationId,
            'name' => $this->name,
            'message' => $this->message,
        ]);

        $this->reset(['name', 'message']);
        session()->flash('message', 'Ucapan terkirim!');
    }

    public function render()
    {
        return view('livewire.wishes-feed', [
            'wishes' => Wish::where('invitation_id', $this->invitationId)->latest()->get(),
        ]);
    }
}
