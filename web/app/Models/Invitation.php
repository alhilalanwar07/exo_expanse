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
        'theme_id',
        'user_id',
        'title',
        'bride_name',
        'bride_nickname',
        'bride_father',
        'bride_mother',
        'bride_photo',
        'groom_name',
        'groom_nickname',
        'groom_father',
        'groom_mother',
        'groom_photo',
        'akad_date',
        'akad_time',
        'akad_venue',
        'akad_address',
        'akad_maps_link',
        'resepsi_date',
        'resepsi_time',
        'resepsi_venue',
        'resepsi_address',
        'resepsi_maps_link',
        'cover_image',
        'background_music',
        'gallery_images',
        'love_story',
        'bank_accounts',
        'instagram_bride',
        'instagram_groom',
        'custom_colors',
        'custom_fonts',
        'custom_styles',
        'slug',
        'is_published',
        'enable_rsvp',
        'enable_wishes',
        'enable_gallery',
        'enable_gift',
    ];

    protected $casts = [
        'akad_date' => 'datetime',
        'resepsi_date' => 'datetime',
        'gallery_images' => 'array',
        'love_story' => 'array',
        'bank_accounts' => 'array',
        'custom_colors' => 'array',
        'custom_fonts' => 'array',
        'custom_styles' => 'array',
        'is_published' => 'boolean',
        'enable_rsvp' => 'boolean',
        'enable_wishes' => 'boolean',
        'enable_gallery' => 'boolean',
        'enable_gift' => 'boolean',
    ];

    // ========================
    // RELATIONSHIPS
    // ========================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
    }

    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class); // Assuming Guest model exists
    }

    public function rsvps(): HasMany
    {
        return $this->hasMany(Rsvp::class); // Assuming Rsvp model exists
    }

    public function wishes(): HasMany
    {
        return $this->hasMany(Wish::class); // Assuming Wish model exists
    }

    public function photos(): HasMany
    {
        return $this->hasMany(InvitationPhoto::class)->orderBy('order');
    }

    public function publish(): bool
    {
        return $this->update(['is_published' => true]);
    }

    public function unpublish(): bool
    {
        return $this->update(['is_published' => false]);
    }

    public function getThemeCustomization(): array
    {
        $themeConfig = $this->theme ? $this->theme->toConfigArray() : [];

        $colors = array_merge($themeConfig['colors'] ?? [], $this->custom_colors ?? []);
        $fonts = array_merge($themeConfig['fonts'] ?? [], $this->custom_fonts ?? []);
        $styles = array_merge($themeConfig['layout'] ?? [], $this->custom_styles ?? []);

        return [
            'colors' => $colors,
            'fonts' => $fonts,
            'styles' => $styles,
        ];
    }
}
