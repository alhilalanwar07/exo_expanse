<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Services\MobileAccessService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MobileAccessApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_exchange_access_code_for_mobile_session(): void
    {
        $user = User::factory()->create(['name' => 'Budi']);
        $service = app(MobileAccessService::class);

        $issued = $service->issueAccessCode($user, 'iPhone Budi', 'ios');

        $response = $this->postJson('/api/mobile/access/exchange', [
            'access_code' => $issued['access_code'],
            'device_alias' => 'iPhone Budi',
            'platform' => 'ios',
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
            'device_alias' => 'iPhone Budi',
        ]);
    }

    public function test_exchange_fails_with_invalid_access_code(): void
    {
        $response = $this->postJson('/api/mobile/access/exchange', [
            'access_code' => 'EXO-INVALID',
            'device_alias' => 'iPhone',
            'platform' => 'ios',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Kode akses tidak valid atau sudah kedaluwarsa.',
            ]);
    }

    public function test_can_refresh_session_and_access_protected_routes(): void
    {
        $user = User::factory()->create(['name' => 'Rina']);
        $service = app(MobileAccessService::class);

        $issued = $service->issueAccessCode($user, 'iPhone Rina', 'ios');

        $exchangeResponse = $this->postJson('/api/mobile/access/exchange', [
            'access_code' => $issued['access_code'],
            'device_alias' => 'iPhone Rina',
            'platform' => 'ios',
        ])->assertOk();

        $session = $exchangeResponse->json('session');
        $accessToken = $session['access_token'];
        $refreshToken = $session['refresh_token'];

        $this->withHeader('Authorization', 'Bearer '.$accessToken)
            ->getJson('/api/mobile/access/devices')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'device_alias', 'platform', 'last_used_at', 'created_at', 'is_current'],
                ],
            ]);

        $refreshResponse = $this->postJson('/api/mobile/access/refresh', [
            'refresh_token' => $refreshToken,
        ])->assertOk();

        $newAccessToken = $refreshResponse->json('session.access_token');

        $this->withHeader('Authorization', 'Bearer '.$newAccessToken)
            ->postJson('/api/mobile/access/revoke')
            ->assertOk()
            ->assertJson([
                'success' => true,
            ]);

        $this->withHeader('Authorization', 'Bearer '.$newAccessToken)
            ->getJson('/api/mobile/access/devices')
            ->assertStatus(401);
    }

    public function test_authenticated_web_user_can_issue_access_code(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/mobile/access/issue', [
                'device_alias' => 'iPhone Utama',
                'platform' => 'ios',
            ]);

        $response->assertOk()
            ->assertJsonStructure([
                'access_code',
                'expires_at',
            ]);
    }
}
