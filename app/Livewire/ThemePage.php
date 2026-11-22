<?php

namespace App\Livewire;

use App\Models\Invitation;
use App\Services\ImageService;
use App\Services\ThemeService;
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
        $ogMeta = app(ImageService::class)->resolveOgImageMetadata(
            $this->invitation->cover_image,
            $this->invitation->theme?->thumbnail_url
        );

        // groom atau bride yang pertama
        $order = $this->invitation->custom_styles['name_order'] ?? 'groom_first';
        $groomOrBride = $order === 'bride_first'
            ? $this->invitation->bride_nickname.' & '.$this->invitation->groom_nickname
            : $this->invitation->groom_nickname.' & '.$this->invitation->bride_nickname;

        $invitationTitle = trim((string) ($this->invitation->title ?? ''));
        $ogTitle = $invitationTitle !== '' ? $invitationTitle : ('The Wedding of '.$groomOrBride);
        $guestName = trim((string) request('kpd', request('to', '')));
        $inviteSentence = 'Kami mengundang Bapak/Ibu/Saudara/i untuk menghadiri acara pernikahan '.$groomOrBride.'.';
        $description = $guestName !== '' ? ('Kepada Yth. '.$guestName.' — '.$inviteSentence) : $inviteSentence;

        // Metadata for SEO & WhatsApp / Social Previews
        $this->metadata = [
            'title' => $ogTitle,
            'description' => $description,
            'image' => $ogMeta['url'],
            'image_alt' => $ogTitle,
            'image_type' => $ogMeta['type'],
            'image_width' => $ogMeta['width'],
            'image_height' => $ogMeta['height'],
            'url' => request()->fullUrl(),
        ];
    }

    public function render()
    {
        return view('livewire.theme-page')
            ->layout('layouts.invitation-layout', ['metadata' => $this->metadata]);
    }
}
