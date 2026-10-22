<?php

namespace Database\Seeders;

use App\Models\TpqFaq;
use App\Models\TpqPage;
use App\Models\TpqPillar;
use Illuminate\Database\Seeder;

class TpqPageSeeder extends Seeder
{
    public function run(): void
    {
        (TpqPage::current() ?? new TpqPage)->fill([
            'hero_title' => "Tumbuh bersama Al-Qur'an sejak dini",
            'hero_description' => "Program TPQ Masjid Jami Nurul Haqq membantu anak-anak usia 6–15 tahun belajar membaca Al-Qur'an, membangun hafalan, dan mengamalkan akhlak mulia sejak dini.",
            'summary' => "Pendidikan Al-Qur'an yang terstruktur dan intensif untuk tumbuh kembang spiritual anak-anak dan remaja di lingkungan Tawangsari.",
            'info_description' => 'Program TPQ Masjid Jami Nurul Haqq khusus anak usia 6–15 tahun. Kelas berlangsung Senin–Kamis pukul 15.30–17.00 WIB dengan biaya Rp50.000 per bulan.',
            'info_age_range' => '6–15 tahun',
            'info_schedule' => 'Senin–Kamis · 15.30–17.00 WIB',
            'info_fee' => 'Rp50.000 / bulan',
            'registration_title' => 'Mari mulai perjalanan belajar anak',
            'registration_description' => 'Pendaftaran dibuka dengan syarat identitas wali dan formulir pendaftaran. Biaya pendaftaran kelas dapat dikonfirmasi kepada pengurus.',
            'registration_phone' => '0812-3956-7390',
        ])->save();

        $pillars = [
            [
                'title' => "Belajar Iqro' & Al-Qur'an",
                'description' => "Pembelajaran membaca Al-Qur'an dimulai dari Iqro' dasar hingga Al-Qur'an dengan metode yang interaktif dan terstruktur.",
            ],
            [
                'title' => "Tahfidzul Qur'an sejak dini",
                'description' => "Santri diajarkan hafalan Al-Qur'an secara bertahap agar menjadi kebiasaan harian dan penguatan spiritual.",
            ],
            [
                'title' => 'Akhlak & doa harian',
                'description' => 'Pembelajaran akhlak dan doa harian membantu anak memahami nilai-nilai baik dalam keseharian.',
            ],
            [
                'title' => 'Kelas reguler & intensif',
                'description' => 'Kelas reguler dan intensif disiapkan agar orang tua dapat memilih jadwal belajar yang sesuai dengan kebutuhan anak.',
            ],
        ];

        $sortOrder = 1;

        foreach ($pillars as $pillar) {
            TpqPillar::query()->updateOrCreate(
                ['title' => $pillar['title']],
                ['description' => $pillar['description'], 'sort_order' => $sortOrder++],
            );
        }

        $faqs = [
            [
                'question' => 'Usia & pilihan kelas',
                'answer' => 'Tanyakan rentang usia serta informasi kelas reguler dan intensif yang sesuai untuk anak.',
            ],
            [
                'question' => 'Hari & jam belajar',
                'answer' => 'Konfirmasikan hari dan jam pembelajaran kepada sekretariat sebelum memilih kelas.',
            ],
            [
                'question' => 'Biaya & pendaftaran',
                'answer' => 'Tanyakan biaya per bulan dan informasi pendaftaran yang sudah terkonfirmasi.',
            ],
        ];

        $sortOrder = 1;

        foreach ($faqs as $faq) {
            TpqFaq::query()->updateOrCreate(
                ['question' => $faq['question']],
                ['answer' => $faq['answer'], 'sort_order' => $sortOrder++],
            );
        }
    }
}
