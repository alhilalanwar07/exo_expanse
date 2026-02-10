<?php

namespace App\Livewire\Themes;

use App\Models\Invitation;
use Livewire\Component;

class RusticSage extends Component
{
    public Invitation $invitation;

    public ?array $metadata = null;

    public ?string $guestName = null;

    public function mount(Invitation $invitation, ?array $metadata = null): void
    {
        $this->invitation = $invitation;
        $this->metadata = $metadata;
        $this->guestName = request('kpd', 'Tamu Undangan');
    }

    public $rsvpName;
    public $rsvpStatus = 'Hadir';
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
            'status' => $this->rsvpStatus,
            'guests' => $this->rsvpGuests,
            'message' => $this->rsvpMessage,
        ]);

        $this->reset(['rsvpName', 'rsvpStatus', 'rsvpGuests', 'rsvpMessage']);
        session()->flash('message', 'Terima kasih, ucapan Anda telah terkirim!');
    }

    public function render()
    {
        return view('livewire.themes.rustic-sage');
    }
}
