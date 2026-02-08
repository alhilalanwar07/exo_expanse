<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    public function run(): void
    {
        $themes = [
            // 1. Royal Gold - Mewah dengan warna emas
            [
                'name' => 'Royal Gold',
                'slug' => 'royal-gold',
                'view_file' => 'themes.royal-gold',  // Format: themes.{slug}
                'thumbnail_url' => '/images/themes/royal-gold.jpg',
                'is_active' => true,
                'is_premium' => true,
                'primary_color' => '#C9A227',
                'secondary_color' => '#1A1A1A',
                'accent_color' => '#E8D5A3',
                'text_color' => '#4A4A4A',
                'heading_color' => '#1A1A1A',
                'background_color' => '#FDF8F0',
                'heading_font' => 'Cormorant Garamond',
                'body_font' => 'Montserrat',
                'accent_font' => 'Great Vibes',
                'container_max_width' => 480,
                'heading_size' => 40,
                'border_radius' => '20px',
                'button_style' => 'rounded',
                'overlay_opacity' => 60,
            ],

            // 2. Floral Romance - Romantis dengan rose pink
            [
                'name' => 'Floral Romance',
                'slug' => 'floral-romance',
                'view_file' => 'themes.floral-romance',
                'thumbnail_url' => '/images/themes/floral-romance.jpg',
                'is_active' => true,
                'is_premium' => true,
                'primary_color' => '#E8919B',
                'secondary_color' => '#3D3D3D',
                'accent_color' => '#9CAF88',
                'text_color' => '#5A5A5A',
                'heading_color' => '#3D3D3D',
                'background_color' => '#FFFBFC',
                'heading_font' => 'Playfair Display',
                'body_font' => 'Lora',
                'accent_font' => 'Pinyon Script',
                'container_max_width' => 450,
                'heading_size' => 40,
                'border_radius' => '25px',
                'button_style' => 'rounded',
                'overlay_opacity' => 50,
            ],

            // 3. Modern Elegance - Modern dengan gold dan cream
            [
                'name' => 'Modern Elegance',
                'slug' => 'modern-elegance',
                'view_file' => 'themes.modern-elegance',
                'thumbnail_url' => '/images/themes/modern-elegance.jpg',
                'is_active' => true,
                'is_premium' => false,
                'primary_color' => '#C5A059',
                'secondary_color' => '#776e62',
                'accent_color' => '#E8D5A3',
                'text_color' => '#2D2926',
                'heading_color' => '#2D2926',
                'background_color' => '#FDFAF5',
                'heading_font' => 'Playfair Display',
                'body_font' => 'Inter',
                'accent_font' => 'Great Vibes',
                'container_max_width' => 440,
                'heading_size' => 36,
                'border_radius' => '16px',
                'button_style' => 'rounded',
                'overlay_opacity' => 40,
            ],

            // 4. Generic (Default) - Netral dan bersih
            [
                'name' => 'Classic Minimal',
                'slug' => 'generic',
                'view_file' => 'themes.generic',
                'thumbnail_url' => '/images/themes/generic.jpg',
                'is_active' => true,
                'is_premium' => false,
                'primary_color' => '#1f2937',
                'secondary_color' => '#f8fafc',
                'accent_color' => '#f59e0b',
                'text_color' => '#374151',
                'heading_color' => '#111827',
                'background_color' => '#f8fafc',
                'heading_font' => 'Playfair Display',
                'body_font' => 'Inter',
                'accent_font' => 'Great Vibes',
                'container_max_width' => 480,
                'heading_size' => 42,
                'border_radius' => '12px',
                'button_style' => 'rounded',
                'overlay_opacity' => 50,
            ],

            // 5. Sage Garden - Natural dengan warna hijau sage
            [
                'name' => 'Sage Garden',
                'slug' => 'sage-garden',
                'view_file' => 'themes.generic',  // Uses generic template
                'thumbnail_url' => '/images/themes/sage-garden.jpg',
                'is_active' => true,
                'is_premium' => false,
                'primary_color' => '#6b8e6b',
                'secondary_color' => '#2d3b2d',
                'accent_color' => '#a8c5a8',
                'text_color' => '#374151',
                'heading_color' => '#2d3b2d',
                'background_color' => '#f5f7f5',
                'heading_font' => 'Cormorant Garamond',
                'body_font' => 'Lora',
                'accent_font' => 'Dancing Script',
                'container_max_width' => 460,
                'heading_size' => 38,
                'border_radius' => '20px',
                'button_style' => 'rounded',
                'overlay_opacity' => 45,
            ],

            // 6. Midnight Dark - Dark mode elegant
            [
                'name' => 'Midnight Elegance',
                'slug' => 'midnight-dark',
                'view_file' => 'themes.generic',  // Uses generic template
                'thumbnail_url' => '/images/themes/midnight-dark.jpg',
                'is_active' => true,
                'is_premium' => true,
                'primary_color' => '#f59e0b',
                'secondary_color' => '#0f172a',
                'accent_color' => '#fbbf24',
                'text_color' => '#e2e8f0',
                'heading_color' => '#f8fafc',
                'background_color' => '#1e293b',
                'heading_font' => 'Playfair Display',
                'body_font' => 'Inter',
                'accent_font' => 'Great Vibes',
                'container_max_width' => 480,
                'heading_size' => 44,
                'border_radius' => '16px',
                'button_style' => 'rounded',
                'overlay_opacity' => 70,
            ],
        ];

        foreach ($themes as $themeData) {
            Theme::updateOrCreate(
                ['slug' => $themeData['slug']],
                $themeData
            );
        }

        $this->command->info('✅ '.count($themes).' themes seeded successfully!');
    }
}
