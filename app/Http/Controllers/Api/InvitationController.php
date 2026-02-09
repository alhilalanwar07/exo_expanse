<?php

namespace App\Http\Controllers\Api;

use App\Enums\GuestStatus;
use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\Invitation;
use App\Models\Wish;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    public function submitRsvp(Request $request, Invitation $invitation): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:confirmed,declined',
            'pax' => 'required|integer|min:1|max:10',
        ]);

        $guest = Guest::updateOrCreate(
            [
                'invitation_id' => $invitation->id,
                'name' => $validated['name'],
            ],
            [
                'slug' => \Illuminate\Support\Str::slug($validated['name']),
                'status' => GuestStatus::from($validated['status']),
                'pax' => $validated['status'] === 'confirmed' ? $validated['pax'] : 0,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Konfirmasi kehadiran berhasil disimpan!',
            'guest' => $guest,
        ]);
    }

    public function submitWish(Request $request, Invitation $invitation): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        $wish = Wish::create([
            'invitation_id' => $invitation->id,
            'name' => $validated['name'],
            'message' => $validated['message'],
        ]);

        // Get attendance status from guest table
        $guest = Guest::where('invitation_id', $invitation->id)
            ->where('name', $validated['name'])
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Ucapan berhasil dikirim!',
            'wish' => [
                'id' => $wish->id,
                'name' => $wish->name,
                'message' => $wish->message,
                'initial' => strtoupper(substr($wish->name, 0, 1)),
                'time' => $wish->created_at->diffForHumans(),
                'attendance_status' => $guest?->status?->value ?? null,
            ],
        ]);
    }

    public function getWishes(Request $request, Invitation $invitation): JsonResponse
    {
        $limit = $request->get('limit', 10);
        $offset = $request->get('offset', 0);

        // Get all guest names and statuses for this invitation
        $guestStatuses = Guest::where('invitation_id', $invitation->id)
            ->pluck('status', 'name')
            ->toArray();

        $wishes = $invitation->wishes()
            ->latest()
            ->skip($offset)
            ->take($limit)
            ->get()
            ->map(fn ($wish) => [
                'id' => $wish->id,
                'name' => $wish->name,
                'message' => $wish->message,
                'initial' => strtoupper(substr($wish->name, 0, 1)),
                'time' => $wish->created_at->diffForHumans(),
                'attendance_status' => isset($guestStatuses[$wish->name]) ? $guestStatuses[$wish->name]->value : null,
            ]);

        return response()->json([
            'wishes' => $wishes,
            'total' => $invitation->wishes()->count(),
        ]);
    }

    public function getStats(Invitation $invitation): JsonResponse
    {
        $totalWishes = $invitation->wishes()->count();
        $totalConfirmed = $invitation->guests()->where('status', 'confirmed')->sum('pax');
        $totalGuests = $invitation->guests()->count();

        return response()->json([
            'total_wishes' => $totalWishes,
            'total_confirmed' => $totalConfirmed,
            'total_guests' => $totalGuests,
        ]);
    }
}
