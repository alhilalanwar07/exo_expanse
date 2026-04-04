<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MobileDeviceSession extends Model
{
    protected $fillable = [
        'user_id',
        'device_alias',
        'platform',
        'access_token_hash',
        'refresh_token_hash',
        'access_expires_at',
        'refresh_expires_at',
        'last_used_at',
        'revoked_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'access_expires_at' => 'datetime',
        'refresh_expires_at' => 'datetime',
        'last_used_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->whereNull('revoked_at')
            ->where('refresh_expires_at', '>', now());
    }

    public function isAccessActive(): bool
    {
        return $this->revoked_at === null && $this->access_expires_at->isFuture();
    }
}
