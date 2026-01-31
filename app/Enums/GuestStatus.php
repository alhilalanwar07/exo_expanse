<?php

namespace App\Enums;

enum GuestStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case DECLINED = 'declined';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Menunggu',
            self::CONFIRMED => 'Hadir',
            self::DECLINED => 'Tidak Hadir',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::CONFIRMED => 'success',
            self::DECLINED => 'danger',
        };
    }
}
