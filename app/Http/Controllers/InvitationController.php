<?php

namespace App\Http\Controllers;

use App\Enums\GuestStatus;
use App\Models\Guest;
use App\Models\Invitation;
use App\Models\Wish;
use App\Services\GuestService;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    public function show(string $slug, Request $request)
    {
        $invitation = Invitation::where('slug', $slug)
            ->with(['theme', 'photos', 'wishes' => fn($q) => $q->latest()->limit(50)])
            ->firstOrFail();

        // Check for specific guest
        $guest = null;
        $guestSlugOrName = $request->get('to') ?? $request->get('kpd');

        if ($guestSlugOrName) {
            // Try to find by slug first
            $guest = Guest::where('invitation_id', $invitation->id)
                ->where('slug', $guestSlugOrName)
                ->first();

            // If not found by slug, and it's a 'kpd' parameter (likely a name), create a transient guest instance
            if (!$guest && $request->has('kpd')) {
                $guest = new Guest([
                    'name' => urldecode($request->get('kpd')),
                    'invitation_id' => $invitation->id,
                ]);
            }
        }

        // Determine which view to use based on theme
        $viewName = 'invitation.show'; // Default fallback
        
        if ($invitation->theme && $invitation->theme->view_file) {
            $themeView = $invitation->theme->view_file;
            if (view()->exists($themeView)) {
                $viewName = $themeView;
            }
        }

        return view($viewName, [
            'invitation' => $invitation,
            'guest' => $guest,
        ]);
    }

    public function rsvp(string $slug, Request $request, GuestService $guestService)
    {
        $invitation = Invitation::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:confirmed,declined',
            'pax' => 'nullable|integer|min:1|max:10',
        ]);

        // Find or create guest
        $guest = Guest::where('invitation_id', $invitation->id)
            ->where('name', $validated['name'])
            ->first();

        if ($guest) {
            $guestService->updateRsvp(
                $guest,
                GuestStatus::from($validated['status']),
                $validated['pax'] ?? 1
            );
        } else {
            $guest = $guestService->addGuest($invitation, [
                'name' => $validated['name'],
                'status' => $validated['status'],
                'pax' => $validated['pax'] ?? 1,
            ]);
            // Update status after creation
            $guestService->updateRsvp($guest, GuestStatus::from($validated['status']), $validated['pax'] ?? 1);
        }

        return back()->with('rsvp_success', 'Terima kasih! RSVP Anda telah tercatat.');
    }

    public function wish(string $slug, Request $request)
    {
        $invitation = Invitation::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        Wish::create([
            'invitation_id' => $invitation->id,
            'name' => $validated['name'],
            'message' => $validated['message'],
        ]);

        return back()->with('wish_success', 'Terima kasih atas ucapan Anda!');
    }
}
