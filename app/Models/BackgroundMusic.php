<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BackgroundMusic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'artist',
        'file_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getStoragePathAttribute(): ?string
    {
        $raw = trim((string) $this->file_path);
        if ($raw === '' || str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://')) {
            return null;
        }

        $normalized = ltrim($raw, '/');
        if (str_starts_with($normalized, 'storage/')) {
            return substr($normalized, strlen('storage/'));
        }

        if (Storage::disk('public')->exists($normalized)) {
            return $normalized;
        }

        return null;
    }

    public function getUrlAttribute(): string
    {
        $raw = trim((string) $this->file_path);
        if ($raw === '') {
            return '';
        }

        if (str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://')) {
            return $raw;
        }

        $storagePath = $this->storage_path;
        if ($storagePath !== null) {
            return img_url($storagePath);
        }

        return asset($raw);
    }
}
