<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvitationPhoto extends Model
{
    protected $fillable = [
        'invitation_id',
        'path',
        'caption',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function invitation(): BelongsTo
    {
        return $this->belongsTo(Invitation::class);
    }

    /**
     * Get the full URL for the photo
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }
}
