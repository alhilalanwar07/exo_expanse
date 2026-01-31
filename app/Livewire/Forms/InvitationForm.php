<?php

namespace App\Livewire\Forms;

use App\Models\Invitation;
use App\Services\InvitationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;

class InvitationForm extends Form
{
    public ?Invitation $invitation = null;

    // ==================
    // TAB 1: COVER
    // ==================
    
    #[Validate('required|string|max:255', as: 'Judul undangan')]
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
    public string $groom_name = 'Andi';
    public string $groom_fullname = 'Andi Prasetyo, S.Kom';
    public string $groom_parents = 'Bpk. Budiman & Ibu Siti';
    public string $groom_instagram = 'andi.prasetyo';
    public ?string $groom_photo = null;

    // Mempelai Wanita
    #[Validate('required|string|max:255', as: 'Nama mempelai wanita')]
    public string $bride_name = 'Rina';
    public string $bride_fullname = 'Rina Wulandari, S.Pd';
    public string $bride_parents = 'Bpk. Ahmad & Ibu Dewi';
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
    public string $maps_url = 'https://maps.google.com/?q=-6.2088,106.8456';
    public string $maps_embed = '';

    // ==================
    // TAB 4: GALLERY
    // ==================
    
    // Photos handled in component due to WithFileUploads trait

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
    public array $gift_accounts = [];

    // Feature Toggles
    public bool $countdown_enabled = true;
    public bool $rsvp_enabled = true;
    public bool $wishes_enabled = true;

    // Messages
    public string $welcome_message = 'Dengan memohon rahmat dan ridho Allah SWT, kami bermaksud mengundang Bapak/Ibu/Saudara/i untuk menghadiri acara pernikahan kami.';
    public string $quran_verse = 'Dan di antara tanda-tanda kekuasaan-Nya ialah Dia menciptakan untukmu isteri-isteri dari jenismu sendiri, supaya kamu cenderung dan merasa tenteram kepadanya. (QS. Ar-Rum: 21)';

    // ==================
    // METHODS
    // ==================

    public function setInvitation(?Invitation $invitation): void
    {
        if (!$invitation) {
            return;
        }

        $this->invitation = $invitation;

        // Basic
        $this->title = $invitation->title;
        $this->cover_title = $invitation->cover_title ?? '';
        $this->cover_subtitle = $invitation->cover_subtitle ?? '';
        $this->cover_photo = $invitation->cover_photo;
        $this->event_date = $invitation->event_date?->format('Y-m-d') ?? '';
        $this->theme_id = $invitation->theme_id;
        $this->type = $invitation->type ?? 'wedding';

        // Settings
        $this->music_enabled = $invitation->music_enabled;
        $this->music_url = $invitation->music_url;
        $this->gift_enabled = $invitation->gift_enabled;
        $this->gift_accounts = $invitation->gift_accounts ?? [];
        $this->countdown_enabled = $invitation->countdown_enabled;
        $this->rsvp_enabled = $invitation->rsvp_enabled;
        $this->wishes_enabled = $invitation->wishes_enabled;

        // Content
        $content = $invitation->content ?? [];
        
        $this->name_order = $content['name_order'] ?? 'groom_first';
        $this->event_type = $content['event_type'] ?? 'both';
        
        // Groom
        $this->groom_name = $content['groom_name'] ?? '';
        $this->groom_fullname = $content['groom_fullname'] ?? '';
        $this->groom_parents = $content['groom_parents'] ?? '';
        $this->groom_instagram = $content['groom_instagram'] ?? '';
        $this->groom_photo = $content['groom_photo'] ?? null;
        
        // Bride
        $this->bride_name = $content['bride_name'] ?? '';
        $this->bride_fullname = $content['bride_fullname'] ?? '';
        $this->bride_parents = $content['bride_parents'] ?? '';
        $this->bride_instagram = $content['bride_instagram'] ?? '';
        $this->bride_photo = $content['bride_photo'] ?? null;
        
        // Akad
        $this->akad_date = $content['akad_date'] ?? '';
        $this->akad_time = $content['akad_time'] ?? '';
        $this->akad_venue = $content['akad_venue'] ?? '';
        $this->akad_address = $content['akad_address'] ?? '';
        
        // Resepsi
        $this->resepsi_date = $content['resepsi_date'] ?? '';
        $this->resepsi_time = $content['resepsi_time'] ?? '';
        $this->resepsi_venue = $content['resepsi_venue'] ?? '';
        $this->resepsi_address = $content['resepsi_address'] ?? '';
        
        // Maps & Messages
        $this->maps_url = $content['maps_url'] ?? '';
        $this->maps_embed = $content['maps_embed'] ?? '';
        $this->welcome_message = $content['welcome_message'] ?? '';
        $this->quran_verse = $content['quran_verse'] ?? '';
    }

    public function getContentArray(): array
    {
        return [
            'name_order' => $this->name_order,
            'event_type' => $this->event_type,
            // Groom
            'groom_name' => $this->groom_name,
            'groom_fullname' => $this->groom_fullname,
            'groom_parents' => $this->groom_parents,
            'groom_instagram' => $this->groom_instagram,
            'groom_photo' => $this->groom_photo,
            // Bride
            'bride_name' => $this->bride_name,
            'bride_fullname' => $this->bride_fullname,
            'bride_parents' => $this->bride_parents,
            'bride_instagram' => $this->bride_instagram,
            'bride_photo' => $this->bride_photo,
            // Akad
            'akad_date' => $this->akad_date,
            'akad_time' => $this->akad_time,
            'akad_venue' => $this->akad_venue,
            'akad_address' => $this->akad_address,
            // Resepsi
            'resepsi_date' => $this->resepsi_date,
            'resepsi_time' => $this->resepsi_time,
            'resepsi_venue' => $this->resepsi_venue,
            'resepsi_address' => $this->resepsi_address,
            // Maps & Messages
            'maps_url' => $this->maps_url,
            'maps_embed' => $this->maps_embed,
            'welcome_message' => $this->welcome_message,
            'quran_verse' => $this->quran_verse,
        ];
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'type' => $this->type,
            'cover_title' => $this->cover_title,
            'cover_subtitle' => $this->cover_subtitle,
            'cover_photo' => $this->cover_photo,
            'event_date' => $this->event_date ?: null,
            'theme_id' => $this->theme_id,
            'content' => $this->getContentArray(),
            'music_enabled' => $this->music_enabled,
            'music_url' => $this->music_url,
            'gift_enabled' => $this->gift_enabled,
            'gift_accounts' => $this->gift_accounts,
            'countdown_enabled' => $this->countdown_enabled,
            'rsvp_enabled' => $this->rsvp_enabled,
            'wishes_enabled' => $this->wishes_enabled,
        ];
    }

    public function save(): Invitation
    {
        $this->validate();

        $service = app(InvitationService::class);
        $data = $this->toArray();

        if ($this->invitation) {
            return $service->update($this->invitation, $data);
        }

        return $service->create(Auth::user(), $data);
    }
}
