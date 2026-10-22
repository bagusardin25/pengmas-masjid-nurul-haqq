<?php

namespace Database\Seeders;

use App\Models\HomePage;
use Illuminate\Database\Seeder;

class HomePageSeeder extends Seeder
{
    public function run(): void
    {
        (HomePage::current() ?? new HomePage)->fill([
            'hero_eyebrow' => 'MASJID JAMI · YAYASAN NURUL HAQQ',
            'hero_title' => "Masjid\nNurul Haqq",
            'hero_description' => 'Menjadi pusat ibadah, pendidikan, dan pemberdayaan umat menuju masyarakat yang berakhlak mulia dan sejahtera di tengah keharmonisan lingkungan Griya Taman Asri.',
            'hero_arabic_text' => 'إِنَّمَا يَعْمُرُ مَسَاجِدَ اللَّهِ مَنْ آمَنَ بِاللَّهِ وَالْيَوْمِ الْآخِرِ',
            'hero_translation' => '“Sesungguhnya yang memakmurkan masjid-masjid Allah hanyalah orang yang beriman kepada Allah dan hari akhir…”',
            'hero_citation' => 'QS. AT-TAUBAH: 18',
            'hero_primary_label' => 'Jadwal Kegiatan',
            'hero_primary_url' => '/jadwal-kegiatan',
            'hero_secondary_label' => 'Hubungi Pengurus',
            'hero_secondary_url' => '/kontak',
        ])->save();
    }
}
