<?php

namespace App\Livewire;

use App\Enums\GuestStatus;
use App\Models\Guest;
use Illuminate\Validation\Rule;
use Livewire\Component;

class RsvpForm extends Component
{
    public $invitationId; // Diterima dari parameter

    // State Form
    public $name;

    public $status = 'confirmed';

    public $total_guests = 1;

    public function mount($invitationId)
    {
        $this->invitationId = $invitationId;
    }

    protected function rules()
    {
        return [
            'name' => 'required|min:3',
            'status' => ['required', Rule::enum(GuestStatus::class)],
            'total_guests' => 'required|integer|min:1|max:5',
        ];
    }

    public function submit()
    {
        $this->validate();

        Guest::create([
            'invitation_id' => $this->invitationId,
            'name' => $this->name,
            'slug' => \Illuminate\Support\Str::slug($this->name).'-'.\Illuminate\Support\Str::random(6),
            'status' => $this->status,
            'pax' => $this->status === 'confirmed' ? $this->total_guests : 0,
        ]);

        // Reset form dan beri notifikasi
        $this->reset(['name', 'status', 'total_guests']);
        session()->flash('message', 'Terima kasih atas konfirmasinya!');
    }

    public function render()
    {
        return view('livewire.rsvp-form');
    }
}
