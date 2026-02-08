<?php

namespace App\Livewire\Invitation;

use App\Enums\GuestStatus;
use App\Models\Guest;
use App\Models\Invitation;
use App\Services\GuestService;
use Livewire\Component;

class RsvpForm extends Component
{
    public Invitation $invitation;

    public ?Guest $guest = null; // Currently identified guest

    public string $theme = 'rose';

    public ?string $name = null;

    public ?string $status = null;

    public ?int $pax = 1;

    public bool $isSubmitted = false;

    public bool $isSuccess = false;

    public function mount(Invitation $invitation, ?Guest $guest = null, string $theme = 'rose')
    {
        $this->invitation = $invitation;
        $this->guest = $guest;
        $this->theme = $theme;

        if ($guest) {
            $this->name = $guest->name;
            $this->status = $guest->status?->value;
            $this->pax = $guest->pax;
        }

    }

    public function save(GuestService $guestService)
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:confirmed,declined',
            'pax' => 'required|integer|min:1|max:5',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'status.required' => 'Mohon pilih konfirmasi kehadiran.',
        ]);

        // If guest exists, update. else create/find by name.
        if ($this->guest) {
            $guestService->updateRsvp(
                $this->guest,
                GuestStatus::from($validated['status']),
                $validated['pax']
            );
        } else {
            // Try determine by name if not logged in
            $existingGuest = Guest::where('invitation_id', $this->invitation->id)
                ->where('name', $validated['name'])
                ->first();

            if ($existingGuest) {
                $guestService->updateRsvp(
                    $existingGuest,
                    GuestStatus::from($validated['status']),
                    $validated['pax']
                );
            } else {
                // New Guest RSVP
                $guestService->addGuest($this->invitation, [
                    'name' => $validated['name'],
                    'status' => $validated['status'],
                    'pax' => $validated['pax'],
                ]);
            }
        }

        $this->isSubmitted = true;
        session()->flash('rsvp_success', 'Terima kasih! Konfirmasi kehadiran Anda telah tersimpan.');
    }

    public function render()
    {
        return view('livewire.invitation.rsvp-form');
    }
}
