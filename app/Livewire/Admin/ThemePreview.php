<?php

namespace App\Livewire\Admin;

use App\Models\Invitation;
use App\Models\Theme;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ThemePreview extends Component
{
    #[Locked]
    public Invitation $invitation;

    #[Locked]
    public array $themeConfig = [];

    #[Locked]
    public array $sectionsConfig = [];

    #[Locked]
    public array $frameConfig = [];

    #[Locked]
    public array $navConfig = [];

    #[Locked]
    public array $enabledSections = [];

    public function mount(): void
    {
        $config = session('theme_builder_preview', []);

        $this->themeConfig = $config;
        $this->sectionsConfig = $config['sections_config']['sections'] ?? Theme::defaultSectionsConfig()['sections'];
        $this->frameConfig = $config['sections_config']['frame'] ?? Theme::defaultSectionsConfig()['frame'];
        $this->navConfig = $config['sections_config']['nav'] ?? Theme::defaultSectionsConfig()['nav'];

        // Build enabled sections sorted by order
        $this->enabledSections = collect($this->sectionsConfig)
            ->where('enabled', true)
            ->sortBy('order')
            ->values()
            ->toArray();

        // Build demo invitation
        $this->invitation = $this->buildDemoInvitation();
    }

    private function buildDemoInvitation(): Invitation
    {
        $invitation = new Invitation;
        $invitation->forceFill([
            'id' => 0,
            'theme_id' => null,
            'user_id' => 1,
            'title' => 'Undangan Pernikahan Arya & Sari',
            'slug' => 'preview',

            'groom_name' => 'Arya Kusuma Wijaya',
            'groom_nickname' => 'Arya',
            'groom_father' => 'Bapak Hendra Wijaya',
            'groom_mother' => 'Ibu Ratna Sari',
            'groom_photo' => null,

            'bride_name' => 'Sari Permata Dewi',
            'bride_nickname' => 'Sari',
            'bride_father' => 'Bapak Darmawan Dewi',
            'bride_mother' => 'Ibu Kartini Dewi',
            'bride_photo' => null,

            'akad_date' => now()->addMonths(2)->format('Y-m-d'),
            'akad_time' => '08:00',
            'akad_venue' => 'Masjid Istiqlal',
            'akad_address' => 'Jl. Taman Wijaya Kusuma, Jakarta Pusat',
            'akad_maps_link' => 'https://maps.google.com',

            'resepsi_date' => now()->addMonths(2)->format('Y-m-d'),
            'resepsi_time' => '11:00',
            'resepsi_venue' => 'Balai Kartini Grand Ballroom',
            'resepsi_address' => 'Jl. Gatot Subroto Kav.37, Jakarta Selatan',
            'resepsi_maps_link' => 'https://maps.google.com',

            'cover_image' => null,
            'background_music' => null,
            'gallery_images' => [],

            'love_story' => [
                ['title' => 'Pertama Bertemu', 'date' => '2020', 'description' => 'Kami pertama kali bertemu di sebuah acara kampus.'],
                ['title' => 'Mulai Dekat', 'date' => '2021', 'description' => 'Dari pertemanan biasa, kami merasa ada yang spesial.'],
                ['title' => 'Lamaran', 'date' => '2025', 'description' => 'Dengan penuh keyakinan, kami melangkah ke jenjang pernikahan.'],
            ],

            'bank_accounts' => [
                ['bank' => 'BCA', 'account_number' => '1234567890', 'account_name' => 'Arya Kusuma Wijaya'],
                ['bank' => 'Mandiri', 'account_number' => '0987654321', 'account_name' => 'Sari Permata Dewi'],
            ],

            'is_published' => true,
            'enable_rsvp' => true,
            'enable_wishes' => true,
            'enable_gallery' => true,
            'enable_gift' => true,

            'custom_colors' => [],
            'custom_fonts' => [],
            'custom_styles' => ['name_order' => 'groom_first'],
        ]);

        $invitation->setRelation('theme', null);
        $invitation->setRelation('wishes', collect([]));
        $invitation->setRelation('guests', collect([]));
        $invitation->setRelation('photos', collect([]));

        return $invitation;
    }

    public function render()
    {
        return view('livewire.admin.theme-preview')
            ->layout('layouts.preview-layout');
    }
}
