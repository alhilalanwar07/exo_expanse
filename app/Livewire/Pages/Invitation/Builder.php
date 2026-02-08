<?php

namespace App\Livewire\Pages\Invitation;

use App\Models\Guest;
use App\Models\Invitation;
use App\Models\InvitationPhoto;
use App\Models\Theme;
use App\Services\GuestImportService;
use App\Services\GuestService;
use App\Services\InvitationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Buat Undangan')]
class Builder extends Component
{
    use WithFileUploads;

    public ?int $invitationId = null;

    // ==================
    // TAB 1: COVER
    // ==================

    #[Validate('required|string|max:255')]
    public string $title = 'Pernikahan Andi & Rina';

    public string $cover_title = 'Andi & Rina';

    public string $cover_subtitle = 'The Wedding of';

    public ?string $cover_photo = null;

    // ==================
    // TAB 2: MEMPELAI
    // ==================

    public string $type = 'wedding';

    public string $name_order = 'groom_first';

    // Mempelai Pria
    #[Validate('required|string|max:255', as: 'Nama mempelai pria')]
    public string $groom_name = 'Andi Prasetyo, S.Kom';

    public string $groom_nickname = 'Andi';

    public string $groom_father = 'Bpk. Budiman';

    public string $groom_mother = 'Ibu Siti';

    public string $groom_instagram = 'andi.prasetyo';

    public ?string $groom_photo = null;

    // Mempelai Wanita
    #[Validate('required|string|max:255', as: 'Nama mempelai wanita')]
    public string $bride_name = 'Rina Wulandari, S.Pd';

    public string $bride_nickname = 'Rina';

    public string $bride_father = 'Bpk. Ahmad';

    public string $bride_mother = 'Ibu Dewi';

    public string $bride_instagram = 'rina.wulandari';

    public ?string $bride_photo = null;

    // ==================
    // TAB 3: ACARA
    // ==================

    public string $event_type = 'both'; // both, akad_only, resepsi_only

    #[Validate('required|date', as: 'Tanggal acara')]
    public string $event_date = '2026-06-15';

    // Akad
    public string $akad_date = '2026-06-15';

    public string $akad_time = '08:00';

    public string $akad_venue = 'Masjid Al-Ikhlas';

    public string $akad_address = 'Jl. Merdeka No. 123, Jakarta Selatan';

    // Resepsi
    public string $resepsi_date = '2026-06-15';

    public string $resepsi_time = '11:00';

    public string $resepsi_venue = 'Gedung Serbaguna Mawar';

    public string $resepsi_address = 'Jl. Mawar No. 456, Jakarta Selatan';

    // Maps
    public string $akad_maps_link = 'https://maps.google.com/?q=-6.2088,106.8456';

    public string $resepsi_maps_link = 'https://maps.google.com/?q=-6.2088,106.8456';

    public string $maps_embed = '';

    // ==================
    // TAB 5: SETTINGS
    // ==================

    #[Validate('required|exists:themes,id', as: 'Tema')]
    public ?int $theme_id = 1;

    // Music
    public bool $music_enabled = false;

    public ?string $music_url = null;

    // Gift/Angpao
    public bool $gift_enabled = false;

    public array $bank_accounts = [];

    // Feature Toggles
    public bool $countdown_enabled = true;

    public bool $rsvp_enabled = true;

    public bool $wishes_enabled = true;

    public bool $gallery_enabled = true;

    // Combined toggle for RSVP & Wishes (syncs both)
    public bool $rsvp_wishes_enabled = true;

    // When rsvp_wishes_enabled is updated, sync both underlying properties
    public function updatedRsvpWishesEnabled(bool $value): void
    {
        $this->rsvp_enabled = $value;
        $this->wishes_enabled = $value;
    }

    // JSON Settings (New Flexible Config)
    public array $settings = [
        'primary_color' => '#d97706', // Default Gold
        'font_heading' => 'Great Vibes',
        'font_body' => 'Serif',
    ];

