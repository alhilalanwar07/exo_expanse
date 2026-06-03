<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\ThemeManagement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ThemeManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_renders_successfully()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Livewire::actingAs($admin)
            ->test(ThemeManagement::class)
            ->assertStatus(200);
    }
}
