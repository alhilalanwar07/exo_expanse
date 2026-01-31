<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invitation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'theme_id',
        'slug',
        'title',
        'type',
        'cover_title',
        'cover_subtitle',
        'cover_photo',
        'event_date',
        'content',
        'settings',
        'music_enabled',
        'music_url',
        'gift_enabled',
        'gift_accounts',
        'countdown_enabled',
        'rsvp_enabled',
        'wishes_enabled',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'datetime',
            'content' => 'array',
            'settings' => 'array',
            'gift_accounts' => 'array',
            'music_enabled' => 'boolean',
            'gift_enabled' => 'boolean',
            'countdown_enabled' => 'boolean',
            'rsvp_enabled' => 'boolean',
            'wishes_enabled' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    // ==================
    // RELATIONSHIPS
    // ==================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(InvitationPhoto::class)->orderBy('order');
    }

    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class);
    }

    public function wishes(): HasMany
    {
        return $this->hasMany(Wish::class)->latest();
    }

    // ==================
    // ACCESSORS
    // ==================

    public function getGroomNameAttribute(): string
    {
        return $this->content['groom_name'] ?? '';
    }

    public function getBrideNameAttribute(): string
    {
        return $this->content['bride_name'] ?? '';
    }

    public function getCoupleNamesAttribute(): string
    {
        $nameOrder = $this->content['name_order'] ?? 'groom_first';
        
        if ($nameOrder === 'bride_first') {
            return $this->bride_name . ' & ' . $this->groom_name;
        }
        
        return $this->groom_name . ' & ' . $this->bride_name;
    }

    public function getPublicUrlAttribute(): string
    {
        return route('invitation.show', $this->slug);
    }

    public function getCoverPhotoUrlAttribute(): ?string
    {
        if (!$this->cover_photo) {
            return null;
        }
        
        return asset('storage/' . $this->cover_photo);
    }

    // ==================
    // METHODS
    // ==================

    public function isOwnedBy(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    public function publish(): void
    {
        $this->update(['is_published' => true]);
    }

    public function unpublish(): void
    {
        $this->update(['is_published' => false]);
    }

    public function getContentValue(string $key, mixed $default = null): mixed
    {
        return $this->content[$key] ?? $default;
    }

    public function getSettingValue(string $key, mixed $default = null): mixed
    {
        return $this->settings[$key] ?? $default;
    }
}