    // Messages
    public string $welcome_message = 'Dengan memohon rahmat dan ridho Allah SWT, kami bermaksud mengundang Bapak/Ibu/Saudara/i untuk menghadiri acara pernikahan kami.';

    public string $quran_verse = 'Dan di antara tanda-tanda kekuasaan-Nya ialah Dia menciptakan untukmu isteri-isteri dari jenismu sendiri, supaya kamu cenderung dan merasa tenteram kepadanya. (QS. Ar-Rum: 21)';

    // Love Story
    public array $love_story = [];

    // Component Specific Properties
    #[Url]
    public string $tab = 'cover';

    public array $photos = [];

    public array $existingPhotos = [];

    public $coverPhotoUpload = null;

    public $groomPhotoUpload = null;

    public $bridePhotoUpload = null;

    public $musicUpload = null;

    public string $guestInput = '';

    public $guestFile = null;

    public array $guests = [];

    public array $tabs = [
        'cover' => ['label' => 'Cover', 'icon' => '🎨'],
        'mempelai' => ['label' => 'Mempelai', 'icon' => '💑'],
        'acara' => ['label' => 'Acara', 'icon' => '📅'],
        'gallery' => ['label' => 'Gallery', 'icon' => '📸'],
        'tamu' => ['label' => 'Tamu', 'icon' => '👥'],
        'settings' => ['label' => 'Settings', 'icon' => '⚙️'],
    ];

    public function mount(?int $id = null): void
    {
        if (request()->has('type')) {
            $this->type = request('type');
        }

        if ($id) {
            $invitation = Invitation::where('user_id', Auth::id())->findOrFail($id);
            $this->invitationId = $id;

            // Hydrate properties
            $this->mountInvitation($invitation);
            $this->loadExistingPhotos($invitation);
            $this->loadGuests($invitation);
        } else {
            // Set default date to tomorrow
            $defaultDate = date('Y-m-d', strtotime('+1 month'));
            $this->event_date = $defaultDate;
            $this->akad_date = $defaultDate;
            $this->resepsi_date = $defaultDate;
        }
    }

