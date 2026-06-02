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
        'sections_config',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_premium' => 'boolean',
        'sections_config' => 'array',
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
     * Get enabled sections sorted by order.
     */
    public function getEnabledSections(): array
    {
        $config = $this->sections_config ?? self::defaultSectionsConfig();
        $sections = $config['sections'] ?? [];

        return collect($sections)
            ->where('enabled', true)
            ->sortBy('order')
            ->values()
            ->toArray();
    }

    /**
     * Get config for a specific section.
     */
    public function getSectionConfig(string $sectionId): array
    {
        $config = $this->sections_config ?? self::defaultSectionsConfig();
        $sections = $config['sections'] ?? [];

        $section = collect($sections)->firstWhere('id', $sectionId);

        return $section['config'] ?? [];
    }

    /**
     * Get frame images configuration.
     */
    public function getFrameConfig(): array
    {
        $config = $this->sections_config ?? self::defaultSectionsConfig();

        return $config['frame'] ?? [
            'tl' => '/assets/themes/adat-bone/tl.webp',
            'tr' => '/assets/themes/adat-bone/tr.webp',
            'bl' => '/assets/themes/adat-bone/bl.webp',
            'br' => '/assets/themes/adat-bone/br.webp',
            'left' => '/assets/themes/adat-bone/left.webp',
            'right' => '/assets/themes/adat-bone/right.webp',
        ];
    }

    /**
     * Get nav bar configuration.
     */
    public function getNavConfig(): array
    {
        $config = $this->sections_config ?? self::defaultSectionsConfig();

        return $config['nav'] ?? [
            'bg_color' => 'rgba(118, 19, 50, 0.95)',
            'active_color' => '#d4b051',
            'inactive_color' => '#ffffff',
        ];
    }

    /**
     * Default sections configuration (based on adat-bone).
     */
    public static function defaultSectionsConfig(): array
    {
        return [
            'sections' => [
                ['id' => 'cover', 'enabled' => true, 'order' => 0, 'config' => [
                    'overlay_gradient' => 'linear-gradient(to bottom, rgba(93,7,31,0.75) 0%, rgba(93,7,31,0.45) 35%, rgba(93,7,31,0.35) 55%, rgba(93,7,31,0.92) 85%, rgba(93,7,31,0.98) 100%)',
                    'title_text' => 'The Wedding Of',
                    'button_text' => 'Buka Undangan',
                ]],
                ['id' => 'opening', 'enabled' => true, 'order' => 1, 'config' => [
                    'overlay_gradient' => 'linear-gradient(to bottom, rgba(93,7,31,0.6), rgba(93,7,31,0.9))',
                ]],
                ['id' => 'couple', 'enabled' => true, 'order' => 2, 'config' => []],
                ['id' => 'quote', 'enabled' => true, 'order' => 3, 'config' => [
                    'quote_title' => 'QS. Ar-Rum 21',
                    'quote_text' => 'Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang. Sungguh, pada yang demikian itu benar-benar terdapat tanda-tanda (kebesaran Allah) bagi kaum yang berpikir.',
                ]],
                ['id' => 'lovestory', 'enabled' => true, 'order' => 4, 'config' => []],
                ['id' => 'events', 'enabled' => true, 'order' => 5, 'config' => []],
                ['id' => 'maps', 'enabled' => true, 'order' => 6, 'config' => []],
                ['id' => 'gallery', 'enabled' => true, 'order' => 7, 'config' => []],
                ['id' => 'gift', 'enabled' => true, 'order' => 8, 'config' => []],
                ['id' => 'rsvp', 'enabled' => true, 'order' => 9, 'config' => []],
                ['id' => 'closing', 'enabled' => true, 'order' => 10, 'config' => []],
            ],
            'frame' => [
                'tl' => '/assets/themes/adat-bone/tl.webp',
                'tr' => '/assets/themes/adat-bone/tr.webp',
                'bl' => '/assets/themes/adat-bone/bl.webp',
                'br' => '/assets/themes/adat-bone/br.webp',
                'left' => '/assets/themes/adat-bone/left.webp',
                'right' => '/assets/themes/adat-bone/right.webp',
            ],
            'nav' => [
                'bg_color' => 'rgba(118, 19, 50, 0.95)',
                'active_color' => '#d4b051',
                'inactive_color' => '#ffffff',
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
