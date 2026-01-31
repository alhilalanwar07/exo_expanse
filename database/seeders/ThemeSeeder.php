<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    public function run(): void
    {
        $themes = [
            [
                'name' => 'Floral Dream',
                'slug' => 'floral-dream',
                'view_file' => 'invitation.themes.floral',
                'thumbnail_url' => 'themes/floral.jpg',
                'is_active' => true,
                'is_premium' => false,
            ],
            [
                'name' => 'Rustic Garden',
                'slug' => 'rustic-garden',
                'view_file' => 'themes.rustic-garden',
                'thumbnail_url' => '/images/themes/rustic-garden.jpg',
                'is_active' => true,
                'is_premium' => false,
            ],
            [
                'name' => 'Modern Minimalist',
                'slug' => 'modern-minimalist',
                'view_file' => 'themes.modern-minimalist',
                'thumbnail_url' => '/images/themes/modern-minimalist.jpg',
                'is_active' => true,
                'is_premium' => true,
            ],
            [
                'name' => 'Royal Gold',
                'slug' => 'royal-gold',
                'view_file' => 'themes.royal-gold',
                'thumbnail_url' => '/images/themes/royal-gold.jpg',
                'is_active' => true,
                'is_premium' => true,
            ],
        ];

        foreach ($themes as $theme) {
            Theme::updateOrCreate(
                ['slug' => $theme['slug']],
                $theme
            );
        }
    }
}
