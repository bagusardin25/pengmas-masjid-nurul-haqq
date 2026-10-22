<?php

namespace Database\Seeders;

use App\Enums\PublicationStatus;
use App\Models\ZiswafPage;
use App\Models\ZiswafReport;
use Illuminate\Database\Seeder;

class ZiswafPageSeeder extends Seeder
{
    public function run(): void
    {
        (ZiswafPage::current() ?? new ZiswafPage)->fill([
            'hero_title' => 'Salurkan kebaikan, kuatkan kebersamaan',
            'hero_description' => 'Salurkan zakat, infak, sedekah, dan wakaf melalui Yayasan Nurul Haqq. Dana dikelola amanah dan dilaporkan setiap bulan kepada jamaah.',
            'zakat_note' => 'Mal & fitrah',
            'infaq_note' => 'Kas & operasional',
            'sedekah_note' => 'Kepedulian sosial',
            'wakaf_note' => 'Aset produktif umat',
            'bank_name' => 'Bank Demo Syariah',
            'account_number' => 'DEMO-0000-0000',
            'account_holder' => 'Yayasan Nurul Haqq',
            'qris_note' => 'QRIS demo · tidak untuk pembayaran',
            'confirmation_phone' => '0812-3956-7390',
        ])->save();

        $report = ZiswafReport::query()->whereDate('period', '2026-05-01')->first() ?? new ZiswafReport;

        $report->fill([
            'period' => '2026-05-01',
            'zakat' => 4875000,
            'infaq' => 6342000,
            'sedekah' => 3215000,
            'wakaf' => 8690000,
            'disbursed' => 18000000,
            'status' => PublicationStatus::Published,
        ])->save();
    }
}
