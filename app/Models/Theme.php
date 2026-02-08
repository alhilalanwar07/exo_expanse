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
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_premium' => 'boolean',
    ];

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
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
