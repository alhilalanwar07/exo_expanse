<?php

namespace App\Services;

use App\Enums\GuestStatus;
use App\Models\Guest;
use App\Models\Invitation;
use Illuminate\Support\Str;

class GuestService
{
    /**
     * Add a guest to an invitation.
     */
    public function addGuest(Invitation $invitation, array $data): Guest
    {
        $data['invitation_id'] = $invitation->id;
        $data['slug'] = $this->generateGuestSlug($data['name']);
        $data['status'] = GuestStatus::PENDING->value;

        return Guest::create($data);
    }

    /**
     * Bulk import guests from array.
     */
    public function bulkImport(Invitation $invitation, array $guests): int
    {
        $count = 0;

        foreach ($guests as $guestData) {
            $this->addGuest($invitation, $guestData);
            $count++;
        }

        return $count;
    }

    /**
     * Update guest RSVP status.
     */
    public function updateRsvp(Guest $guest, GuestStatus $status, int $pax = 1): Guest
    {
        $guest->update([
            'status' => $status->value,
            'pax' => $pax,
        ]);

        return $guest->refresh();
    }

    /**
     * Generate a unique slug for guest-specific invitation link.
     */
    private function generateGuestSlug(string $name): string
    {
        return Str::slug($name) . '-' . Str::random(4);
    }

    /**
     * Get RSVP statistics for an invitation.
     */
    public function getRsvpStats(Invitation $invitation): array
    {
        $guests = $invitation->guests;

        return [
            'total' => $guests->count(),
            'confirmed' => $guests->where('status', GuestStatus::CONFIRMED->value)->count(),
            'declined' => $guests->where('status', GuestStatus::DECLINED->value)->count(),
            'pending' => $guests->where('status', GuestStatus::PENDING->value)->count(),
            'total_pax' => $guests->where('status', GuestStatus::CONFIRMED->value)->sum('pax'),
        ];
    }
}
