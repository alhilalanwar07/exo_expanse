<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThemeRenderingTest extends TestCase
{
    use RefreshDatabase;

    public function test_generic_theme_renders_correctly()
    {
        $user = User::factory()->create();
        $theme = Theme::factory()->create(['slug' => 'generic', 'view_file' => 'components.themes.generic']);

        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'theme_id' => $theme->id,
            'slug' => 'generic-test',
            'is_published' => true,
            'enable_gallery' => true,
            'gallery_images' => ['gallery1.jpg'],
        ]);

        $response = $this->get('/i/generic-test');

        $response->assertStatus(200);
        $response->assertSee('gallery1.jpg');
    }

    public function test_generic_theme_renders_dynamic_styles()
    {
        // Create a theme with specific colors
        $theme = Theme::factory()->create([
            'slug' => 'test-dynamic-style',
            'view_file' => 'themes.generic',
            'primary_color' => '#123456',
        ]);

        $invitation = Invitation::factory()->create([
            'theme_id' => $theme->id,
            'groom_name' => 'Test Groom',
            'bride_name' => 'Test Bride',
            'slug' => 'test-dynamic-style-invitation',
        ]);

        $this->get('/i/'.$invitation->slug)
            ->assertSee('--color-primary: #123456', false)
            ->assertSee('--font-heading', false);
    }
}
