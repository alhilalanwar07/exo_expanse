<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Theme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'view_file',
        'thumbnail_url',
        'is_active',
        'is_premium',

        // Styling Configuration
        'primary_color',
        'secondary_color',
        'accent_color',
        'text_color',
        'heading_color',
        'background_color',
        'heading_font',
        'body_font',
        'accent_font',
        'container_max_width',
        'heading_size',
        'border_radius',
        'overlay_gradient',
        'overlay_opacity',
        'button_style',
        'custom_css',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_premium' => 'boolean',
    ];

    /**
     * Available theme categories.
     */
    public const CATEGORIES = [
        'Modern' => 'Modern',
        'Islamic' => 'Islamic',
        'Adat' => 'Adat / Tradisional',
        'Nature' => 'Nature / Botanical',
        'Minimalist' => 'Minimalist',
        'Luxury' => 'Luxury / Premium',
        'Romantic' => 'Romantic / Floral',
        'Dark' => 'Dark Mode',
    ];

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    // ==================
    // ACCESSORS
    // ==================

    /**
     * Get a protected URL for the thumbnail (hides storage path, adds watermark).
     */
    public function getProtectedThumbnailAttribute(): ?string
    {
        if (! $this->thumbnail_url) {
            return null;
        }

        // Storage-based uploads: strip /storage/ prefix and use img_url()
        if (str_starts_with($this->thumbnail_url, '/storage/')) {
            return img_url(str_replace('/storage/', '', $this->thumbnail_url));
        }

        // Static public assets (e.g. /images/themes/...) — serve as-is
        return asset($this->thumbnail_url);
    }

    // ==================
    // METHODS
    // ==================

    /**
     * Get theme as configuration array
     */
    public function toConfigArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description ?? '',
            'thumbnail_url' => $this->thumbnail_url,
            'colors' => [
                'primary' => $this->primary_color,
                'secondary' => $this->secondary_color,
                'accent' => $this->accent_color,
                'text' => $this->text_color,
                'heading' => $this->heading_color,
                'background' => $this->background_color,
            ],
            'fonts' => [
                'heading' => $this->heading_font,
                'body' => $this->body_font,
                'accent' => $this->accent_font,
            ],
            'layout' => [
                'container_max_width' => $this->container_max_width,
                'border_radius' => $this->border_radius,
                'heading_size' => $this->heading_size,
            ],
        ];
    }

    /**
     * Duplicate this theme with a new name/slug.
     */
    public function duplicate(): self
    {
        $newTheme = $this->replicate();
        $newTheme->name = $this->name . ' (Copy)';
        $newTheme->slug = $this->slug . '-copy-' . time();
        $newTheme->thumbnail_url = $this->thumbnail_url;
        $newTheme->save();

        return $newTheme;
    }

    /**
     * Export theme data as a JSON-safe array.
     */
    public function toExportArray(): array
    {
        return collect($this->toArray())
            ->except(['id', 'created_at', 'updated_at'])
            ->toArray();
    }

    /**
     * Create a theme from an imported array.
     */
    public static function fromImportArray(array $data): self
    {
        // Remove fields that should not be imported
        unset($data['id'], $data['created_at'], $data['updated_at']);

        // Ensure unique slug
        $originalSlug = $data['slug'] ?? 'imported-theme';
        $slug = $originalSlug;
        $counter = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }
        $data['slug'] = $slug;

        return static::create($data);
    }

    /**
     * Check if theme is locked (premium and not owned by user)
     */
    public function isLocked(?int $userId = null): bool
    {
        if (! $this->is_premium) {
            return false;
        }

        // Add logic here for premium user checks if needed
        return false;
    }
}
