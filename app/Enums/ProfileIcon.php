<?php

namespace App\Enums;

/**
 * Ikon kartu di halaman Profil (statistik dan peran masjid).
 */
enum ProfileIcon: string
{
    case Area = 'area';
    case Building = 'building';
    case People = 'people';
    case Check = 'check';
    case Book = 'book';
    case Quran = 'quran';
    case Heart = 'heart';

    public function glyph(): string
    {
        return match ($this) {
            self::Area => '⌗',
            self::Building => '⌂',
            self::People => '♧',
            self::Check => '✓',
            self::Book => '▤',
            self::Quran => '▣',
            self::Heart => '♡',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Area => 'Luas / area',
            self::Building => 'Bangunan',
            self::People => 'Jamaah',
            self::Check => 'Centang',
            self::Book => 'Kajian',
            self::Quran => "Al-Qur'an",
            self::Heart => 'Kepedulian',
        };
    }
}