    protected function mountInvitation(Invitation $invitation): void
    {
        // Basic
        $this->title = $invitation->title ?? 'Untitled Invitation';
        $this->cover_photo = $invitation->cover_image;
        $this->event_date = $invitation->akad_date?->format('Y-m-d')
            ?? $invitation->resepsi_date?->format('Y-m-d')
            ?? date('Y-m-d');

        $this->theme_id = $invitation->theme_id;

        // Settings (Columns & Derived)
        $this->music_enabled = ! empty($invitation->background_music);
        $this->music_url = $invitation->background_music;

        $this->bank_accounts = $invitation->bank_accounts ?? [];
        $this->gift_enabled = ! empty($this->bank_accounts);

        $this->rsvp_enabled = $invitation->enable_rsvp ?? true;
        $this->wishes_enabled = $invitation->enable_wishes ?? true;
        $this->rsvp_wishes_enabled = $this->rsvp_enabled && $this->wishes_enabled;
        $this->gallery_enabled = $invitation->enable_gallery ?? true;
        $this->gift_enabled = $invitation->enable_gift ?? false;

        // JSON Settings (handled below)
        $this->countdown_enabled = $invitation->custom_styles['countdown_enabled'] ?? true;

        // Settings (JSON)
        $styles = $invitation->custom_styles ?? [];
        $this->name_order = $styles['name_order'] ?? 'groom_first';
        $this->event_type = $styles['event_type'] ?? 'both';
        $this->cover_title = $styles['cover_title'] ?? '';
        $this->cover_subtitle = $styles['cover_subtitle'] ?? '';
        $this->akad_maps_link = $invitation->akad_maps_link ?? '';
        $this->resepsi_maps_link = $invitation->resepsi_maps_link ?? '';
        $this->maps_embed = $styles['maps_embed'] ?? '';
        $this->welcome_message = $styles['welcome_message'] ?? '';
        $this->quran_verse = $styles['quran_verse'] ?? '';

        // Colors/Fonts
        $this->settings['primary_color'] = $invitation->custom_colors['primary'] ?? '#d97706';

        // Groom
        $this->groom_name = $invitation->groom_name ?? '';
        $this->groom_nickname = $invitation->groom_nickname ?? '';
        $this->groom_father = $invitation->groom_father ?? '';
        $this->groom_mother = $invitation->groom_mother ?? '';
        $this->groom_instagram = $invitation->instagram_groom ?? '';
        $this->groom_photo = $invitation->groom_photo;

        // Bride
        $this->bride_name = $invitation->bride_name ?? '';
        $this->bride_nickname = $invitation->bride_nickname ?? '';
        $this->bride_father = $invitation->bride_father ?? '';
        $this->bride_mother = $invitation->bride_mother ?? '';
        $this->bride_instagram = $invitation->instagram_bride ?? '';
        $this->bride_photo = $invitation->bride_photo;

        // Akad
        $this->akad_date = $invitation->akad_date?->format('Y-m-d') ?? '';
        $this->akad_time = $invitation->akad_time ?? '';
        $this->akad_venue = $invitation->akad_venue ?? '';
        $this->akad_address = $invitation->akad_address ?? '';

        // Resepsi
        $this->resepsi_date = $invitation->resepsi_date?->format('Y-m-d') ?? '';
        $this->resepsi_time = $invitation->resepsi_time ?? '';
        $this->resepsi_venue = $invitation->resepsi_venue ?? '';
        $this->resepsi_address = $invitation->resepsi_address ?? '';

        // Love Story
        $this->love_story = $invitation->love_story ?? [];
    }

    protected function prepareInvitationData(): array
    {
        return [
            'title' => $this->title,
            'theme_id' => $this->theme_id,

            // Couple
            'groom_name' => $this->groom_name,
            'groom_nickname' => $this->groom_nickname,
            'groom_father' => $this->groom_father,
            'groom_mother' => $this->groom_mother,
            'groom_photo' => $this->groom_photo,
            'instagram_groom' => $this->groom_instagram,

            'bride_name' => $this->bride_name,
            'bride_nickname' => $this->bride_nickname,
            'bride_father' => $this->bride_father,
            'bride_mother' => $this->bride_mother,
            'bride_photo' => $this->bride_photo,
            'instagram_bride' => $this->bride_instagram,

            // Events
            'akad_date' => $this->akad_date ?: null,
            'akad_time' => $this->akad_time,
            'akad_venue' => $this->akad_venue,
            'akad_address' => $this->akad_address,
            'akad_maps_link' => $this->akad_maps_link,

            'resepsi_date' => $this->resepsi_date ?: null,
            'resepsi_time' => $this->resepsi_time,
            'resepsi_venue' => $this->resepsi_venue,
            'resepsi_address' => $this->resepsi_address,
            'resepsi_maps_link' => $this->resepsi_maps_link,

            // Media & Content
            'cover_image' => $this->cover_photo,
            'background_music' => $this->music_enabled ? $this->music_url : null,
            'love_story' => $this->love_story,
            'bank_accounts' => $this->gift_enabled ? $this->bank_accounts : null,

            // Settings Columns
            'enable_rsvp' => $this->rsvp_enabled,
            'enable_wishes' => $this->wishes_enabled,
            'enable_gallery' => $this->gallery_enabled,
            'enable_gift' => $this->gift_enabled,
            'is_published' => $this->invitation?->is_published ?? false,

            // JSON Columns
            'custom_colors' => [
                'primary' => $this->settings['primary_color'] ?? '#d97706',
            ],
            'custom_styles' => [
                'name_order' => $this->name_order,
                'event_type' => $this->event_type,
                'cover_title' => $this->cover_title,
                'cover_subtitle' => $this->cover_subtitle,
                'maps_embed' => $this->maps_embed,
                'welcome_message' => $this->welcome_message,
                'quran_verse' => $this->quran_verse,
                'countdown_enabled' => $this->countdown_enabled,
            ],
            'custom_fonts' => [
                'heading' => $this->settings['font_heading'] ?? null,
                'body' => $this->settings['font_body'] ?? null,
            ],
        ];
    }

