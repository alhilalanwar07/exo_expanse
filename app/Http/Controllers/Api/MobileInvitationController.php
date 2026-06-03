<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\Invitation;
use App\Models\InvitationPhoto;
use App\Models\Theme;
use App\Models\User;
use App\Services\GuestImportService;
use App\Services\GuestService;
use App\Services\ImageService;
use App\Services\InvitationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MobileInvitationController extends Controller
{
    // ── List ──────────────────────────────────────────────────────────────────

    public function index(Request $request): JsonResponse
    {
        $user = $request->attributes->get('mobileUser');
        if (! $user instanceof User) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        $all = $user->invitations()
            ->with('theme')
            ->withCount(['wishes', 'guests as guests_count',
                'guests as guests_confirmed_count' => fn ($q) => $q->where('status', 'confirmed')])
            ->get();

        return response()->json([
            'stats' => [
                'total_undangan' => $all->count(),
                'total_tamu' => $all->sum('guests_count'),
                'tamu_hadir' => $all->sum('guests_confirmed_count'),
                'total_ucapan' => $all->sum('wishes_count'),
            ],
            'data' => $all->sortByDesc('created_at')->values()->map(fn ($inv) => [
                'id' => (string) $inv->id,
                'title' => $inv->title ?? 'Undangan Tanpa Judul',
                'theme' => $inv->theme?->name ?? 'Digital Classic',
                'slug' => $inv->slug ?? '',
                'date' => $inv->akad_date?->format('d F Y') ?? $inv->resepsi_date?->format('d F Y') ?? '-',
                'status' => $inv->is_published ? 'Aktif' : 'Draf',
                'thumbnail' => $inv->cover_image ? url('storage/'.$inv->cover_image) : null,
                'url' => url('/i/'.$inv->slug),
            ]),
        ]);
    }

    // ── Show (flat for form) ──────────────────────────────────────────────────

    public function show(Request $request, $id): JsonResponse
    {
        $user = $request->attributes->get('mobileUser');
        if (! $user instanceof User) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        $invitation = $user->invitations()->with(['photos'])->find($id);
        if (! $invitation) {
            return response()->json(['message' => 'Undangan tidak ditemukan.'], 404);
        }

        $styles = $invitation->custom_styles ?? [];

        return response()->json([
            'data' => [
                'id' => $invitation->id,
                'title' => $invitation->title,
                'slug' => $invitation->slug,
                'is_published' => $invitation->is_published,
                'cover_photo' => $invitation->cover_image ? url('storage/'.$invitation->cover_image) : null,
                'groom_name' => $invitation->groom_name,
                'groom_nickname' => $invitation->groom_nickname,
                'groom_father' => $invitation->groom_father,
                'groom_mother' => $invitation->groom_mother,
                'instagram_groom' => $invitation->instagram_groom,
                'groom_photo' => $invitation->groom_photo ? url('storage/'.$invitation->groom_photo) : null,
                'bride_name' => $invitation->bride_name,
                'bride_nickname' => $invitation->bride_nickname,
                'bride_father' => $invitation->bride_father,
                'bride_mother' => $invitation->bride_mother,
                'instagram_bride' => $invitation->instagram_bride,
                'bride_photo' => $invitation->bride_photo ? url('storage/'.$invitation->bride_photo) : null,
                'akad_date' => $invitation->akad_date?->format('Y-m-d'),
                'akad_time' => $invitation->akad_time,
                'akad_venue' => $invitation->akad_venue,
                'akad_address' => $invitation->akad_address,
                'akad_maps_link' => $invitation->akad_maps_link,
                'resepsi_date' => $invitation->resepsi_date?->format('Y-m-d'),
                'resepsi_time' => $invitation->resepsi_time,
                'resepsi_venue' => $invitation->resepsi_venue,
                'resepsi_address' => $invitation->resepsi_address,
                'resepsi_maps_link' => $invitation->resepsi_maps_link,
                'enable_rsvp' => $invitation->enable_rsvp ?? true,
                'enable_wishes' => $invitation->enable_wishes ?? true,
                'enable_gallery' => $invitation->enable_gallery ?? true,
                'background_music' => $invitation->background_music,
                'music_id' => $invitation->music_id,
                'love_story' => $invitation->love_story ?? [],
                'gallery_photos' => $invitation->photos->map(fn ($p) => [
                    'id' => $p->id,
                    'url' => $p->url,
                ]),
                'custom_styles' => [
                    'event_type' => $styles['event_type'] ?? 'both',
                    'name_order' => $styles['name_order'] ?? 'groom_first',
                    'cover_title' => $styles['cover_title'] ?? '',
                    'cover_subtitle' => $styles['cover_subtitle'] ?? 'The Wedding of',
                    'welcome_message' => $styles['welcome_message'] ?? '',
                    'quran_verse' => $styles['quran_verse'] ?? '',
                    'countdown_enabled' => $styles['countdown_enabled'] ?? true,
                ],
            ],
        ]);
    }

    // ── Store ─────────────────────────────────────────────────────────────────

    public function store(Request $request): JsonResponse
    {
        $user = $request->attributes->get('mobileUser');
        if (! $user instanceof User) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'groom_name' => 'required|string|max:255',
        ]);

        $slug = $this->uniqueSlug(Str::slug($request->input('slug') ?: $request->input('title')));
        $data = $this->buildData($request, $slug);
        $invitation = app(InvitationService::class)->create($user, $data);

        return response()->json([
            'message' => 'Undangan berhasil dibuat.',
            'data' => ['id' => $invitation->id, 'slug' => $invitation->slug],
        ], 201);
    }

    // ── Update ────────────────────────────────────────────────────────────────

    public function update(Request $request, $id): JsonResponse
    {
        $user = $request->attributes->get('mobileUser');
        if (! $user instanceof User) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        $invitation = $user->invitations()->findOrFail($id);
        $slug = $this->uniqueSlug(
            Str::slug($request->input('slug') ?: $invitation->slug),
            $invitation->id
        );

        app(InvitationService::class)->update($invitation, $this->buildData($request, $slug));

        return response()->json(['message' => 'Undangan berhasil diperbarui.']);
    }

    public function updateTheme(Request $request, $id): JsonResponse
    {
        $user = $request->attributes->get('mobileUser');
        if (! $user instanceof User) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        $validated = $request->validate([
            'theme_id' => 'required|integer|exists:themes,id',
        ]);

        $invitation = $user->invitations()->findOrFail($id);

        $theme = Theme::query()
            ->where('id', $validated['theme_id'])
            ->where('is_active', true)
            ->first();

        if (! $theme) {
            return response()->json([
                'message' => 'Tema tidak tersedia atau tidak aktif.',
            ], 422);
        }

        if ($theme->isLocked($user->id)) {
            return response()->json([
                'message' => 'Tema premium ini membutuhkan akses langganan aktif.',
            ], 403);
        }

        $invitation->update([
            'theme_id' => $theme->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tema berhasil diterapkan.',
            'data' => [
                'invitation_id' => $invitation->id,
                'theme_id' => $theme->id,
                'theme_name' => $theme->name,
                'theme_slug' => $theme->slug,
            ],
        ]);
    }

    // ── Publish / Unpublish ───────────────────────────────────────────────────

    public function publish(Request $request, $id): JsonResponse
    {
        $user = $request->attributes->get('mobileUser');
        if (! $user instanceof User) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        $invitation = $user->invitations()->findOrFail($id);
        $invitation->update(['is_published' => ! $invitation->is_published]);

        return response()->json([
            'message' => $invitation->is_published ? 'Undangan berhasil dipublikasi.' : 'Undangan kembali ke draf.',
            'is_published' => $invitation->is_published,
        ]);
    }

    // ── Photo Upload ──────────────────────────────────────────────────────────

    public function uploadCoverPhoto(Request $request, $id): JsonResponse
    {
        $invitation = $this->findOwned($request, $id);
        $request->validate(['photo' => 'required|image|max:5120']);
        $path = app(ImageService::class)->storeAsWebp($request->file('photo'), 'invitations/covers');
        if ($invitation->cover_image) {
            Storage::disk('public')->delete($invitation->cover_image);
        }
        $invitation->update(['cover_image' => $path]);

        return response()->json(['url' => url('storage/'.$path)]);
    }

    public function uploadGroomPhoto(Request $request, $id): JsonResponse
    {
        $invitation = $this->findOwned($request, $id);
        $request->validate(['photo' => 'required|image|max:5120']);
        $path = app(ImageService::class)->storeAsWebp($request->file('photo'), 'invitations/mempelai');
        if ($invitation->groom_photo) {
            Storage::disk('public')->delete($invitation->groom_photo);
        }
        $invitation->update(['groom_photo' => $path]);

        return response()->json(['url' => url('storage/'.$path)]);
    }

    public function uploadBridePhoto(Request $request, $id): JsonResponse
    {
        $invitation = $this->findOwned($request, $id);
        $request->validate(['photo' => 'required|image|max:5120']);
        $path = app(ImageService::class)->storeAsWebp($request->file('photo'), 'invitations/mempelai');
        if ($invitation->bride_photo) {
            Storage::disk('public')->delete($invitation->bride_photo);
        }
        $invitation->update(['bride_photo' => $path]);

        return response()->json(['url' => url('storage/'.$path)]);
    }

    public function uploadGalleryPhoto(Request $request, $id): JsonResponse
    {
        $invitation = $this->findOwned($request, $id);
        $request->validate(['photo' => 'required|image|max:5120']);
        $maxOrder = InvitationPhoto::where('invitation_id', $invitation->id)->max('order') ?? 0;
        $path = app(ImageService::class)->storeAsWebp($request->file('photo'), 'invitations/gallery/'.$invitation->id);
        $photo = InvitationPhoto::create(['invitation_id' => $invitation->id, 'path' => $path, 'order' => $maxOrder + 1]);

        return response()->json(['id' => $photo->id, 'url' => url('storage/'.$path)], 201);
    }

    public function deleteGalleryPhoto(Request $request, $id, $photoId): JsonResponse
    {
        $invitation = $this->findOwned($request, $id);
        $photo = InvitationPhoto::where('invitation_id', $invitation->id)->findOrFail($photoId);
        Storage::disk('public')->delete($photo->path);
        $photo->delete();

        return response()->json(['message' => 'Foto berhasil dihapus.']);
    }

    // ── Guest Management ──────────────────────────────────────────────────────

    public function getGuests(Request $request, $id): JsonResponse
    {
        $invitation = $this->findOwned($request, $id);

        return response()->json([
            'data' => $invitation->guests->map(fn ($g) => [
                'id' => $g->id,
                'name' => $g->name,
                'phone' => $g->phone_number,
                'status' => $g->status?->value ?? 'pending',
            ]),
        ]);
    }

    public function storeGuests(Request $request, $id): JsonResponse
    {
        $invitation = $this->findOwned($request, $id);
        $request->validate(['names' => 'required|string']);

        $importService = app(GuestImportService::class);
        $guestService = app(GuestService::class);
        $guestData = $importService->parseCommaSeparated($request->input('names'));
        $guestData = $importService->validateGuests($guestData);

        if (empty($guestData)) {
            return response()->json(['message' => 'Tidak ada tamu valid.'], 422);
        }

        $count = $guestService->bulkImport($invitation, $guestData);

        return response()->json(['message' => "{$count} tamu berhasil ditambahkan."]);
    }

    public function deleteGuest(Request $request, $id, $guestId): JsonResponse
    {
        $invitation = $this->findOwned($request, $id);
        $guest = Guest::where('invitation_id', $invitation->id)->findOrFail($guestId);
        $guest->delete();

        return response()->json(['message' => 'Tamu berhasil dihapus.']);
    }

    // ── Delete Invitation ────────────────────────────────────────────────────

    public function destroy(Request $request, $id): JsonResponse
    {
        $invitation = $this->findOwned($request, $id);

        // Clean up photos from storage
        if ($invitation->cover_image) {
            Storage::disk('public')->delete($invitation->cover_image);
        }
        if ($invitation->groom_photo) {
            Storage::disk('public')->delete($invitation->groom_photo);
        }
        if ($invitation->bride_photo) {
            Storage::disk('public')->delete($invitation->bride_photo);
        }

        foreach ($invitation->photos as $photo) {
            Storage::disk('public')->delete($photo->path);
        }

        $invitation->delete();

        return response()->json(['message' => 'Undangan berhasil dihapus.']);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function findOwned(Request $request, $id): Invitation
    {
        $user = $request->attributes->get('mobileUser');
        if (! $user instanceof User) {
            abort(401);
        }

        return $user->invitations()->findOrFail($id);
    }

    private function uniqueSlug(string $base, ?int $excludeId = null): string
    {
        $slug = $base;
        $i = 1;
        while (Invitation::where('slug', $slug)->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    private function buildData(Request $request, string $slug): array
    {
        $styles = array_filter([
            'event_type' => $request->input('custom_styles.event_type'),
            'name_order' => $request->input('custom_styles.name_order'),
            'cover_title' => $request->input('custom_styles.cover_title'),
            'cover_subtitle' => $request->input('custom_styles.cover_subtitle'),
            'welcome_message' => $request->input('custom_styles.welcome_message'),
            'quran_verse' => $request->input('custom_styles.quran_verse'),
            'countdown_enabled' => $request->input('custom_styles.countdown_enabled'),
        ], fn ($v) => $v !== null);

        return [
            'title' => $request->input('title'),
            'slug' => $slug,
            'groom_name' => $request->input('groom_name'),
            'groom_nickname' => $request->input('groom_nickname'),
            'groom_father' => $request->input('groom_father'),
            'groom_mother' => $request->input('groom_mother'),
            'instagram_groom' => $request->input('instagram_groom'),
            'bride_name' => $request->input('bride_name'),
            'bride_nickname' => $request->input('bride_nickname'),
            'bride_father' => $request->input('bride_father'),
            'bride_mother' => $request->input('bride_mother'),
            'instagram_bride' => $request->input('instagram_bride'),
            'akad_date' => $request->input('akad_date') ?: null,
            'akad_time' => $request->input('akad_time'),
            'akad_venue' => $request->input('akad_venue'),
            'akad_address' => $request->input('akad_address'),
            'akad_maps_link' => $request->input('akad_maps_link'),
            'resepsi_date' => $request->input('resepsi_date') ?: null,
            'resepsi_time' => $request->input('resepsi_time'),
            'resepsi_venue' => $request->input('resepsi_venue'),
            'resepsi_address' => $request->input('resepsi_address'),
            'resepsi_maps_link' => $request->input('resepsi_maps_link'),
            'enable_rsvp' => $request->boolean('enable_rsvp', true),
            'enable_wishes' => $request->boolean('enable_wishes', true),
            'enable_gallery' => $request->boolean('enable_gallery', true),
            'background_music' => $request->input('background_music') ?: null,
            'music_id' => $request->input('music_id') ?: null,
            'love_story' => $request->input('love_story', []),
            'custom_styles' => $styles,
        ];
    }
}
