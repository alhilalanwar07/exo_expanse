<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Notifications\ActivateAccountNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class MobileAuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_mobile_register_creates_user_and_sends_activation_email(): void
    {
        Notification::fake();

        $response = $this->postJson('/api/mobile/auth/register', [
            'name' => 'Rina Putri',
            'email' => 'rina@example.com',
            'password' => 'secret1234',
        ]);

        $response->assertCreated()
            ->assertJson([
                'message' => 'Registrasi berhasil. Link aktivasi akun sudah dikirim ke email Anda.',
                'data' => [
                    'name' => 'Rina Putri',
                    'email' => 'rina@example.com',
                    'requires_email_verification' => true,
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'rina@example.com',
            'name' => 'Rina Putri',
        ]);

        $user = User::query()->where('email', 'rina@example.com')->firstOrFail();

        $this->assertNull($user->email_verified_at);
        Notification::assertSentTo($user, ActivateAccountNotification::class);
    }

    public function test_mobile_login_requires_verified_email(): void
    {
        $user = User::factory()->unverified()->create([
            'email' => 'owner@example.com',
            'password' => Hash::make('secret1234'),
        ]);

        $response = $this->postJson('/api/mobile/auth/login', [
            'email' => $user->email,
            'password' => 'secret1234',
            'platform' => 'ios',
            'device_alias' => 'iPhone Owner',
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'message' => 'Akun belum aktif. Silakan cek email Anda untuk aktivasi.',
                'requires_email_verification' => true,
            ]);
    }

    public function test_mobile_login_returns_session_for_verified_user(): void
    {
        $user = User::factory()->create([
            'email' => 'owner2@example.com',
            'password' => Hash::make('secret1234'),
        ]);

        $response = $this->postJson('/api/mobile/auth/login', [
            'email' => $user->email,
            'password' => 'secret1234',
            'platform' => 'ios',
            'device_alias' => 'iPhone Owner',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'session' => [
                    'workspace_id',
                    'workspace_label',
                    'owner_name',
                    'device_alias',
                    'access_token',
                    'refresh_token',
                    'expires_at',
                    'refresh_expires_at',
                ],
            ]);

        $this->assertDatabaseHas('mobile_device_sessions', [
            'user_id' => $user->id,
            'platform' => 'ios',
            'device_alias' => 'iPhone Owner',
        ]);
    }
}
