<?php

use App\Enums\ProfileIcon;
use App\Enums\PublicationStatus;
use App\Enums\ReportCategory;
use App\Models\Media;
use App\Models\ZiswafContact;
use App\Models\ZiswafFund;
use App\Models\ZiswafPage;
use App\Models\ZiswafProgram;
use App\Models\ZiswafReport;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->travelTo(CarbonImmutable::parse('2026-11-22 10:00:00', 'Asia/Jakarta'));
    Http::fake(['https://api.myquran.com/*' => Http::response([], 500)]);
});

test('the ziswaf page shows the bank account, funds, programs, and confirmation contacts from the database', function () {
    ZiswafPage::factory()->create([
        'hero_title' => 'Judul ZISWAF tersimpan',
        'hero_description' => 'Deskripsi ZISWAF tersimpan',
        'bank_name' => 'Bank Syariah Indonesia (BSI)',
        'account_number' => '7211074177',
        'account_holder' => 'Budiyono',
        'account_note' => 'Rekening Bendahara Takmir Masjid Nurul Haqq.',
        'infaq_box_description' => 'Kotak infak dibuka setelah sholat Jumat.',
    ]);
    ZiswafFund::factory()->create(['name' => 'Sedekah & Infak', 'icon' => ProfileIcon::Heart, 'sort_order' => 1]);
    ZiswafFund::factory()->create(['name' => 'Zakat', 'description' => 'Mal & fitrah', 'sort_order' => 2]);
    ZiswafProgram::factory()->create(['title' => 'Peduli Palestina', 'sort_order' => 1]);
    ZiswafProgram::factory()->create(['title' => 'Kotak Ambulans', 'sort_order' => 2]);
    ZiswafContact::factory()->create(['name' => 'Gatot Bambang', 'role' => 'Sekretaris Takmir', 'phone' => '0812-3028-185', 'sort_order' => 1]);

    $this->get(route('ziswaf'))
        ->assertOk()
        ->assertSee('Judul ZISWAF tersimpan')
        ->assertSee('Deskripsi ZISWAF tersimpan')
        ->assertSee('Bank Syariah Indonesia (BSI)')
        ->assertSee('7211074177')
        ->assertSee('a.n. Budiyono')
        ->assertSee('Rekening Bendahara Takmir Masjid Nurul Haqq.')
        ->assertSeeInOrder(['Sedekah &amp; Infak', 'Zakat', 'Mal &amp; fitrah'], false)
        ->assertSeeInOrder(['Peduli Palestina', 'Kotak Ambulans'])
        ->assertSee('Gatot Bambang')
        ->assertSee('Sekretaris Takmir')
        ->assertSee('href="https://wa.me/628123028185"', false)
        ->assertSee('Kotak infak dibuka setelah sholat Jumat.')
        ->assertSee('Transfer ke rekening masjid')
        ->assertDontSee('QRIS demo')
        ->assertDontSee('Bank Demo Syariah')
        ->assertDontSee('Rp 23.122.000');
});

test('hidden funds and programs are not shown on the ziswaf page or beranda', function () {
    ZiswafPage::factory()->create();
    ZiswafFund::factory()->create(['name' => 'Infak tampil']);
    ZiswafFund::factory()->create(['name' => 'Wakaf tersembunyi', 'is_active' => false]);
    ZiswafProgram::factory()->create(['title' => 'Program tersembunyi', 'is_active' => false]);

    $this->get(route('ziswaf'))
        ->assertOk()
        ->assertSee('Infak tampil')
        ->assertDontSee('Wakaf tersembunyi')
        ->assertDontSee('Program tersembunyi')
        ->assertDontSee('Pilih program penyaluran');

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Infak tampil')
        ->assertDontSee('Wakaf tersembunyi');
});

test('the qris image only appears after it is uploaded', function () {
    $page = ZiswafPage::factory()->create(['account_number' => '7211074177']);

    $this->get(route('ziswaf'))->assertOk()->assertDontSee('Kode QRIS');

    $qris = Media::factory()->create(['path' => 'ziswaf/qris.png']);
    $page->update(['qris_image_id' => $qris->id, 'qris_note' => 'Scan dengan aplikasi bank apa saja']);

    $this->get(route('ziswaf'))
        ->assertOk()
        ->assertSee('Transfer atau scan QRIS')
        ->assertSee('src="'.$qris->url().'"', false)
        ->assertSee('Scan dengan aplikasi bank apa saja');
});

test('the bank account is hidden until an account number is filled in', function () {
    ZiswafPage::factory()->create(['account_number' => null, 'bank_name' => 'Bank tanpa nomor']);

    $this->get(route('ziswaf'))
        ->assertOk()
        ->assertDontSee('Bank tanpa nomor')
        ->assertDontSee('href="#cara-menyalurkan"', false);
});

