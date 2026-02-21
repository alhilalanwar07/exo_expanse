<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\Dashboard;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;
    public function test_renders_successfully()
    {
        $admin = \App\Models\User::factory()->create(['role' => 'admin']);

        Livewire::actingAs($admin)
            ->test(Dashboard::class)
            ->assertStatus(200);
    }
}
