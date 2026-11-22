<?php

namespace App\Enums;

/**
 * Kategori laporan keuangan masjid (rapat takmir 30 Oktober 2026).
 */
enum ReportCategory: string
{
    case Weekly = 'mingguan';
    case Event = 'kegiatan';
    case Construction = 'pembangunan';

    public function label(): string
    {
        return match ($this) {
            self::Weekly => 'Laporan rutin mingguan',
            self::Event => 'Laporan kegiatan',
            self::Construction => 'Pembangunan masjid',
        };
    }

    public function shortLabel(): string
    {
        return match ($this) {
            self::Weekly => 'Mingguan',
            self::Event => 'Kegiatan',
            self::Construction => 'Pembangunan',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Weekly => 'Pemasukan dan pengeluaran kas masjid setiap pekan.',
            self::Event => 'Keuangan kegiatan seperti Ramadan, Maulid Nabi, dan layanan ambulans.',
            self::Construction => 'Perkembangan penggunaan dana pembangunan masjid.',
        };
    }
}
