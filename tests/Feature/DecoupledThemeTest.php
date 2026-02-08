<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DecoupledThemeTest extends TestCase
{
    use RefreshDatabase;

    public function test_floral_romance_theme_renders_correctly()
    {
        $user = User::factory()->create();

        // Ensure theme exists (simulating seeder)
        $theme = Theme::create([
            'name' => 'Floral Romance',
            'slug' => 'floral-romance',
            'view_file' => 'themes.floral-romance',
            'is_active' => true,
        ]);

        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'theme_id' => $theme->id,
            'slug' => 'romeo-juliet-floral',
            'is_published' => true,
            'groom_name' => 'Romeo',
            'bride_name' => 'Juliet',
        ]);

        $response = $this->get('/i/romeo-juliet-floral');

        $response->assertStatus(200);
        $response->assertViewIs('themes.floral-romance');

        // Check for specific independent layout elements
        $response->assertSee('<!DOCTYPE html>', false); // Should be full page
        $response->assertSee('<html', false);
        $response->assertSee('Romeo', false);
        $response->assertSee('Juliet', false);

        // Check for SEO partial usage
        $response->assertSee('og:title', false);
    }
}
