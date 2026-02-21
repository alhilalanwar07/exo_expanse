<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\ThemeManagement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ThemeManagementTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_renders_successfully()
    {
        $admin = \App\Models\User::factory()->create(['role' => 'admin']);

        Livewire::actingAs($admin)
            ->test(ThemeManagement::class)
            ->assertStatus(200);
    }
}
