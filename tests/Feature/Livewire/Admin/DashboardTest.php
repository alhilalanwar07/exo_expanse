<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\Dashboard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_renders_successfully()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Livewire::actingAs($admin)
            ->test(Dashboard::class)
            ->assertStatus(200);
    }
}
