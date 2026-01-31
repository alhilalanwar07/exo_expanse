<?php

namespace App\Enums;

enum InvitationType: string
{
    case WEDDING = 'wedding';
    case BIRTHDAY = 'birthday';
    case AQIQAH = 'aqiqah';
    case KHITAN = 'khitan';
    case GRADUATION = 'graduation';
    case CORPORATE = 'corporate';
    case NATAL = 'natal';
    case HALAL_BIHALAL = 'halal_bihalal';
    case TAHLILAN = 'tahlilan';
    case BAPTISAN = 'baptisan';
    case ONLINE_GREETING = 'online_greeting';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::WEDDING => 'Pernikahan',
            self::BIRTHDAY => 'Ulang Tahun',
            self::AQIQAH => 'Aqiqah',
            self::KHITAN => 'Khitanan',
            self::GRADUATION => 'Wisuda / Ujian',
            self::CORPORATE => 'Acara Perusahaan',
            self::NATAL => 'Natal',
            self::HALAL_BIHALAL => 'Halal Bihalal / Bukber',
            self::TAHLILAN => 'Tahlilan',
            self::BAPTISAN => 'Baptisan',
            self::ONLINE_GREETING => 'Ucapan Online',
            self::OTHER => 'Acara Custom',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::WEDDING => 'Buat undangan pernikahan untuk disebarkan ke seluruh tamu undangan',
            self::BIRTHDAY => 'Undangan untuk acara ulang tahun spesial',
            self::AQIQAH => 'Cocok untuk semua acara anak',
            self::KHITAN => 'Acara Khitanan atau sunatan untuk umat muslim',
            self::GRADUATION => 'Berikan hadiah undangan untuk teman yang telah Wisuda atau Ujian',
            self::CORPORATE => 'Acara perusahaan, meeting, atau gathering',
            self::NATAL => 'Buat acara natal, acara keluarga, acara gereja',
            self::HALAL_BIHALAL => 'Untuk semua acara nuansa ramadhan',
            self::TAHLILAN => 'Acara untuk mengenang dan mendoakan seseorang yang telah meninggal',
            self::BAPTISAN => 'Ritual untuk menyucikan diri dan mengakui keimanan kepada Yesus',
            self::ONLINE_GREETING => 'Berikan ucapan online untuk hari spesial',
            self::OTHER => 'Buat undangan untuk acara apa saja bisa custom semuanya',
        };
    }

    public function emoji(): string
    {
        return match ($this) {
            self::WEDDING => '💒',
            self::BIRTHDAY => '🎂',
            self::AQIQAH => '👶',
            self::KHITAN => '✨',
            self::GRADUATION => '🎓',
            self::CORPORATE => '🏢',
            self::NATAL => '🎄',
            self::HALAL_BIHALAL => '🌙',
            self::TAHLILAN => '🕯️',
            self::BAPTISAN => '✝️',
            self::ONLINE_GREETING => '💌',
            self::OTHER => '📝',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::WEDDING => 'heroicon-o-heart',
            self::BIRTHDAY => 'heroicon-o-cake',
            self::AQIQAH => 'heroicon-o-gift',
            self::KHITAN => 'heroicon-o-sparkles',
            self::GRADUATION => 'heroicon-o-academic-cap',
            self::CORPORATE => 'heroicon-o-building-office',
            self::NATAL => 'heroicon-o-gift-top',
            self::HALAL_BIHALAL => 'heroicon-o-moon',
            self::TAHLILAN => 'heroicon-o-fire',
            self::BAPTISAN => 'heroicon-o-hand-raised',
            self::ONLINE_GREETING => 'heroicon-o-envelope',
            self::OTHER => 'heroicon-o-document-text',
        };
    }

    /**
     * Check if this invitation type is currently available
     * Only Wedding is available for now, others coming soon
     */
    public function isAvailable(): bool
    {
        return match ($this) {
            self::WEDDING => true,
            default => false,
        };
    }

    /**
     * Get gradient colors for the card
     */
    public function gradient(): string
    {
        return match ($this) {
            self::WEDDING => 'from-rose-500 to-pink-600',
            self::BIRTHDAY => 'from-amber-500 to-orange-600',
            self::AQIQAH => 'from-emerald-500 to-teal-600',
            self::KHITAN => 'from-blue-500 to-indigo-600',
            self::GRADUATION => 'from-purple-500 to-violet-600',
            self::CORPORATE => 'from-slate-500 to-gray-600',
            self::NATAL => 'from-red-500 to-rose-600',
            self::HALAL_BIHALAL => 'from-green-500 to-emerald-600',
            self::TAHLILAN => 'from-stone-500 to-neutral-600',
            self::BAPTISAN => 'from-sky-500 to-cyan-600',
            self::ONLINE_GREETING => 'from-pink-500 to-fuchsia-600',
            self::OTHER => 'from-gray-500 to-slate-600',
        };
    }

    /**
     * Get all available invitation types
     */
    public static function available(): array
    {
        return array_filter(self::cases(), fn($type) => $type->isAvailable());
    }

    /**
     * Get all invitation types grouped by availability
     */
    public static function grouped(): array
    {
        $available = [];
        $comingSoon = [];
        
        foreach (self::cases() as $type) {
            if ($type->isAvailable()) {
                $available[] = $type;
            } else {
                $comingSoon[] = $type;
            }
        }
        
        return [
            'available' => $available,
            'coming_soon' => $comingSoon,
        ];
    }
}
