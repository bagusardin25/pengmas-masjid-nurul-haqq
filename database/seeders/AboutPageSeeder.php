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
            'intro_description' => 'Menjadi pusat ibadah, pendidikan, dan pemberdayaan umat menuju masyarakat yang berakhlak mulia dan sejahtera di tengah keharmonisan lingkungan Griya Taman Asri.',
            'history_title' => 'Sejarah',
            'history_content' => "Masjid Jami Nurul Haqq berada di bawah naungan Yayasan Nurul Haqq dan melayani jamaah di lingkungan Griya Taman Asri Blok FF 08–09, RT 32 RW 08, Desa Tawangsari, Kecamatan Taman, Kabupaten Sidoarjo. Berdiri di atas tanah wakaf seluas 270 m² dengan bangunan dua lantai seluas 540 m² berdaya tampung hingga 600 jamaah, masjid ini menjadi pusat peribadatan sekaligus pemersatu warga.\n\nPerintisan Masjid Nurul Haqq dimulai pada tahun 2008 melalui inisiatif dan gotong royong warga Griya Taman Asri yang membutuhkan sarana ibadah bersama. Pembangunan fisik masjid mulai dilaksanakan pada 13 Juni 2010 dan terus dikembangkan secara bertahap—termasuk perluasan ruang ibadah pada 2012—hingga resmi diresmikan pada 15 April 2018 sebagaimana tercatat pada prasasti masjid.\n\nSeiring pertumbuhan jamaah, fungsi masjid diperluas melampaui sholat berjamaah lima waktu dan sholat Jumat. Pada tahun 2015, Yayasan dan Takmir mendirikan Taman Pendidikan Al-Qur'an (TPQ) Nurul Haqq dengan metode Tartil untuk membina bacaan serta akhlak anak-anak dan remaja, berdampingan dengan majelis taklim serta kajian kitab rutin setiap pekan.\n\nMemasuki tahun 2020, layanan sosial dan pengelolaan dana umat melalui ZISWAF semakin ditata secara terstruktur di bawah tiga bidang kepengurusan takmir—Idaroh, Imaroh, dan Riayah. Setiap penghimpunan infak, sedekah, zakat, maupun wakaf dilaporkan secara rutin kepada jamaah sebagai wujud transparansi, amanah, dan keberlanjutan kemakmuran masjid.",
        ])->save();

        $stats = [
            ['value' => '270 m²', 'label' => 'Luas tanah', 'icon' => ProfileIcon::Area],
            ['value' => '540 m²', 'label' => 'Luas bangunan', 'icon' => ProfileIcon::Building],
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
