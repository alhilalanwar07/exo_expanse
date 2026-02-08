<?php

namespace App\Livewire;

use App\Models\Invitation;
use App\Services\ThemeService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.invitation-layout')]
class ThemePage extends Component
{
    public $slug;

    public Invitation $invitation;

    public $themeComponent = 'themes.generic';

    public $themeCssVariables = '';

    public $googleFontsUrl = '';

    public $metadata;

    public function mount($slug, ThemeService $themeService)
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

        // Metadata for SEO
        $this->metadata = [
            'title' => 'Undangan Pernikahan '.$this->invitation->groom_name.' & '.$this->invitation->bride_name,
            'description' => 'Kami mengundang Anda untuk merayakan momen bahagia kami.',
            'image' => $this->invitation->cover_image_url ?? '',
        ];
    }

    public function render()
    {
        return view('livewire.theme-page');
    }
}
