<?php

namespace App\Services;

use App\Models\MobileAccessCode;
use App\Models\MobileDeviceSession;
use App\Models\User;
use Illuminate\Support\Str;
use InvalidArgumentException;

class MobileAccessService
{
    private const ACCESS_CODE_TTL_MINUTES = 10;

    private const ACCESS_TOKEN_TTL_MINUTES = 60;

    private const REFRESH_TOKEN_TTL_DAYS = 30;

    public function issueAccessCode(User $user, ?string $deviceAlias = null, ?string $platform = null): array
    {
        $plainCode = $this->generateUniqueAccessCode();
        $expiresAt = now()->addMinutes(self::ACCESS_CODE_TTL_MINUTES);

        MobileAccessCode::create([
            'user_id' => $user->id,
            'code_hash' => $this->hashValue($plainCode),
            'code_prefix' => substr($plainCode, 0, 7),
            'code_hint' => substr($plainCode, -4),
            'device_alias' => $deviceAlias,
            'platform' => $platform,
            'expires_at' => $expiresAt,
        ]);

        return [
            'access_code' => $plainCode,
            'expires_at' => $expiresAt->toIso8601String(),
        ];
    }

    public function exchangeAccessCode(
        string $accessCode,
        ?string $deviceAlias,
        ?string $platform,
        ?string $ipAddress,
        ?string $userAgent
    ): array {
        $normalizedCode = strtoupper(trim($accessCode));

        if ($normalizedCode === '') {
            throw new InvalidArgumentException('Kode akses wajib diisi.');
        }

        $code = MobileAccessCode::query()
            ->with('user')
            ->available()
            ->where('code_hash', $this->hashValue($normalizedCode))
            ->first();

        if (! $code || ! $code->user) {
            throw new InvalidArgumentException('Kode akses tidak valid atau sudah kedaluwarsa.');
        }

        $code->forceFill([
            'consumed_at' => now(),
            'consumed_by_ip' => $ipAddress,
        ])->save();

        $tokenBundle = $this->generateTokenBundle();

        $session = MobileDeviceSession::create([
            'user_id' => $code->user_id,
            'device_alias' => $deviceAlias ?: ($code->device_alias ?: 'Perangkat Owner'),
            'platform' => $platform ?: ($code->platform ?: 'unknown'),
            'access_token_hash' => $this->hashValue($tokenBundle['access_token']),
            'refresh_token_hash' => $this->hashValue($tokenBundle['refresh_token']),
            'access_expires_at' => $tokenBundle['access_expires_at'],
            'refresh_expires_at' => $tokenBundle['refresh_expires_at'],
            'last_used_at' => now(),
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);

        return $this->buildSessionResponse($code->user, $session, $tokenBundle);
    }

    public function createSessionForUser(
        User $user,
        ?string $deviceAlias,
        ?string $platform,
        ?string $ipAddress,
        ?string $userAgent
    ): array {
        $tokenBundle = $this->generateTokenBundle();

        $session = MobileDeviceSession::create([
            'user_id' => $user->id,
            'device_alias' => $deviceAlias ?: 'Perangkat Mobile',
            'platform' => $platform ?: 'unknown',
            'access_token_hash' => $this->hashValue($tokenBundle['access_token']),
            'refresh_token_hash' => $this->hashValue($tokenBundle['refresh_token']),
            'access_expires_at' => $tokenBundle['access_expires_at'],
            'refresh_expires_at' => $tokenBundle['refresh_expires_at'],
            'last_used_at' => now(),
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);

        return $this->buildSessionResponse($user, $session, $tokenBundle);
    }

    public function refreshSession(string $refreshToken, ?string $ipAddress, ?string $userAgent): array
    {
        $normalizedToken = trim($refreshToken);

        if ($normalizedToken === '') {
            throw new InvalidArgumentException('Refresh token wajib diisi.');
        }

        $session = MobileDeviceSession::query()
            ->with('user')
            ->active()
            ->where('refresh_token_hash', $this->hashValue($normalizedToken))
            ->first();

        if (! $session || ! $session->user || $session->refresh_expires_at->isPast()) {
            throw new InvalidArgumentException('Refresh token tidak valid atau sudah kedaluwarsa.');
        }

        $tokenBundle = $this->generateTokenBundle();

        $session->forceFill([
            'access_token_hash' => $this->hashValue($tokenBundle['access_token']),
            'refresh_token_hash' => $this->hashValue($tokenBundle['refresh_token']),
            'access_expires_at' => $tokenBundle['access_expires_at'],
            'refresh_expires_at' => $tokenBundle['refresh_expires_at'],
            'last_used_at' => now(),
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ])->save();

        return $this->buildSessionResponse($session->user, $session, $tokenBundle);
    }

    public function findActiveSessionByAccessToken(string $accessToken): ?MobileDeviceSession
    {
        $normalizedToken = trim($accessToken);

        if ($normalizedToken === '') {
            return null;
        }

        $session = MobileDeviceSession::query()
            ->with('user')
            ->active()
            ->where('access_token_hash', $this->hashValue($normalizedToken))
            ->first();

        if (! $session || ! $session->isAccessActive()) {
            return null;
        }

        return $session;
    }

    public function touchSession(MobileDeviceSession $session, ?string $ipAddress, ?string $userAgent): void
    {
        $session->forceFill([
            'last_used_at' => now(),
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ])->save();
    }

    public function revokeSession(MobileDeviceSession $session): void
    {
        if ($session->revoked_at !== null) {
            return;
        }

        $session->forceFill([
            'revoked_at' => now(),
        ])->save();
    }

    public function listDevices(User $user, ?int $currentSessionId = null): array
    {
        return $user->mobileDeviceSessions()
            ->active()
            ->orderByDesc('last_used_at')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (MobileDeviceSession $session) => [
                'id' => $session->id,
                'device_alias' => $session->device_alias ?: 'Perangkat Tanpa Nama',
                'platform' => $session->platform,
                'last_used_at' => $session->last_used_at?->toIso8601String(),
                'created_at' => $session->created_at?->toIso8601String(),
                'is_current' => $currentSessionId !== null && $currentSessionId === $session->id,
            ])
            ->values()
            ->all();
    }

    private function generateUniqueAccessCode(): string
    {
        do {
            $code = 'EXO-'.Str::upper(Str::random(4)).'-'.Str::upper(Str::random(4));
            $exists = MobileAccessCode::query()
                ->where('code_hash', $this->hashValue($code))
                ->exists();
        } while ($exists);

        return $code;
    }

    private function generateTokenBundle(): array
    {
        return [
            'access_token' => 'exo_at_'.Str::random(64),
            'refresh_token' => 'exo_rt_'.Str::random(64),
            'access_expires_at' => now()->addMinutes(self::ACCESS_TOKEN_TTL_MINUTES),
            'refresh_expires_at' => now()->addDays(self::REFRESH_TOKEN_TTL_DAYS),
        ];
    }

    private function buildSessionResponse(User $user, MobileDeviceSession $session, array $tokenBundle): array
    {
        $workspaceLabel = $user->invitations()->latest()->value('title') ?? ('Workspace '.$user->name);

        return [
            'session' => [
                'workspace_id' => 'user-'.$user->id,
                'workspace_label' => $workspaceLabel,
                'owner_name' => $user->name,
                'device_alias' => $session->device_alias,
                'access_token' => $tokenBundle['access_token'],
                'refresh_token' => $tokenBundle['refresh_token'],
                'expires_at' => $tokenBundle['access_expires_at']->toIso8601String(),
                'refresh_expires_at' => $tokenBundle['refresh_expires_at']->toIso8601String(),
            ],
        ];
    }

    private function hashValue(string $value): string
    {
        $appKey = (string) config('app.key', 'exo-expanse');

        return hash_hmac('sha256', $value, $appKey);
    }
}
