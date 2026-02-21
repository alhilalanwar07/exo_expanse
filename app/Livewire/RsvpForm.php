<?php

namespace App\Livewire;

use App\Models\Guest;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class RsvpForm extends Component
{
    #[Locked]
    public int $invitationId;

    #[Validate('required|string|min:3|max:255')]
    public string $name = '';

    #[Validate('required|string')]
    public string $status = 'confirmed';

    #[Validate('required|integer|min:1|max:5')]
    public int $total_guests = 1;

    public function mount(int $invitationId): void
    {
        $this->invitationId = $invitationId;
    }

    public function submit(): void
    {
        $this->validate();

        Guest::create([
            'invitation_id' => $this->invitationId,
            'name' => $this->name,
            'slug' => Str::slug($this->name).'-'.Str::random(6),
            'status' => $this->status,
            'pax' => $this->status === 'confirmed' ? $this->total_guests : 0,
        ]);

        $this->reset(['name', 'status', 'total_guests']);
        session()->flash('message', 'Terima kasih atas konfirmasinya!');
    }

    public function render()
    {
        return view('livewire.rsvp-form');
    }
}
