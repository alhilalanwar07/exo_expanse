<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\InvitationManagement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class InvitationManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_renders_successfully()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Livewire::actingAs($admin)
            ->test(InvitationManagement::class)
            ->assertStatus(200);
    }
}