    #[Computed]
    public function themes(): \Illuminate\Database\Eloquent\Collection
    {
        return Theme::orderBy('name')->get();
    }

    #[Computed]
    public function invitation(): ?Invitation
    {
        if ($this->invitationId) {
            return Invitation::find($this->invitationId);
        }

        return null;
    }

    public function setTab(string $tab): void
    {
        if (array_key_exists($tab, $this->tabs)) {
            $this->tab = $tab;
        }
    }

    public function loadExistingPhotos(Invitation $invitation): void
    {
        $this->existingPhotos = $invitation->photos->map(fn (InvitationPhoto $photo) => [
            'id' => $photo->id,
            'url' => $photo->url,
            'path' => $photo->path,
            'caption' => $photo->caption,
        ])->toArray();
    }

    // ==================
    // MEDIA ACTIONS
    // ==================

    public function updatedMusicUpload(): void
    {
        $this->validate([
            'musicUpload' => 'required|file|mimes:mp3|max:10240', // 10MB
        ]);

        if ($this->musicUpload) {
            if ($this->music_url) {
                Storage::disk('public')->delete($this->music_url);
            }

            $path = $this->musicUpload->store('invitations/music', 'public');
            $this->music_url = $path;
            $this->musicUpload = null;
        }
    }

    public function removeMusic(): void
    {
        if ($this->music_url) {
            Storage::disk('public')->delete($this->music_url);
            $this->music_url = null;
        }
    }

    public function updatedCoverPhotoUpload(): void
    {
        $this->validate([
            'coverPhotoUpload' => 'image|max:5120', // 5MB
        ]);

        if ($this->coverPhotoUpload) {
            if ($this->cover_photo) {
                Storage::disk('public')->delete($this->cover_photo);
            }

            $path = $this->coverPhotoUpload->store('invitations/covers', 'public');
            $this->cover_photo = $path;
            $this->coverPhotoUpload = null;
        }
    }

    public function removeCoverPhoto(): void
    {
        if ($this->cover_photo) {
            Storage::disk('public')->delete($this->cover_photo);
            $this->cover_photo = null;
        }
    }

    public function updatedGroomPhotoUpload(): void
    {
        $this->validate([
            'groomPhotoUpload' => 'image|max:5120', // 5MB
        ]);

        if ($this->groomPhotoUpload) {
            if ($this->groom_photo) {
                Storage::disk('public')->delete($this->groom_photo);
            }

            $path = $this->groomPhotoUpload->store('invitations/mempelai', 'public');
            $this->groom_photo = $path;
            $this->groomPhotoUpload = null;
        }
    }

    public function removeGroomPhoto(): void
    {
        if ($this->groom_photo) {
            Storage::disk('public')->delete($this->groom_photo);
            $this->groom_photo = null;
        }
    }

    public function updatedBridePhotoUpload(): void
    {
        $this->validate([
            'bridePhotoUpload' => 'image|max:5120', // 5MB
        ]);

        if ($this->bridePhotoUpload) {
            if ($this->bride_photo) {
                Storage::disk('public')->delete($this->bride_photo);
            }

            $path = $this->bridePhotoUpload->store('invitations/mempelai', 'public');
            $this->bride_photo = $path;
            $this->bridePhotoUpload = null;
        }
    }

    public function removeBridePhoto(): void
    {
        if ($this->bride_photo) {
            Storage::disk('public')->delete($this->bride_photo);
            $this->bride_photo = null;
        }
    }

