<?php

namespace Database\Seeders;

use App\Enums\ProfileIcon;
use App\Models\AboutPage;
use App\Models\AboutRole;
use App\Models\AboutStat;
use App\Models\Facility;
use App\Models\OrganizationMember;
use Illuminate\Database\Seeder;

class AboutPageSeeder extends Seeder
{
    public function run(): void
    {
        (AboutPage::current() ?? new AboutPage)->fill([
            'hero_title' => 'Rumah ibadah warga Griya Taman Asri',
            'hero_description' => 'Mengenal Masjid Jami Nurul Haqq, di bawah naungan Yayasan Nurul Haqq, yang melayani jamaah lingkungan Griya Taman Asri, Tawangsari.',
            'intro_title' => 'Pusat ibadah, pendidikan, dan pemberdayaan umat',
            'intro_description' => "Menjadi pusat ibadah, pendidikan, dan pemberdayaan umat menuju masyarakat yang berakhlak mulia dan sejahtera di tengah keharmonisan lingkungan Griya Taman Asri.",
            'history_title' => 'Sejarah',
            'history_content' => "Masjid Jami Nurul Haqq berada di bawah naungan Yayasan Nurul Haqq dan melayani jamaah di lingkungan Griya Taman Asri, Tawangsari. Selain sholat berjamaah, masjid menjadi tempat pengajian, pendidikan Al-Qur'an anak, dan pengelolaan dana umat.\n\nMasjid Nurul Haqq dirintis pada 2008 melalui gotong royong warga Griya Taman Asri. Ruang ibadah dikembangkan pada 2012, disusul program TPQ pada 2015 dan layanan sosial jamaah pada 2020. Catatan ini merupakan contoh ringkas dan dapat dilengkapi dengan data resmi masjid.",
        ])->save();

        $stats = [
            ['value' => '270 m²', 'label' => 'Luas tanah', 'icon' => ProfileIcon::Area],
            ['value' => '150 m²', 'label' => 'Luas bangunan', 'icon' => ProfileIcon::Building],
            ['value' => '600', 'label' => 'Daya tampung jamaah', 'icon' => ProfileIcon::People],
            ['value' => 'Wakaf', 'label' => 'Status tanah', 'icon' => ProfileIcon::Check],
        ];

        $sortOrder = 1;

        foreach ($stats as $stat) {
            AboutStat::query()->updateOrCreate(
                ['label' => $stat['label']],
                ['value' => $stat['value'], 'icon' => $stat['icon'], 'sort_order' => $sortOrder++],
            );
        }

        $roles = [
            [
                'title' => 'Ibadah & pengajian',
                'description' => 'Sholat berjamaah dan pengajian menjadi bagian dari kegiatan masjid untuk jamaah lingkungan Griya Taman Asri.',
                'icon' => ProfileIcon::Book,
            ],
            [
                'title' => "Pendidikan Al-Qur'an",
                'description' => 'Program TPQ mendampingi tumbuh kembang spiritual anak-anak dan remaja di lingkungan Tawangsari.',
                'icon' => ProfileIcon::Quran,
            ],
            [
                'title' => 'Pengelolaan dana umat',
                'description' => 'Zakat, infak, sedekah, dan wakaf dikelola Yayasan Nurul Haqq secara amanah dan dilaporkan setiap bulan.',
                'icon' => ProfileIcon::Heart,
            ],
        ];

        $sortOrder = 1;

        foreach ($roles as $role) {
            AboutRole::query()->updateOrCreate(
                ['title' => $role['title']],
                ['description' => $role['description'], 'icon' => $role['icon'], 'sort_order' => $sortOrder++],
            );
        }

        $facilities = [
            'Ruang sholat',
            'Tempat wudhu',
            'Perpustakaan',
            'Akses internet',
            'Area parkir',
            'Toilet bersih',
        ];

        $sortOrder = 1;

        foreach ($facilities as $name) {
            Facility::query()->updateOrCreate(
                ['name' => $name],
                ['sort_order' => $sortOrder++],
            );
        }

        // Pengurus harian Takmir Masjid Nurul Haqq periode 2025–2030.
        // Sumber: "SUSUNAN PENGURUS TA_MIR MNH 2025 - 2030.pdf". Seksi-seksi tidak dimasukkan
        // agar daftar di halaman Profil tetap ringkas.
        $members = [
            ['position' => 'Ketua', 'name' => 'H. Sholihul Ibadi, S.T', 'note' => null],
            ['position' => 'Wakil Ketua I', 'name' => 'H. Iswanto, S.Pd', 'note' => 'Bidang Idaroh'],
            ['position' => 'Wakil Ketua II', 'name' => 'Ali Tamam', 'note' => 'Bidang Imaroh'],
            ['position' => 'Wakil Ketua III', 'name' => 'Noegroho Wisnoe Widodo, S.T', 'note' => 'Bidang Riayah'],
            ['position' => 'Sekretaris', 'name' => 'Gatot Bambang', 'note' => null],
            ['position' => 'Wakil Sekretaris I', 'name' => 'H. Nur Syarif Hidayat, S.T', 'note' => 'Bidang Idaroh'],
            ['position' => 'Wakil Sekretaris II', 'name' => 'Samsul Hadi', 'note' => 'Bidang Imaroh'],
            // Di dokumen tertulis "Wakil Sekretaris I (Bidang Riayah)"; urutannya menunjukkan III.
            ['position' => 'Wakil Sekretaris III', 'name' => 'Jaka Arya Tri Wahyono', 'note' => 'Bidang Riayah'],
            ['position' => 'Bendahara', 'name' => 'Budiyono', 'note' => null],
            ['position' => 'Wakil Bendahara', 'name' => 'H. Yopi Yacob Isaac', 'note' => null],
        ];

        $sortOrder = 1;

        foreach ($members as $member) {
            OrganizationMember::query()->updateOrCreate(
                ['position' => $member['position'], 'name' => $member['name']],
                ['note' => $member['note'], 'sort_order' => $sortOrder++, 'is_active' => true],
            );
        }
    }
}
