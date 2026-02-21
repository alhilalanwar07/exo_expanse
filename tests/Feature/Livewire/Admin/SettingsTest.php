<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\Settings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_renders_successfully()
    {
        $admin = \App\Models\User::factory()->create(['role' => 'admin']);

        Livewire::actingAs($admin)
            ->test(Settings::class)
            ->assertStatus(200);
    }
}