test('the ziswaf page renders before any content is saved', function () {
    $this->get(route('ziswaf'))
        ->assertOk()
        ->assertSee('Laporan keuangan masjid')
        ->assertSee('Belum ada laporan yang diterbitkan.');
});

test('only published reports appear, newest first, grouped by category', function () {
    ZiswafPage::factory()->create();
    ZiswafReport::factory()->published()->create(['title' => 'Kas pekan lama', 'report_date' => '2026-11-06']);
    ZiswafReport::factory()->published()->create(['title' => 'Kas pekan terbaru', 'report_date' => '2026-11-20']);
    ZiswafReport::factory()->create(['title' => 'Kas pekan draf', 'report_date' => '2026-11-21']);
    ZiswafReport::factory()->published()->create([
        'title' => 'Laporan Maulid Nabi',
        'category' => ReportCategory::Event,
        'report_date' => '2026-09-10',
    ]);

    $response = $this->get(route('ziswaf'))->assertOk();

    $response->assertSeeInOrder(['Laporan rutin mingguan', 'Kas pekan terbaru', 'Kas pekan lama', 'Laporan kegiatan', 'Laporan Maulid Nabi'])
        ->assertDontSee('Kas pekan draf')
        ->assertSee('20 November 2026')
        ->assertSee(route('ziswaf.laporan', ['kategori' => 'mingguan']), false);

    $latest = ZiswafReport::query()->where('title', 'Kas pekan terbaru')->firstOrFail();

    $response->assertSee('href="'.$latest->reportFile->url().'"', false)
        ->assertSee('download="laporan.pdf"', false);
});

test('the report list page filters by category and paginates', function () {
    ZiswafReport::factory()->published()->create(['title' => 'Laporan pembangunan tahap II', 'category' => ReportCategory::Construction]);
    ZiswafReport::factory()->published()->create(['title' => 'Kas pekan ke-3']);
    ZiswafReport::factory()->create(['title' => 'Pembangunan draf', 'category' => ReportCategory::Construction]);

    $this->get(route('ziswaf.laporan'))
        ->assertOk()
        ->assertSee('Laporan pembangunan tahap II')
        ->assertSee('Kas pekan ke-3')
        ->assertDontSee('Pembangunan draf');

    $this->get(route('ziswaf.laporan', ['kategori' => 'pembangunan']))
        ->assertOk()
        ->assertSee('Laporan pembangunan tahap II')
        ->assertDontSee('Kas pekan ke-3')
        ->assertSee('aria-current="true"', false);

    $this->get(route('ziswaf.laporan', ['kategori' => 'kegiatan']))
        ->assertOk()
        ->assertSee('Belum ada laporan kategori Kegiatan yang diterbitkan.');

    ZiswafReport::factory()->published()->count(13)->create(['report_date' => '2026-01-01']);

    $this->get(route('ziswaf.laporan'))->assertOk()->assertSee('page=2', false);

    // Desain pagination situs dalam bahasa Indonesia (sebelumnya diuji lewat album galeri).
    $this->get(route('ziswaf.laporan', ['page' => 2]))
        ->assertOk()
        ->assertSee('Menampilkan')
        ->assertSee('dari <strong>15</strong> laporan', false)
        ->assertSee('aria-current="page"', false)
        ->assertSee('Sebelumnya')
        ->assertDontSee('Showing')
        ->assertDontSee('results');
});

test('beranda shows the ziswaf summary, bank account, contacts, and latest reports', function () {
    ZiswafPage::factory()->create([
        'hero_title' => 'Judul ZISWAF Beranda',
        'summary' => 'Ringkasan ZISWAF untuk Beranda',
        'bank_name' => 'Bank Syariah Indonesia (BSI)',
        'account_number' => '7211074177',
        'account_holder' => 'Budiyono',
    ]);
    ZiswafContact::factory()->create(['name' => 'H. Iswanto, S.Pd', 'phone' => '0812-3058-294']);
    ZiswafReport::factory()->published()->create(['title' => 'Laporan Ramadan 1447 H', 'category' => ReportCategory::Event]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Judul ZISWAF Beranda')
        ->assertSee('Ringkasan ZISWAF untuk Beranda')
        ->assertSee('7211074177')
        ->assertSee('href="https://wa.me/628123058294"', false)
        ->assertSee('Laporan Ramadan 1447 H')
        ->assertSee(route('ziswaf.laporan'), false)
        ->assertDontSee('Bank Syariah Mandiri')
        ->assertDontSee('7005342184')
        ->assertDontSee('Rp 23.122.000');
});

test('beranda explains that no report has been published yet', function () {
    ZiswafReport::factory()->create(['title' => 'Laporan draf', 'status' => PublicationStatus::Draft]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Belum ada laporan yang diterbitkan.')
        ->assertDontSee('Laporan draf');
});