    // ==================
    // GALLERY PHOTOS
    // ==================

    public function updatedPhotos(): void
    {
        $this->validate([
            'photos.*' => 'image|max:5120', // 5MB per photo
        ]);
    }

    public function removePhoto(int $index): void
    {
        if (isset($this->photos[$index])) {
            unset($this->photos[$index]);
            $this->photos = array_values($this->photos);
        }
    }

    public function removeExistingPhoto(int $photoId): void
    {
        if (! $this->invitationId) {
            return;
        }

        $photo = InvitationPhoto::where('invitation_id', $this->invitationId)
            ->find($photoId);

        if ($photo) {
            Storage::disk('public')->delete($photo->path);
            $photo->delete();

            $invitation = Invitation::find($this->invitationId);
            $this->loadExistingPhotos($invitation);
        }
    }

    // ==================
    // GIFT ACCOUNTS
    // ==================

    public function addGiftAccount(): void
    {
        $this->bank_accounts[] = [
            'bank' => '',
            'account_number' => '',
            'account_name' => '',
        ];
    }

    public function removeGiftAccount(int $index): void
    {
        if (isset($this->bank_accounts[$index])) {
            unset($this->bank_accounts[$index]);
            $this->bank_accounts = array_values($this->bank_accounts);
        }
    }

    // ==================
    // GUEST MANAGEMENT
    // ==================

    public function addLoveStory(): void
    {
        $this->love_story[] = [
            'date' => date('Y'),
            'title' => '',
            'description' => '',
        ];
    }

    public function removeLoveStory(int $index): void
    {
        if (isset($this->love_story[$index])) {
            unset($this->love_story[$index]);
            $this->love_story = array_values($this->love_story);
        }
    }

    public function loadGuests(Invitation $invitation): void
    {
        $this->guests = $invitation->guests->map(fn ($g) => [
            'id' => $g->id,
            'name' => $g->name,
            'slug' => $g->slug,
            'phone_number' => $g->phone_number,
            'status' => $g->status?->value ?? 'pending',
        ])->toArray();
    }

    public function addGuestsFromText(): void
    {
        if (! $this->invitationId) {
            session()->flash('error', 'Simpan undangan terlebih dahulu sebelum menambahkan tamu.');

            return;
        }

        $invitation = Invitation::find($this->invitationId);
        $importService = app(GuestImportService::class);
        $guestService = app(GuestService::class);

        $guestData = $importService->parseCommaSeparated($this->guestInput);
        $guestData = $importService->validateGuests($guestData);

        if (empty($guestData)) {
            session()->flash('error', 'Tidak ada tamu yang valid untuk ditambahkan.');

            return;
        }

        $count = $guestService->bulkImport($invitation, $guestData);

        $this->guestInput = '';
        $this->loadGuests($invitation);

        session()->flash('success', "{$count} tamu berhasil ditambahkan!");
    }

    public function updatedGuestFile(): void
    {
        $this->validate([
            'guestFile' => 'required|file|mimes:csv,txt,xlsx,xls|max:2048',
        ]);
    }

    public function importGuestsFromFile(): void
    {
        if (! $this->invitationId) {
            session()->flash('error', 'Simpan undangan terlebih dahulu sebelum import tamu.');

            return;
        }

        if (! $this->guestFile) {
            session()->flash('error', 'Pilih file terlebih dahulu.');

            return;
        }

        $invitation = Invitation::find($this->invitationId);
        $importService = app(GuestImportService::class);
        $guestService = app(GuestService::class);

        $guestData = $importService->parseCsvFile($this->guestFile);
        $guestData = $importService->validateGuests($guestData);

        if (empty($guestData)) {
            session()->flash('error', 'Tidak ada tamu yang valid dalam file.');

            return;
        }

        $count = $guestService->bulkImport($invitation, $guestData);

        $this->guestFile = null;
        $this->loadGuests($invitation);

        session()->flash('success', "{$count} tamu berhasil diimport dari file!");
    }

