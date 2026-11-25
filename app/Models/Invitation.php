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

    public const TIMEZONES = [
        'WIB' => 'Asia/Jakarta',
        'WITA' => 'Asia/Makassar',
        'WIT' => 'Asia/Jayapura',
    ];

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
        'music_id',
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

    public function backgroundMusic(): BelongsTo
    {
        return $this->belongsTo(BackgroundMusic::class, 'music_id');
    }

    public function getBackgroundMusicAttribute($value): ?string
    {
        // If it has a related music_id from the centralized library,
        // resolve through BackgroundMusic::$url (/img/{token} for storage files).
        if ($this->music_id) {
            $music = $this->getRelationValue('backgroundMusic');
            if ($music) {
                return $music->url;
            }
        }

        if (is_string($value) && trim($value) !== '') {
            $trimmed = trim($value);

            if (str_starts_with($trimmed, '/storage/') || str_starts_with($trimmed, 'storage/')) {
                return img_url(preg_replace('#^/?storage/#', '', $trimmed));
            }

            if (preg_match('#^https?://[^/]+/storage/(.+)$#i', $trimmed, $m)) {
                return img_url($m[1]);
            }
        }

        return $value;
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

    public function getEventDateAttribute(): ?\Carbon\Carbon
    {
        $styles = $this->custom_styles ?? [];
        if (!empty($styles['event_date'])) {
            try {
                return \Carbon\Carbon::parse($styles['event_date']);
            } catch (\Throwable $e) {}
        }

        $eventType = $styles['event_type'] ?? 'both';
        if ($eventType === 'resepsi_only') {
            return $this->resepsi_date ? \Carbon\Carbon::parse($this->resepsi_date) : null;
        }

        if ($eventType === 'akad_only') {
            return $this->akad_date ? \Carbon\Carbon::parse($this->akad_date) : null;
        }

        return $this->akad_date 
            ? \Carbon\Carbon::parse($this->akad_date) 
            : ($this->resepsi_date ? \Carbon\Carbon::parse($this->resepsi_date) : null);
    }

    public function getEventTypeAttribute(): string
    {
        return $this->custom_styles['event_type'] ?? 'both';
    }

    public function getTimezoneAttribute(): string
    {
        $tz = strtoupper(trim((string) ($this->custom_styles['timezone'] ?? '')));
        if (array_key_exists($tz, self::TIMEZONES)) {
            return $tz;
        }

        if ($this->relationLoaded('theme') || $this->theme_id) {
            $slug = (string) ($this->theme?->slug ?? '');
            if (in_array($slug, ['adat-bone', 'wedding-walasuji', 'agung-bali', 'special-03'], true)) {
                return 'WITA';
            }
        }

        return 'WIB';
    }

    public function ianaTimezone(): string
    {
        return self::TIMEZONES[$this->timezone] ?? 'Asia/Jakarta';
    }

    public function resolveTimeHm(?string $timeCol, $dateCol, string $fallback = '08:00'): string
    {
        $t = trim((string) $timeCol);
        if ($t !== '') {
            if (preg_match('/^(\d{1,2})[:.](\d{2})(?::\d{2})?$/', $t, $m)) {
                return sprintf('%02d:%02d', (int) $m[1], (int) $m[2]);
            }

            return $t;
        }

        if ($dateCol) {
            try {
                $hm = \Carbon\Carbon::parse($dateCol)->format('H:i');
                if ($hm !== '00:00') {
                    return $hm;
                }
            } catch (\Throwable $e) {
            }
        }

        return $fallback;
    }

    public function formatCustomTime(?string $rawTime, string $fallback = '08:00', bool $withSelesai = true, string $separator = ':'): string
    {
        $t = trim((string) $rawTime);
        $tz = $this->timezone;

        if ($t === '') {
            $hm = str_replace(':', $separator, $fallback);

            return $hm.' '.$tz.($withSelesai ? ' - Selesai' : '');
        }

        if (preg_match('/^(\d{1,2})[:.](\d{2})(?::\d{2})?$/', $t, $m)) {
            $hm = sprintf('%02d%s%02d', (int) $m[1], $separator, (int) $m[2]);

            return $hm.' '.$tz.($withSelesai ? ' - Selesai' : '');
        }

        $hasZone = (bool) preg_match('/\b(WIB|WITA|WIT)\b/i', $t);
        if ($hasZone) {
            $formatted = preg_replace('/\b(WIB|WITA|WIT)\b/i', $tz, $t) ?? $t;
        } elseif (preg_match('/\s*-\s*selesai$/i', $t)) {
            $formatted = preg_replace('/(\s*-\s*selesai)$/i', ' '.$tz.'$1', $t) ?? ($t.' '.$tz);
        } else {
            $formatted = $t.' '.$tz;
        }

        $hasEndOrSelesai = str_contains(strtolower($formatted), 'selesai') || (bool) preg_match('/-\s*\d{1,2}[:.]\d{2}/', $formatted);
        if ($withSelesai && ! $hasEndOrSelesai) {
            $formatted .= ' - Selesai';
        }

        return $formatted;
    }

    public function formatEventTime(string $event = 'akad', bool $withSelesai = true, string $separator = ':'): string
    {
        if ($event === 'resepsi') {
            $fallback = '11:00';
            $raw = $this->resolveTimeHm($this->resepsi_time, $this->resepsi_date, $fallback);
        } else {
            $fallback = '08:00';
            $raw = $this->resolveTimeHm($this->akad_time, $this->akad_date, $fallback);
        }

        return $this->formatCustomTime($raw, $fallback, $withSelesai, $separator);
    }

    public function eventDateTime(string $event = 'akad'): ?\Carbon\Carbon
    {
        $isResepsi = $event === 'resepsi';
        $dateVal = $isResepsi ? $this->resepsi_date : $this->akad_date;
        if (! $dateVal) {
            return null;
        }

        $fallback = $isResepsi ? '11:00' : '08:00';
        $hm = $this->resolveTimeHm(
            $isResepsi ? $this->resepsi_time : $this->akad_time,
            $dateVal,
            $fallback
        );

        try {
            $ymd = \Carbon\Carbon::parse($dateVal)->format('Y-m-d');
            $dt = \Carbon\Carbon::createFromFormat('Y-m-d', $ymd, $this->ianaTimezone());
            if (preg_match('/^(\d{1,2})[:.](\d{2})/', $hm, $m)) {
                $dt->setTime((int) $m[1], (int) $m[2], 0);
            } else {
                [$fh, $fm] = explode(':', $fallback);
                $dt->setTime((int) $fh, (int) $fm, 0);
            }

            return $dt;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function primaryEventDateTime(): \Carbon\Carbon
    {
        $eventType = $this->event_type;
        $dt = $eventType === 'resepsi_only'
            ? ($this->eventDateTime('resepsi') ?? $this->eventDateTime('akad'))
            : ($this->eventDateTime('akad') ?? $this->eventDateTime('resepsi'));

        if ($dt) {
            return $dt;
        }

        $styles = $this->custom_styles ?? [];
        if (! empty($styles['event_date'])) {
            try {
                $ymd = \Carbon\Carbon::parse($styles['event_date'])->format('Y-m-d');
                $fallback = $eventType === 'resepsi_only' ? '11:00' : '08:00';
                $hm = $this->resolveTimeHm(
                    $eventType === 'resepsi_only' ? $this->resepsi_time : ($this->akad_time ?: $this->resepsi_time),
                    null,
                    $fallback
                );
                $dt = \Carbon\Carbon::createFromFormat('Y-m-d', $ymd, $this->ianaTimezone());
                if (preg_match('/^(\d{1,2})[:.](\d{2})/', $hm, $m)) {
                    $dt->setTime((int) $m[1], (int) $m[2], 0);
                } else {
                    $dt->setTime(8, 0, 0);
                }

                return $dt;
            } catch (\Throwable $e) {
            }
        }

        return \Carbon\Carbon::now($this->ianaTimezone())->addDays(30)->setTime(8, 0, 0);
    }

    public function countdownTimestamp(): int
    {
        return $this->primaryEventDateTime()->timestamp;
    }

    public function countdownIso(): string
    {
        return $this->primaryEventDateTime()->toIso8601String();
    }

    public function googleCalendarDates(string $event = 'primary', int $durationHours = 3): string
    {
        $start = ($event === 'primary'
            ? $this->primaryEventDateTime()
            : ($this->eventDateTime($event) ?? $this->primaryEventDateTime())
        )->copy()->utc();
        $end = $start->copy()->addHours($durationHours);

        return $start->format('Ymd\THis\Z').'/'.$end->format('Ymd\THis\Z');
    }
}
