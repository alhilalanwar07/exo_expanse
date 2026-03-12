<?php

namespace App\Livewire;

use App\Models\Invitation;
use App\Models\Theme;
use App\Services\ThemeService;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;

class DemoPage extends Component
{
    #[Url(as: 'theme')]
    public string $themeSlug = '';

    #[Locked]
    public string $themeComponent = 'themes.generic';

    #[Locked]
    public string $themeCssVariables = '';

    #[Locked]
    public string $googleFontsUrl = '';

    #[Locked]
    public ?array $metadata = null;

    public Invitation $invitation;

    public array $themes = [];

    public function mount(ThemeService $themeService): void
    {
        // Load available themes for the selector
        $this->themes = Theme::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'thumbnail_url', 'is_premium'])
            ->toArray();

        // Build demo invitation
        $this->invitation = $this->buildDemoInvitation($themeService);

        // Generate theme rendering data
        $this->resolveTheme($themeService);
    }

    public function updatedThemeSlug(ThemeService $themeService): void
    {
        $this->invitation = $this->buildDemoInvitation($themeService);
        $this->resolveTheme($themeService);
    }

    private function resolveTheme(ThemeService $themeService): void
    {
        $theme = $this->invitation->theme;

        if ($theme) {
            $this->themeComponent = $theme->view_file ?? 'themes.'.$theme->slug;
        } else {
            $this->themeComponent = 'themes.generic';
        }

        $themeConfig = $themeService->getThemeConfig($this->invitation);
        $this->themeCssVariables = $themeService->generateCssVariables($themeConfig);
        $this->googleFontsUrl = $themeService->getGoogleFontsUrl($themeConfig['fonts']);

        $this->metadata = [
            'title' => 'Demo Tema Undangan Digital - ExoInvite',
            'description' => 'Preview tema undangan digital ExoInvite. Lihat berbagai pilihan tema premium untuk undangan pernikahan online Anda.',
            'image' => $theme?->thumbnail_url ? asset($theme->thumbnail_url) : asset('images/og-default.jpg'),
            'url' => request()->url(),
        ];
    }

    private function buildDemoInvitation(ThemeService $themeService): Invitation
    {
        $theme = null;
        if ($this->themeSlug) {
            $theme = Theme::where('slug', $this->themeSlug)->where('is_active', true)->first();
        }
        if (! $theme) {
            $theme = Theme::where('is_active', true)->first();
            if ($theme) {
                $this->themeSlug = $theme->slug;
            }
        }

        // Create an in-memory Invitation (not persisted)
        $invitation = new Invitation;
        $invitation->forceFill([
            'id' => 0,
            'theme_id' => $theme?->id,
            'user_id' => 1,
            'title' => 'Undangan Pernikahan Arya & Sari',
            'slug' => 'demo',

            // Groom
            'groom_name' => 'Arya Kusuma Wijaya',
            'groom_nickname' => 'Arya',
            'groom_father' => 'Bapak Hendra Wijaya',
            'groom_mother' => 'Ibu Ratna Sari',
            'groom_photo' => null,

            // Bride
            'bride_name' => 'Sari Permata Dewi',
            'bride_nickname' => 'Sari',
            'bride_father' => 'Bapak Darmawan Dewi',
            'bride_mother' => 'Ibu Kartini Dewi',
            'bride_photo' => null,

            // Akad
            'akad_date' => now()->addMonths(2)->format('Y-m-d'),
            'akad_time' => '08:00',
            'akad_venue' => 'Masjid Istiqlal',
            'akad_address' => 'Jl. Taman Wijaya Kusuma, Jakarta Pusat',
            'akad_maps_link' => 'https://maps.google.com',

            // Resepsi
            'resepsi_date' => now()->addMonths(2)->format('Y-m-d'),
            'resepsi_time' => '11:00',
            'resepsi_venue' => 'Balai Kartini Grand Ballroom',
            'resepsi_address' => 'Jl. Gatot Subroto Kav.37, Jakarta Selatan',
            'resepsi_maps_link' => 'https://maps.google.com',

            // Media
            'cover_image' => null,
            'background_music' => null,
            'gallery_images' => [],

            // Content
            'love_story' => [
                ['title' => 'Pertama Bertemu', 'date' => '2020', 'story' => 'Kami pertama kali bertemu di sebuah acara kampus. Senyuman hangat menjadi awal dari segalanya.'],
                ['title' => 'Mulai Dekat', 'date' => '2021', 'story' => 'Dari pertemanan biasa, kami mulai sering menghabiskan waktu bersama dan merasa ada yang spesial.'],
                ['title' => 'Lamaran', 'date' => '2025', 'story' => 'Dengan penuh keyakinan dan doa, kami memutuskan untuk melangkah ke jenjang pernikahan.'],
            ],

            'bank_accounts' => [
                ['bank' => 'BCA', 'account_number' => '1234567890', 'account_name' => 'Arya Kusuma Wijaya'],
                ['bank' => 'Mandiri', 'account_number' => '0987654321', 'account_name' => 'Sari Permata Dewi'],
            ],

            // Settings
            'is_published' => true,
            'enable_rsvp' => true,
            'enable_wishes' => true,
            'enable_gallery' => true,
            'enable_gift' => true,

            // Customization
            'custom_colors' => [],
            'custom_fonts' => [],
            'custom_styles' => ['name_order' => 'groom_first'],
            'theme_customization' => [],
        ]);

        // Set the relationship manually
        $invitation->setRelation('theme', $theme);
        $invitation->setRelation('wishes', collect([]));
        $invitation->setRelation('guests', collect([]));
        $invitation->setRelation('photos', collect([]));

        return $invitation;
    }

    public function render()
    {
        return view('livewire.demo-page')
            ->layout('layouts.invitation-layout', ['metadata' => $this->metadata]);
    }
}
