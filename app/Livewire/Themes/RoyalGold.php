<?php

namespace App\Livewire\Themes;

use App\Models\Invitation;
use Livewire\Component;

class RoyalGold extends Component
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

    public function render()
    {
        return view('livewire.themes.royal-gold');
    }
}
