<?php

namespace Database\Seeders;

use App\Enums\ProfileIcon;
use App\Models\TpqDocumentation;
use App\Models\TpqFaq;
use App\Models\TpqPage;
use App\Models\TpqPillar;
use App\Services\MediaUploader;
use Illuminate\Database\Seeder;

/**
 * Data asli TPQ Nurul Haqq.
 *
 * Sumber: "Notulensi Zoom 18 Oktober 2026.pdf" (keterangan Bu Ani, Ketua TPQ) dan papan
 * visi-misi TPQ (public/images/beranda/tpq-papan-visi-misi-1200.jpg). Rentang usia dan
 * nominal SPP belum ada di dokumen, jadi dibiarkan kosong dan tidak ditampilkan.
 */
class TpqPageSeeder extends Seeder
{
    public function run(): void
    {
        (TpqPage::current() ?? new TpqPage)->fill([
            'hero_title' => "Tumbuh bersama Al-Qur'an sejak dini",
            'hero_description' => "TPQ Nurul Haqq hadir untuk membentuk generasi berkarakter Qur'ani: santri belajar membaca Al-Qur'an dengan tartil, menulis Al-Qur'an dengan benar, serta menanamkan dasar keimanan dan ketakwaan kepada Allah dan Rasul-Nya.",
            'summary' => "TPQ Nurul Haqq membentuk generasi berkarakter Qur'ani melalui pembelajaran Al-Qur'an metode Tartil, dari jenjang Tartil 1–6 hingga Marhalah Ula, Wustha, Akhir, dan Diniyah.",
            'info_description' => 'Mengaji rutin berlangsung setiap Senin–Jumat sesuai kelas dan jenjang masing-masing santri. Saat ini 115 santri aktif belajar bersama 11 ustadz dan ustadzah, dengan satu pengajar mendampingi sekitar 10–15 santri.',
            'info_age_range' => null,
            'info_schedule' => 'Senin–Jumat · 15.30–17.00 WIB',
            'info_fee' => null,
            'registration_title' => 'Mari mulai perjalanan belajar anak',
            'registration_description' => 'Pendaftaran santri baru dilakukan dengan mengisi formulir dari TPQ dan melampirkan fotokopi Kartu Keluarga serta akta kelahiran, masing-masing 2 lembar. Hubungi pengurus untuk mendapatkan formulir.',
            'registration_phone' => '0812-3956-7390',
            'registration_form_url' => null,
        ])->save();

        $pillars = [
            [
                'title' => "Membaca Al-Qur'an dengan tartil",
                'description' => 'Metode Tartil berjenjang dari Tartil 1 hingga Tartil 6, dilanjutkan tingkatan Marhalah Ula, Wustha, Akhir, dan Diniyah.',
                'icon' => ProfileIcon::Quran,
            ],
            [
                'title' => "Menulis Al-Qur'an dengan benar",
                'description' => "Santri dibimbing menulis Al-Qur'an dengan baik dan benar sebagai bagian dari misi TPQ.",
                'icon' => ProfileIcon::Book,
            ],
            [
                'title' => 'Munaqosah setiap enam bulan',
                'description' => 'Kenaikan jenjang dan kelulusan ditentukan melalui ujian munaqosah internal TPQ dan munaqosah tingkat kecamatan.',
                'icon' => ProfileIcon::Check,
            ],
            [
                'title' => 'Ekstrakurikuler & tadarus',
                'description' => 'Jujitsu, banjari, dan drum band setiap Sabtu, tadarus bersama setiap pekan, serta tadarling (tadarus keliling) setiap bulan.',
                'icon' => ProfileIcon::People,
            ],
        ];

        $sortOrder = 1;

        foreach ($pillars as $pillar) {
            TpqPillar::query()->updateOrCreate(
                ['title' => $pillar['title']],
                ['description' => $pillar['description'], 'icon' => $pillar['icon'], 'sort_order' => $sortOrder++],
            );
        }

        $faqs = [
            [
                'question' => 'Kapan jadwal mengaji?',
                'answer' => 'Setiap Senin–Jumat pukul 15.30–17.00 WIB, sesuai kelas dan jenjang santri. Ekstrakurikuler diadakan setiap hari Sabtu.',
            ],
            [
                'question' => 'Apa syarat pendaftaran?',
                'answer' => 'Mengisi formulir pendaftaran dari TPQ dan melampirkan fotokopi Kartu Keluarga serta akta kelahiran, masing-masing 2 lembar.',
            ],
            [
                'question' => 'Bagaimana pembayaran SPP?',
                'answer' => 'SPP dibayarkan setiap bulan menggunakan kartu SPP, dan bisa ditransfer kepada bendahara TPQ.',
            ],
            [
                'question' => 'Bagaimana memantau perkembangan anak?',
                'answer' => 'Nilai harian dicatat pengajar di buku prestasi yang bisa diperiksa orang tua. Kenaikan jenjang ditentukan lewat munaqosah setiap enam bulan.',
            ],
        ];

        $sortOrder = 1;

        foreach ($faqs as $faq) {
            TpqFaq::query()->updateOrCreate(
                ['question' => $faq['question']],
                ['answer' => $faq['answer'], 'sort_order' => $sortOrder++],
            );
        }

        // Foto asli papan TPQ (sudah dipakai di Beranda) sebagai dokumentasi pertama.
        $boardPhoto = public_path('images/beranda/tpq-papan-visi-misi-1200.jpg');

        if (is_file($boardPhoto)) {
            $caption = 'Papan nama serta visi dan misi TPQ Nurul Haqq';
            $media = app(MediaUploader::class)->importFile($boardPhoto, 'tpq/papan-visi-misi-tpq.jpg', $caption);

            TpqDocumentation::query()->firstOrCreate(
                ['image_id' => $media->id],
                ['caption' => $caption, 'sort_order' => 1],
            );
        }
    }
}
