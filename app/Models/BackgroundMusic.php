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

    /**
     * Shared audio upload validation rules allowing legitimate audio & YouTube-converted containers.
     */
    public static function audioValidationRules(bool $required = true): array
    {
        return [
            $required ? 'required' : 'nullable',
            'file',
            'extensions:mp3,wav,ogg,m4a,aac,webm',
            'max:10240',
            function (string $attribute, mixed $value, \Closure $fail): void {
                if (! $value || ! method_exists($value, 'getRealPath')) {
                    return;
                }

                $realPath = $value->getRealPath();
                $detectedMime = '';
                if (is_string($realPath) && is_file($realPath) && function_exists('mime_content_type')) {
                    $detectedMime = strtolower((string) (@mime_content_type($realPath) ?: ''));
                }
                if ($detectedMime === '' && method_exists($value, 'getMimeType')) {
                    $detectedMime = strtolower((string) ($value->getMimeType() ?? ''));
                }

                $blockedExact = [
                    'application/x-php',
                    'application/x-httpd-php',
                    'application/javascript',
                    'application/x-javascript',
                    'application/x-executable',
                    'application/x-dosexec',
                    'application/x-elf',
                    'application/x-sh',
                    'application/x-shellscript',
                    'application/zip',
                    'application/pdf',
                    'application/xml',
                    'application/json',
                ];

                if (str_starts_with($detectedMime, 'text/')
                    || str_starts_with($detectedMime, 'image/svg')
                    || in_array($detectedMime, $blockedExact, true)
                ) {
                    $fail('Format file harus berupa audio (MP3, WAV, OGG, M4A, AAC, atau WEBM).');

                    return;
                }

                $allowedExact = [
                    'video/mp4',
                    'video/webm',
                    'video/x-matroska',
                    'video/ogg',
                    'application/mp4',
                    'application/ogg',
                    'application/octet-stream',
                ];

                if ($detectedMime === ''
                    || str_starts_with($detectedMime, 'audio/')
                    || in_array($detectedMime, $allowedExact, true)
                ) {
                    return;
                }

                // Fallback magic-byte check (e.g. MP3 with ID3v2 cover or MOV/MP4 container)
                $header = is_string($realPath) && is_file($realPath)
                    ? (string) (@file_get_contents($realPath, false, null, 0, 16) ?: '')
                    : '';

                $isAudioMagic = str_starts_with($header, 'ID3')
                    || str_starts_with($header, 'RIFF')
                    || str_starts_with($header, 'OggS')
                    || str_starts_with($header, 'fLaC')
                    || str_starts_with($header, "\x1A\x45\xDF\xA3")
                    || (strlen($header) >= 8 && substr($header, 4, 4) === 'ftyp')
                    || (strlen($header) >= 2 && ord($header[0]) === 0xFF && (ord($header[1]) & 0xE0) === 0xE0);

                if (! $isAudioMagic) {
                    $fail('Format file harus berupa audio (MP3, WAV, OGG, M4A, AAC, atau WEBM).');
                }
            },
        ];
    }

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
