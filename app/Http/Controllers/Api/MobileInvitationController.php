<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileInvitationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->attributes->get('mobileUser');

        if (! $user instanceof User) {
            return response()->json([
                'message' => 'Unauthorized.',
            ], 401);
        }

        $invitationsQuery = $user->invitations()
            ->with('theme')
            ->withCount(['wishes'])
            ->withCount([
                'guests as guests_count',
                'guests as guests_confirmed_count' => function ($query) {
                    $query->where('status', 'confirmed');
                }
            ]);

        $totalWishes = $invitationsQuery->get()->sum('wishes_count');
        $totalTamu = $invitationsQuery->get()->sum('guests_count');
        $tamuHadir = $invitationsQuery->get()->sum('guests_confirmed_count');
        $totalUndangan = $invitationsQuery->count();

        $invitations = $invitationsQuery
            ->latest()
            ->get()
            ->map(function ($invitation) {
                // Map status based on 'is_published' or date
                $status = $invitation->is_published ? 'Aktif' : 'Draf';
                
                return [
                    'id' => (string) $invitation->id,
                    'title' => $invitation->title ?? 'Undangan Tanpa Judul',
                    'theme' => $invitation->theme ? $invitation->theme->name : 'Digital Classic',
                    'slug' => $invitation->slug ?? 'undang.in/u/'.strtolower(str_replace(' ', '-', $invitation->title)),
                    'date' => $invitation->akad_date ? $invitation->akad_date->format('d F Y') : ($invitation->resepsi_date ? $invitation->resepsi_date->format('d F Y') : '-'),
                    'status' => $status,
                    'thumbnail' => $invitation->cover_image ? url('storage/' . $invitation->cover_image) : 'https://picsum.photos/200/300?random=' . $invitation->id,
                ];
            });

        return response()->json([
            'stats' => [
                'total_undangan' => $totalUndangan,
                'total_tamu' => $totalTamu,
                'tamu_hadir' => $tamuHadir,
                'total_ucapan' => $totalWishes,
            ],
            'data' => $invitations,
        ]);
    }

    public function show(Request $request, $id): JsonResponse
    {
        $user = $request->attributes->get('mobileUser');

        if (! $user instanceof User) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        $invitation = $user->invitations()
            ->with(['photos'])
            ->find($id);

        if (!$invitation) {
            return response()->json(['message' => 'Undangan tidak ditemukan.'], 404);
        }

        // Gather stats
        $totalWishes = $invitation->wishes()->count();
        $totalTamu = $invitation->guests()->count();
        $tamuHadir = $invitation->guests()->where('status', 'confirmed')->sum('pax');

        return response()->json([
            'data' => [
                'id' => $invitation->id,
                'title' => $invitation->title,
                'theme' => $invitation->theme ? $invitation->theme->name : 'Digital Classic',
                'slug' => $invitation->slug,
                'bride' => [
                    'name' => $invitation->bride_name,
                    'nickname' => $invitation->bride_nickname,
                    'photo' => $invitation->bride_photo ? url('storage/' . $invitation->bride_photo) : null,
                ],
                'groom' => [
                    'name' => $invitation->groom_name,
                    'nickname' => $invitation->groom_nickname,
                    'photo' => $invitation->groom_photo ? url('storage/' . $invitation->groom_photo) : null,
                ],
                'akad' => [
                    'date' => $invitation->akad_date ? $invitation->akad_date->format('Y-m-d') : null,
                    'time' => $invitation->akad_time,
                    'venue' => $invitation->akad_venue,
                    'address' => $invitation->akad_address,
                ],
                'resepsi' => [
                    'date' => $invitation->resepsi_date ? $invitation->resepsi_date->format('Y-m-d') : null,
                    'time' => $invitation->resepsi_time,
                    'venue' => $invitation->resepsi_venue,
                    'address' => $invitation->resepsi_address,
                ],
                'photos' => $invitation->photos->map(function ($photo) {
                    return [
                        'id' => $photo->id,
                        'url' => $photo->url, // from getUrlAttribute
                        'caption' => $photo->caption,
                        'order' => $photo->order,
                    ];
                }),
                'stats' => [
                    'total_undangan' => 1, // Mock or based on specific logic
                    'total_tamu' => $totalTamu,
                    'tamu_hadir' => $tamuHadir,
                    'total_ucapan' => $totalWishes,
                ],
            ]
        ]);
    }
}
