<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class InvitationBuilderTest extends TestCase
{
    use RefreshDatabase;

    public function test_builder_component_mounts_correctly()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test('pages::invitation.builder')
            ->assertOk()
            ->assertSee('Buat Undangan Baru');
    }

    public function test_builder_hydrates_from_invitation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $theme = Theme::factory()->create();
        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'groom_name' => 'Romeo Montague', // Fullname
            'groom_nickname' => 'Romeo',
            'bride_name' => 'Juliet Capulet', // Fullname
            'bride_nickname' => 'Juliet',
            'custom_styles' => [
                'name_order' => 'bride_first',
                'event_type' => 'resepsi_only',
            ],
            'custom_colors' => [
                'primary' => '#ff0000',
            ],
        ]);

        Livewire::test('pages::invitation.builder', ['id' => $invitation->id])
            ->assertSet('groom_name', 'Romeo Montague')
            ->assertSet('groom_nickname', 'Romeo')
            ->assertSet('bride_name', 'Juliet Capulet')
            ->assertSet('name_order', 'bride_first')
            ->assertSet('event_type', 'resepsi_only')
            ->assertSet('settings.primary_color', '#ff0000');
    }

    public function test_builder_saves_data_correctly()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $theme = Theme::factory()->create();

        Livewire::test('pages::invitation.builder')
            ->set('title', 'Test Title')
            ->set('theme_id', $theme->id)
            ->set('groom_name', 'Adam')
            ->set('groom_nickname', 'Ads')
            ->set('bride_name', 'Eve')
            ->set('bride_nickname', 'Evi')
            ->set('name_order', 'groom_first')
            ->set('event_type', 'both')
            ->set('settings.primary_color', '#00ff00')
            ->set('groom_father', 'F')
            ->set('groom_mother', 'M')
            ->set('bride_father', 'D')
            ->set('bride_mother', 'M')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('invitations', [
            'user_id' => $user->id,
            'title' => 'Test Title',
            'groom_name' => 'Adam',
            'groom_nickname' => 'Ads',
            'bride_name' => 'Eve',
            'bride_nickname' => 'Evi',
            'groom_father' => 'F',
            'groom_mother' => 'M',
        ]);
    }

    public function test_builder_publishes_correctly()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $theme = Theme::factory()->create();
        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'is_published' => false,
        ]);

        Livewire::test('pages::invitation.builder', ['id' => $invitation->id])
            ->call('publish')
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard'));

        $this->assertTrue($invitation->refresh()->is_published);
    }
}
