<?php

namespace App\Livewire\Themes;

use App\Models\Invitation;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AdatBone extends Component
{
    #[Locked]
    public Invitation $invitation;

    #[Locked]
    public ?array $metadata = null;

    #[Locked]
    public ?string $guestName = null;

    #[Validate('required|string|min:3|max:255')]
    public string $rsvpName = '';

    #[Validate('required|string')]
    public string $rsvpStatus = 'confirmed';

    #[Validate('required|integer|min:1|max:10')]
    public int $rsvpGuests = 1;

    #[Validate('required|string|min:5|max:1000')]
    public string $rsvpMessage = '';

    public function mount(Invitation $invitation, ?array $metadata = null): void
    {
        $this->invitation = $invitation;
        $this->metadata = $metadata;
        $this->guestName = request('kpd', 'Tamu Undangan');
    }

    public function submitRSVP(): void
    {
        $this->validate();

        $this->invitation->wishes()->create([
            'name' => $this->rsvpName,
            'attendance_status' => $this->rsvpStatus,
            'pax' => $this->rsvpGuests,
            'message' => $this->rsvpMessage,
        ]);

        $this->reset(['rsvpName', 'rsvpStatus', 'rsvpGuests', 'rsvpMessage']);
        session()->flash('message', 'Terima kasih, ucapan dan doa Anda telah tersimpan!');
    }

    public function render()
    {
        return view('livewire.themes.adat-bone');
    }
}
