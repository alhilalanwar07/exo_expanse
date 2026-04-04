<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitationViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_invitation_route_works()
    {
        $user = User::factory()->create();
        $theme = Theme::factory()->create(['slug' => 'royal-gold', 'view_file' => 'themes.royal-gold']);

        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'theme_id' => $theme->id,
            'slug' => 'romeo-juliet',
            'is_published' => true,
        ]);

        $response = $this->get('/i/romeo-juliet');

        $response->assertStatus(200);
        $response->assertViewIs('themes.royal-gold');
        $response->assertSee($invitation->groom_name);

        // Assert Metadata
        $response->assertSee('og:title', false);
        $response->assertSee('og:description', false);
    }

    public function test_unpublished_invitation_404_for_guest()
    {
        $user = User::factory()->create();
        $theme = Theme::factory()->create(['slug' => 'royal-gold', 'view_file' => 'themes.royal-gold']);

        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'theme_id' => $theme->id,
            'slug' => 'hidden-wedding',
            'is_published' => false,
        ]);

        $response = $this->get('/i/hidden-wedding');

        $response->assertStatus(404);
    }

    public function test_unpublished_invitation_visible_for_owner()
    {
        // Logic in controller was: if (! $invitation->is_published && ! auth()->check())
        // So any auth user can see it? Or validation needed?
        // For now testing simple auth check

        $user = User::factory()->create();
        $theme = Theme::factory()->create(['slug' => 'royal-gold', 'view_file' => 'themes.royal-gold']);

        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'theme_id' => $theme->id,
            'slug' => 'hidden-wedding',
            'is_published' => false,
        ]);

        $this->actingAs($user);
        $response = $this->get('/i/hidden-wedding');

        $response->assertStatus(200);
    }
}
