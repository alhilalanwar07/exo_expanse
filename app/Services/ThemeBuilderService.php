<?php

namespace App\Services;

use App\Models\Theme;

class ThemeBuilderService
{
    /**
     * Build CSS variables and styles from theme configuration
     */
    public static function buildStyles(
        Theme $theme,
        array $customColors = [],
        array $customFonts = [],
        string $customCss = ''
    ): string {
        // Use custom colors if provided, otherwise use theme colors
        $colors = [
            'primary' => $customColors['primary'] ?? $theme->primary_color,
            'secondary' => $customColors['secondary'] ?? $theme->secondary_color,
            'accent' => $customColors['accent'] ?? $theme->accent_color,
            'text' => $customColors['text'] ?? $theme->text_color,
            'heading' => $customColors['heading'] ?? $theme->heading_color,
            'background' => $customColors['background'] ?? $theme->background_color,
        ];

        $fonts = [
            'heading' => $customFonts['heading_font'] ?? $theme->heading_font,
            'body' => $customFonts['body_font'] ?? $theme->body_font,
            'accent' => $customFonts['accent_font'] ?? $theme->accent_font,
        ];

        $styles = self::generateCSSVariables($colors, $fonts, $theme);

        if (! empty($customCss)) {
            $styles .= "\n".$customCss;
        }

        return $styles;
    }

    /**
     * Generate CSS variables string
     */
    private static function generateCSSVariables(array $colors, array $fonts, Theme $theme): string
    {
        return <<<CSS
        :root {
            /* Colors */
            --color-primary: {$colors['primary']};
            --color-secondary: {$colors['secondary']};
            --color-accent: {$colors['accent']};
            --color-text: {$colors['text']};
            --color-heading: {$colors['heading']};
            --color-background: {$colors['background']};
            
            /* Fonts */
            --font-heading: '{$fonts['heading']}', serif;
            --font-body: '{$fonts['body']}', sans-serif;
            --font-accent: '{$fonts['accent']}', cursive;
            
            /* Layout */
            --container-max-width: {$theme->container_max_width}px;
            --border-radius: {$theme->border_radius}px;
            
            /* Typography */
            --heading-size: {$theme->heading_size}px;
        }
        
        body {
            background-color: var(--color-background);
            color: var(--color-text);
            font-family: var(--font-body);
        }
        
        h1, h2, h3, h4, h5, h6 {
            color: var(--color-heading);
            font-family: var(--font-heading);
        }
        
        .btn-primary {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
            color: var(--color-text);
        }
        
        .btn-primary:hover {
            background-color: var(--color-accent);
            border-color: var(--color-accent);
        }
        
        .accent-text {
            color: var(--color-accent);
            font-family: var(--font-accent);
        }
        CSS;
    }

    /**
     * Get Google Fonts import statement
     */
    public static function getGoogleFontsImport(Theme $theme, array $customFonts = []): string
    {
        $fonts = [
            $customFonts['heading_font'] ?? $theme->heading_font,
            $customFonts['body_font'] ?? $theme->body_font,
            $customFonts['accent_font'] ?? $theme->accent_font,
        ];

        // Remove duplicates
        $fonts = array_unique($fonts);

        // Convert font names to URL format
        $fontUrls = array_map(fn ($font) => urlencode($font), $fonts);

        return 'https://fonts.googleapis.com/css2?family='.implode('&family=', $fontUrls).'&display=swap';
    }

    /**
     * Export theme configuration for frontend
     */
    public static function exportThemeConfig(
        Theme $theme,
        array $customColors = [],
        array $customFonts = [],
        string $customCss = ''
    ): array {
        return [
            'id' => $theme->id,
            'name' => $theme->name,
            'slug' => $theme->slug,
            'colors' => [
                'primary' => $customColors['primary'] ?? $theme->primary_color,
                'secondary' => $customColors['secondary'] ?? $theme->secondary_color,
                'accent' => $customColors['accent'] ?? $theme->accent_color,
                'text' => $customColors['text'] ?? $theme->text_color,
                'heading' => $customColors['heading'] ?? $theme->heading_color,
                'background' => $customColors['background'] ?? $theme->background_color,
            ],
            'fonts' => [
                'heading' => $customFonts['heading_font'] ?? $theme->heading_font,
                'body' => $customFonts['body_font'] ?? $theme->body_font,
                'accent' => $customFonts['accent_font'] ?? $theme->accent_font,
            ],
            'layout' => [
                'container_max_width' => $theme->container_max_width,
                'border_radius' => $theme->border_radius,
                'heading_size' => $theme->heading_size,
            ],
            'customCss' => $customCss,
            'googleFontsUrl' => self::getGoogleFontsImport($theme, $customFonts),
        ];
    }

    /**
     * Get available Google Fonts list
     */
    public static function getAvailableFonts(): array
    {
        return [
            // Serif Fonts
            'Playfair Display' => 'serif',
            'Cormorant Garamond' => 'serif',
            'Libre Baskerville' => 'serif',
            'Cinzel' => 'serif',
            'Italiana' => 'serif',

            // Sans-serif Fonts
            'Montserrat' => 'sans-serif',
            'Roboto' => 'sans-serif',
            'Lato' => 'sans-serif',
            'Raleway' => 'sans-serif',
            'Nunito' => 'sans-serif',

            // Script/Display Fonts
            'Great Vibes' => 'cursive',
            'Sacramento' => 'cursive',
            'Tangerine' => 'cursive',
            'Dancing Script' => 'cursive',
            'Allura' => 'cursive',
        ];
    }

    /**
     * Validate custom CSS to prevent XSS
     */
    public static function validateCustomCss(string $css): bool
    {
        // Check for dangerous patterns
        $dangerousPatterns = [
            '/javascript:/i',
            '/<script/i',
            '/expression\s*\(/i',
            '/behavior\s*:/i',
        ];

        foreach ($dangerousPatterns as $pattern) {
            if (preg_match($pattern, $css)) {
                return false;
            }
        }

        return true;
    }
}
