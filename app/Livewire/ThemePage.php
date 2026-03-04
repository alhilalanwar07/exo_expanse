<?php

namespace App\Livewire;

use App\Models\Invitation;
use App\Services\ThemeService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ThemePage extends Component
{
    #[Locked]
    public string $slug = '';

    #[Locked]
    public Invitation $invitation;

    #[Locked]
    public string $themeComponent = 'themes.generic';

    #[Locked]
    public string $themeCssVariables = '';

    #[Locked]
    public string $googleFontsUrl = '';

    #[Locked]
    public ?array $metadata = null;

    public function mount(string $slug, ThemeService $themeService): void
    {
        $this->slug = $slug;
        $this->invitation = Invitation::where('slug', $slug)->firstOrFail();

        // Resolve Theme Component
        if ($this->invitation->theme) {
            $this->themeComponent = $this->invitation->theme->view_file ?? 'themes.'.$this->invitation->theme->slug;
        }

        // Generate Theme CSS & Fonts using Service
        $themeConfig = $themeService->getThemeConfig($this->invitation);
        $this->themeCssVariables = $themeService->generateCssVariables($themeConfig);
        $this->googleFontsUrl = $themeService->getGoogleFontsUrl($themeConfig['fonts']);

        // Determine OG Image: User's Cover Image -> Theme's Thumbnail -> Default
        $ogImage = asset('images/og-default.jpg');
        if ($this->invitation->cover_image) {
            $ogImage = asset('storage/' . $this->invitation->cover_image);
        } elseif ($this->invitation->theme && $this->invitation->theme->thumbnail_url) {
            $ogImage = asset($this->invitation->theme->thumbnail_url);
        }

        // Metadata for SEO
        $this->metadata = [
            'title' => 'Undangan Pernikahan '.$this->invitation->groom_name.' & '.$this->invitation->bride_name,
            'description' => 'Kami mengundang Anda untuk merayakan momen pernikahan kami.',
            'image' => $ogImage,
        ];
    }

    public function render()
    {
        return view('livewire.theme-page')
            ->layout('layouts.invitation-layout', ['metadata' => $this->metadata]);
    }
}
