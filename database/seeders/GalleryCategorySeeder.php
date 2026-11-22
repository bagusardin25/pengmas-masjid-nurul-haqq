<?php

namespace Database\Seeders;

use App\Models\GalleryCategory;
use Illuminate\Database\Seeder;

class GalleryCategorySeeder extends Seeder
{
    /**
     * Kategori demo lama. Dihapus bila tidak lagi dipakai album atau video mana pun.
     */
    private const DEMO_SLUGS = ['kajian-rutin', 'sholat-berjamaah', 'kegiatan-tpq', 'pengajian-ahad-malam'];

    public function run(): void
    {
        $categories = [
            'kegiatan-masjid' => 'Kegiatan masjid',
            'kajian-pengajian' => 'Kajian & pengajian',
            'hari-besar-islam' => 'Hari besar Islam',
            'tpq-madrasah' => 'TPQ & Madrasah Diniyah',
            'masjid-fasilitas' => 'Masjid & fasilitas',
        ];

        $sortOrder = 1;

        foreach ($categories as $slug => $name) {
            GalleryCategory::query()->updateOrCreate(
                ['slug' => $slug],
                ['name' => $name, 'sort_order' => $sortOrder++],
            );
        }

        self::removeUnusedDemoCategories();
    }

    /**
     * Hapus kategori demo yang sudah tidak dipakai. Dipanggil lagi oleh GalleryPageSeeder setelah
     * album demo dihapus, karena saat seeder ini jalan album demo lama masih memakai kategorinya.
     */
    public static function removeUnusedDemoCategories(): void
    {
        GalleryCategory::query()
            ->whereIn('slug', self::DEMO_SLUGS)
            ->whereDoesntHave('albums')
            ->whereDoesntHave('videos')
            ->delete();
    }
}
