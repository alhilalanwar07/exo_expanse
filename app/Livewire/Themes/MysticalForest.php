<?php

namespace App\Livewire\Themes;

use App\Models\Invitation;
use Livewire\Component;

class MysticalForest extends Component
{
    public Invitation $invitation;

    public ?array $metadata = null;

    public ?string $guestName = null;

    public function mount(Invitation $invitation, ?array $metadata = null): void
    {
        $this->invitation = $invitation;
        $this->metadata = $metadata;
        $this->guestName = request('kpd', 'Guest');
    }

    public $rsvpName;
    public $rsvpStatus = 'confirmed';
    public $rsvpGuests = 1;
    public $rsvpMessage;

    protected $rules = [
        'rsvpName' => 'required|min:3',
        'rsvpStatus' => 'required',
        'rsvpGuests' => 'required|integer|min:1',
        'rsvpMessage' => 'required|min:5',
    ];

    public function submitRSVP()
    {
        $this->validate();

        $this->invitation->wishes()->create([
            'name' => $this->rsvpName,
            'status' => $this->rsvpStatus === 'confirmed' ? 'Hadir' : 'Maaf, Tidak Bisa Hadir',
            'guests' => $this->rsvpGuests,
            'message' => $this->rsvpMessage,
        ]);

        $this->reset(['rsvpName', 'rsvpStatus', 'rsvpGuests', 'rsvpMessage']);
        session()->flash('message', 'Thank you, your wish has been sent to the realm!');
    }

    public function render()
    {
        return view('livewire.themes.mystical-forest');
    }
}