    public function deleteGuest(int $guestId): void
    {
        $guest = Guest::find($guestId);

        if ($guest && $guest->invitation_id === $this->invitationId) {
            $guest->delete();
            $this->guests = array_filter($this->guests, fn ($g) => $g['id'] !== $guestId);
            $this->guests = array_values($this->guests);
        }
    }

    public function getGuestShareUrl(string $guestName): string
    {
        if (! $this->invitationId) {
            return '#';
        }

        $invitation = Invitation::find($this->invitationId);
        $baseUrl = route('invitation.show', $invitation->slug);

        return $baseUrl.'?kpd='.urlencode($guestName);
    }

    public function getGuestWhatsAppUrl(string $guestName, ?string $phone = null): string
    {
        if (! $this->invitationId) {
            return '#';
        }

        $importService = app(GuestImportService::class);
        $invitation = Invitation::find($this->invitationId);
        $baseUrl = route('invitation.show', $invitation->slug);

        return $importService->generateWhatsAppUrl($baseUrl, $guestName, $phone);
    }

    // ==================
    // SAVE
    // ==================

    public function save(): void
    {
        $this->validate();

        $service = app(InvitationService::class);
        $data = $this->prepareInvitationData();

        if ($this->invitationId) {
            $invitation = Invitation::find($this->invitationId);
            // $invitation = $service->update($invitation, $data);
            // Update logic usually returns the invitation or boolean
            // Assuming service updates model in place or returns it
            $service->update($invitation, $data);
            $invitation->refresh();
        } else {
            $invitation = $service->create(Auth::user(), $data);
        }

        // Save new photos
        $this->savePhotos($invitation);

        session()->flash('success', 'Undangan berhasil disimpan!');

        // Update ID jika ini adalah create baru
        if (! $this->invitationId) {
            $this->invitationId = $invitation->id;

            // $this->redirect(route('invitations.edit', $invitation->id));
            // No redirect needed if we update ID? Livewire might lose state if not careful.
            // Safer to redirect
            return;
        }
    }

    public function savePhotos(Invitation $invitation): void
    {
        // Debug: Log how many photos we have
        \Log::info('savePhotos called', [
            'invitation_id' => $invitation->id,
            'photos_count' => count($this->photos),
        ]);

        if (empty($this->photos)) {
            \Log::info('No photos to save');

            return;
        }

        // Get the current max order for this invitation
        $maxOrder = InvitationPhoto::where('invitation_id', $invitation->id)->max('order') ?? 0;

        foreach ($this->photos as $index => $photo) {
            if ($photo) {
                \Log::info('Saving photo', ['index' => $index]);

                // Store file
                $path = $photo->store('invitations/gallery/'.$invitation->id, 'public');

                // Create InvitationPhoto record
                $savedPhoto = InvitationPhoto::create([
                    'invitation_id' => $invitation->id,
                    'path' => $path,
                    'order' => ++$maxOrder,
                ]);

                \Log::info('Photo saved', ['photo_id' => $savedPhoto->id, 'path' => $path]);
            }
        }

        $this->photos = [];
        $this->loadExistingPhotos($invitation);
    }

    public function publish(): void
    {
        $this->validate();

        $service = app(InvitationService::class);
        $data = $this->prepareInvitationData();

        if ($this->invitationId) {
            $invitation = Invitation::find($this->invitationId);
            $service->update($invitation, $data);
            $invitation->refresh();
        } else {
            $invitation = $service->create(Auth::user(), $data);
        }

        // Save new photos before publishing
        $this->savePhotos($invitation);

        $service->publish($invitation);

        session()->flash('success', 'Undangan berhasil dipublikasikan!');

        $this->redirect(route('dashboard'));
    }

    public function render()
    {
        return view('livewire.pages.invitation.builder');
    }
}
