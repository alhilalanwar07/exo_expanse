<?php

namespace App\Livewire\Themes;

use App\Models\Invitation;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AdatBugis02 extends Component
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

    #[Validate('required|string|min:3|max:1000')]
    public string $rsvpMessage = '';

    public function mount(Invitation $invitation, ?array $metadata = null): void
    {
        $this->invitation = $invitation;
        $this->metadata = $metadata;
        $this->guestName = request('to', request('kpd', 'Tamu Undangan'));
        if ($this->guestName !== 'Tamu Undangan' && $this->guestName !== 'Nama Tamu') {
            $this->rsvpName = $this->guestName;
        }
    }

    public function submitRSVP(): void
    {
        $this->validate();

        $this->invitation->wishes()->create([
            'name' => $this->rsvpName,
            'attendance_status' => $this->rsvpStatus,
            'pax' => $this->rsvpStatus === 'confirmed' ? $this->rsvpGuests : 1,
            'message' => $this->rsvpMessage,
        ]);

        $this->reset(['rsvpMessage']);
        session()->flash('rsvp_success', 'Terima kasih, ucapan dan konfirmasi kehadiran Anda telah tersimpan!');
    }

    public function render()
    {
        $wishes = $this->invitation->wishes()->latest()->take(50)->get();
        $totalWishesCount = $this->invitation->wishes()->count();

        return view('livewire.themes.3d-adat-bugis-02', [
            'wishes' => $wishes,
            'totalWishesCount' => $totalWishesCount,
        ]);
    }
}
