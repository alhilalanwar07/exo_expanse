<?php

namespace App\Services;

use App\Models\Invitation;
use App\Models\Theme;

class ThemeService
{
    /**
     * Get theme configuration for an invitation
     */
    public function getThemeConfig(Invitation $invitation): array
    {
        $theme = $invitation->theme;

        if (! $theme) {
            return $this->getDefaultConfig();
        }

        // Merge theme defaults with invitation customizations
        $colors = array_merge(
            [
                'primary' => $theme->primary_color ?? '#d97706',
                'secondary' => $theme->secondary_color ?? '#1f2937',
                'accent' => $theme->accent_color ?? '#f59e0b',
                'text' => $theme->text_color ?? '#1f2937',
                'heading' => $theme->heading_color ?? '#111827',
                'background' => $theme->background_color ?? '#ffffff',
                'muted' => '#6b7280',
            ],
            $invitation->custom_colors ?? []
        );

        $fonts = array_merge(
            [
                'heading' => $theme->heading_font ?? 'Playfair Display',
                'body' => $theme->body_font ?? 'Inter',
                'accent' => $theme->accent_font ?? 'Great Vibes',
            ],
            $invitation->custom_fonts ?? []
        );

        $settings = array_merge(
            [
                'border_radius' => $theme->border_radius ?? '0.75rem',
                'container_max_width' => $theme->container_max_width ?? '28rem',
            ],
            $invitation->custom_styles ?? []
        );

        return [
            'id' => $theme->id,
            'name' => $theme->name,
            'slug' => $theme->slug,
            'colors' => $colors,
            'fonts' => $fonts,
            'settings' => $settings,
        ];
    }

    /**
     * Get default theme configuration
     */
    public function getDefaultConfig(): array
    {
        return [
            'id' => null,
            'name' => 'Default',
            'slug' => 'default',
            'colors' => [
                'primary' => '#d97706',
                'secondary' => '#1f2937',
                'accent' => '#f59e0b',
                'text' => '#1f2937',
                'heading' => '#111827',
                'background' => '#ffffff',
                'muted' => '#6b7280',
            ],
            'fonts' => [
                'heading' => 'Playfair Display',
                'body' => 'Inter',
                'accent' => 'Great Vibes',
            ],
            'settings' => [
                'border_radius' => '0.75rem',
                'container_max_width' => '28rem',
            ],
        ];
    }

    /**
     * Generate CSS variables from theme config
     */
    public function generateCssVariables(array $config): string
    {
        $colors = $config['colors'];
        $fonts = $config['fonts'];
        $settings = $config['settings'];

        return <<<CSS
:root {
    --color-primary: {$colors['primary']};
    --color-secondary: {$colors['secondary']};
    --color-accent: {$colors['accent']};
    --color-text: {$colors['text']};
    --color-heading: {$colors['heading']};
    --color-background: {$colors['background']};
    --color-muted: {$colors['muted']};
    
    --font-heading: '{$fonts['heading']}', serif;
    --font-body: '{$fonts['body']}', sans-serif;
    --font-accent: '{$fonts['accent']}', cursive;
    
    --border-radius: {$settings['border_radius']};
    --container-max-width: {$settings['container_max_width']};
}
CSS;
    }

    /**
     * Get Google Fonts URL for theme fonts
     */
    public function getGoogleFontsUrl(array $fonts): string
    {
        $fontList = collect($fonts)
            ->unique()
            ->map(fn ($font) => str_replace(' ', '+', $font).':wght@300;400;500;600;700')
            ->implode('&family=');

        return "https://fonts.googleapis.com/css2?family={$fontList}&display=swap";
    }

    /**
     * Get theme component name
     */
    public function getThemeComponentName(?Theme $theme): string
    {
        if (! $theme || ! $theme->view_file) {
            return 'themes.⚡generic';
        }

        $viewFile = $theme->view_file;
        $parts = explode('.', $viewFile);
        $lastPart = array_pop($parts);

        return (count($parts) > 0 ? implode('.', $parts).'.' : '').'⚡'.$lastPart;
    }
}
