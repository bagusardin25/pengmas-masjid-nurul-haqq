<?php

namespace Database\Seeders;

use App\Enums\ProfileIcon;
use App\Models\ZiswafContact;
use App\Models\ZiswafFund;
use App\Models\ZiswafPage;
use App\Models\ZiswafProgram;
use Illuminate\Database\Seeder;

/**
 * Data ZISWAF Masjid Nurul Haqq.
 *
 * Sumber: "Notulensi Rapat Jum'at, 30 Oktober 2026.pdf" (rekening BSI, kontak konfirmasi,
 * kategori sedekah, mekanisme kotak infak, laporan keuangan) dan "SK AMIL ZAKAT, INFAQ,
 * SHODAQOH (ZIS) MASJID NURUL HAQQ.pdf" (zakat dihimpun JPZIS NU CARE-LAZISNU).
 * QRIS belum diberikan dan belum ada file laporan, jadi keduanya dibiarkan kosong.
 */
class ZiswafPageSeeder extends Seeder
{
    public function run(): void
    {
        (ZiswafPage::current() ?? new ZiswafPage)->fill([
            'hero_title' => 'Salurkan kebaikan, kuatkan kebersamaan',
            'hero_description' => 'Salurkan sedekah dan infak untuk kegiatan, pembangunan, dan kepedulian sosial Masjid Nurul Haqq. Setiap penerimaan dicatat dan dilaporkan secara berkala kepada jamaah.',
            'summary' => 'Sedekah dan infak dikelola Takmir Masjid Nurul Haqq dan dilaporkan secara berkala kepada jamaah. Zakat dihimpun dan disalurkan JPZIS NU CARE-LAZISNU Masjid Nurul Haqq Tawangsari.',
            'bank_name' => 'Bank Syariah Indonesia (BSI)',
            'account_number' => '7211074177',
            'account_holder' => 'Budiyono',
            'account_note' => 'Rekening Bendahara Takmir Masjid Nurul Haqq.',
            'qris_note' => null,
            'infaq_box_description' => 'Kotak infak dibuka dan dihitung setelah sholat Jumat. Jadwal pembukaan diumumkan satu pekan sebelumnya, lalu hasil penghitungan dilaporkan kepada jamaah sebagai bentuk transparansi.',
            'report_description' => 'Laporan pemasukan dan pengeluaran masjid: rutin setiap pekan, per kegiatan seperti Ramadan dan Maulid Nabi, serta pembangunan masjid tahap II.',
        ])->save();

        // Rapat menggabungkan sedekah dan infak menjadi satu bagian. Zakat dan wakaf tetap ada
        // dan bisa disembunyikan atau diubah pengurus dari dashboard.
        $funds = [
            ['name' => 'Sedekah & Infak', 'description' => 'Operasional, pembangunan, dan program sosial masjid', 'icon' => ProfileIcon::Heart],
            ['name' => 'Zakat', 'description' => 'Mal & fitrah melalui JPZIS NU CARE-LAZISNU', 'icon' => ProfileIcon::People],
            ['name' => 'Wakaf', 'description' => 'Aset produktif umat', 'icon' => ProfileIcon::Building],
        ];

        $sortOrder = 1;

        foreach ($funds as $fund) {
            ZiswafFund::query()->updateOrCreate(
                ['name' => $fund['name']],
                ['description' => $fund['description'], 'icon' => $fund['icon'], 'sort_order' => $sortOrder++],
            );
        }

        $programs = [
            ['title' => 'Peduli Palestina', 'description' => 'Penyaluran bantuan kepada masyarakat Palestina.'],
            ['title' => 'Anak Yatim', 'description' => 'Kegiatan santunan dan bantuan bagi anak yatim.'],
            ['title' => 'Kotak Ambulans', 'description' => 'Mendukung kebutuhan dan operasional layanan ambulans masjid.'],
        ];

        $sortOrder = 1;

        foreach ($programs as $program) {
            ZiswafProgram::query()->updateOrCreate(
                ['title' => $program['title']],
                ['description' => $program['description'], 'sort_order' => $sortOrder++],
            );
        }

        $contacts = [
            ['name' => 'Gatot Bambang', 'role' => 'Sekretaris Takmir', 'phone' => '0812-3028-185'],
            ['name' => 'H. Iswanto, S.Pd', 'role' => 'Wakil Ketua I Takmir', 'phone' => '0812-3058-294'],
        ];

        $sortOrder = 1;

        foreach ($contacts as $contact) {
            ZiswafContact::query()->updateOrCreate(
                ['phone' => $contact['phone']],
                ['name' => $contact['name'], 'role' => $contact['role'], 'sort_order' => $sortOrder++],
            );
        }
    }
}
