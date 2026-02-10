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
                'primary_color' => '#D4A5A5',
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
                'view_file' => 'themes.sage-garden',
                'thumbnail_url' => '/images/themes/sage-garden.jpg',
                'is_active' => true,
                'is_premium' => true,
                'primary_color' => '#6B8E6B',
                'secondary_color' => '#2D3B2D',
                'accent_color' => '#C4A35A',
                'text_color' => '#4A5548',
                'heading_color' => '#2D3B2D',
                'background_color' => '#F5F7F2',
                'heading_font' => 'Cormorant Garamond',
                'body_font' => 'Lora',
                'accent_font' => 'Dancing Script',
                'container_max_width' => 460,
                'heading_size' => 42,
                'border_radius' => '18px',
                'button_style' => 'rounded',
                'overlay_opacity' => 55,
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

            // 7. White Blossom - Elegant dengan frame bunga putih-emas
            [
                'name' => 'White Blossom',
                'slug' => 'white-blossom',
                'view_file' => 'themes.white-blossom',
                'thumbnail_url' => '/images/themes/white-blossom.jpg',
                'is_active' => true,
                'is_premium' => true,
                'primary_color' => '#C9A227',
                'secondary_color' => '#1A1A1A',
                'accent_color' => '#E8D5A3',
                'text_color' => '#4A4A4A',
                'heading_color' => '#1A1A1A',
                'background_color' => '#FFFEF9',
                'heading_font' => 'Cinzel',
                'body_font' => 'Cormorant Garamond',
                'accent_font' => 'Great Vibes',
                'container_max_width' => 480,
                'heading_size' => 42,
                'border_radius' => '20px',
                'button_style' => 'rounded',
                'overlay_opacity' => 55,
            ],

            // 8. Islamic Gold - Mewah dan Elegan
            [
                'name' => 'Islamic Gold',
                'slug' => 'islamic-gold',
                'view_file' => 'themes.islamic-gold',
                'thumbnail_url' => '/images/themes/islamic-gold.jpg',
                'is_active' => true,
                'is_premium' => true,
                'primary_color' => '#D4AF37',
                'secondary_color' => '#1a1a1a',
                'accent_color' => '#FFD700',
                'text_color' => '#f0f0f0',
                'heading_color' => '#D4AF37',
                'background_color' => '#050505',
                'heading_font' => 'Cormorant Garamond',
                'body_font' => 'Montserrat',
                'accent_font' => 'Great Vibes',
                'container_max_width' => 500,
                'heading_size' => 48,
                'border_radius' => '20px',
                'button_style' => 'rounded',
                'overlay_opacity' => 80,
            ],

            // 9. Agung Bali - Elegan dengan nuansa Bali
            [
                'name' => 'Agung Bali',
                'slug' => 'agung-bali',
                'view_file' => 'themes.agung-bali',
                'thumbnail_url' => '/images/themes/agung-bali.jpg',
                'is_active' => true,
                'is_premium' => true,
                'primary_color' => '#D4AF37', // bali-gold
                'secondary_color' => '#3E2723', // bali-brown
                'accent_color' => '#8D6E63', // bali-red
                'text_color' => '#2C241B',
                'heading_color' => '#3E2723',
                'background_color' => '#FAF7F2',
                'heading_font' => 'Cinzel Decorative',
                'body_font' => 'Noto Serif Display',
                'accent_font' => 'Pinyon Script',
                'container_max_width' => 500,
                'heading_size' => 45,
                'border_radius' => '4px',
                'button_style' => 'boxy',
                'overlay_opacity' => 60,
            ],

            // 10. Rustic Sage - Natural dengan warna hijau sage (Variant 2)
            [
                'name' => 'Rustic Sage',
                'slug' => 'rustic-sage',
                'view_file' => 'themes.rustic-sage',
                'thumbnail_url' => '/images/themes/rustic-sage.jpg',
                'is_active' => true,
                'is_premium' => true,
                'primary_color' => '#6F8B68', // sage-medium
                'secondary_color' => '#4A5D46', // sage-dark
                'accent_color' => '#D6C0B3', // dusty-pink
                'text_color' => '#3D403D',
                'heading_color' => '#6F8B68',
                'background_color' => '#F9F7F2',
                'heading_font' => 'Playfair Display',
                'body_font' => 'Lato',
                'accent_font' => 'Dancing Script',
                'container_max_width' => 500,
                'heading_size' => 42,
                'border_radius' => '24px',
                'button_style' => 'rounded',
                'overlay_opacity' => 50,
            ],

            // 11. Mystical Forest - Hutan Magis dengan nuansa gelap dan emas
            [
                'name' => 'Mystical Forest',
                'slug' => 'mystical-forest',
                'view_file' => 'themes.mystical-forest',
                'thumbnail_url' => '/images/themes/mystical-forest.jpg',
                'is_active' => true,
                'is_premium' => true,
                'primary_color' => '#134640', // mystic-teal
                'secondary_color' => '#071A18', // mystic-dark
                'accent_color' => '#D4AF37', // mystic-gold
                'text_color' => '#E8E3D1',
                'heading_color' => '#D4AF37',
                'background_color' => '#071A18',
                'heading_font' => 'Cinzel',
                'body_font' => 'Fauna One',
                'accent_font' => 'Pinyon Script',
                'container_max_width' => 500,
                'heading_size' => 45,
                'border_radius' => '16px',
                'button_style' => 'rounded',
                'overlay_opacity' => 70,
            ],

            // 12. Javanese Heritage - Jawa Keraton Premium
            [
                'name' => 'Javanese Heritage',
                'slug' => 'javanese-heritage',
                'view_file' => 'themes.javanese-heritage',
                'thumbnail_url' => '/images/themes/javanese-heritage.jpg',
                'is_active' => true,
                'is_premium' => true,
                'primary_color' => '#D4AF37', // java-gold
                'secondary_color' => '#3E2723', // java-brown
                'accent_color' => '#AA8C2C', // java-gold-dim
                'text_color' => '#3E2723',
                'heading_color' => '#D4AF37',
                'background_color' => '#F4F1EA',
                'heading_font' => 'Cinzel Decorative',
                'body_font' => 'Lora',
                'accent_font' => 'Pinyon Script',
                'container_max_width' => 500,
                'heading_size' => 45,
                'border_radius' => '8px',
                'button_style' => 'rounded',
                'overlay_opacity' => 60,
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
