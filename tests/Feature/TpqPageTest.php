<?php

use App\Enums\ProfileIcon;
use App\Models\TpqFaq;
use App\Models\TpqPage;
use App\Models\TpqPillar;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    // Beranda memanggil API jadwal sholat; cukup gagal tanpa jaringan.
    Http::fake(['https://api.myquran.com/*' => Http::response([], 500)]);
});

test('tpq page shows content from the database', function () {
    TpqPage::factory()->create([
        'hero_title' => 'Judul TPQ dari database',
        'hero_description' => 'Deskripsi TPQ dari database',
        'summary' => 'Ringkasan metode Tartil',
        'info_description' => '115 santri aktif bersama 11 pengajar',
        'info_schedule' => 'Senin–Jumat · 15.30–17.00 WIB',
        'registration_title' => 'Daftarkan anak Anda',
        'registration_description' => 'Lampirkan fotokopi KK dan akta kelahiran',
        'registration_phone' => '0812-3956-7390',
    ]);
    TpqPillar::factory()->create(['title' => 'Munaqosah setiap enam bulan', 'icon' => ProfileIcon::Check]);
    TpqFaq::factory()->create(['question' => 'Apa syarat pendaftaran?', 'answer' => 'Formulir, KK, dan akta kelahiran.']);

    $this->get(route('tpq'))
        ->assertOk()
        ->assertSee('Judul TPQ dari database')
        ->assertSee('Deskripsi TPQ dari database')
        ->assertDontSee('Ringkasan metode Tartil')
        ->assertSee('115 santri aktif bersama 11 pengajar')
        ->assertSee('<dt>Jadwal</dt><dd>Senin–Jumat · 15.30–17.00 WIB</dd>', false)
        ->assertSee('Daftarkan anak Anda')
        ->assertSee('Lampirkan fotokopi KK dan akta kelahiran')
        ->assertSee('Munaqosah setiap enam bulan')
        ->assertSee(ProfileIcon::Check->glyph())
        ->assertSee('Apa syarat pendaftaran?')
        ->assertSee('href="https://wa.me/6281239567390"', false);
});

test('tpq page hides details that have not been filled in', function () {
    TpqPage::factory()->create([
        'info_age_range' => null,
        'info_schedule' => 'Senin–Jumat · 15.30–17.00 WIB',
        'info_fee' => null,
    ]);

    $this->get(route('tpq'))
        ->assertOk()
        ->assertDontSee('<dt>Usia</dt>', false)
        ->assertDontSee('<dt>Biaya</dt>', false)
        ->assertDontSee('6–15 tahun')
        ->assertDontSee('Rp50.000');
});

test('the register button prefers the online form when one is set', function () {
    TpqPage::factory()->create([
        'registration_phone' => '0812-3956-7390',
        'registration_form_url' => 'https://forms.example.test/tpq',
    ]);

    $this->get(route('tpq'))
        ->assertOk()
        ->assertSee('<a class="button button-gold button-full" href="https://forms.example.test/tpq">Daftar TPQ</a>', false)
        ->assertSee('href="https://wa.me/6281239567390"', false);
});

test('tpq page renders before any content is saved', function () {
    $this->get(route('tpq'))
        ->assertOk()
        ->assertSee('Program TPQ')
        ->assertDontSee('Daftar TPQ</a>', false);
});

test('beranda tpq section uses database content and never shows placeholders', function () {
    TpqPage::factory()->create([
        'summary' => 'Ringkasan TPQ untuk Beranda',
        'info_age_range' => null,
        'info_schedule' => 'Senin–Jumat · 15.30–17.00 WIB',
        'info_fee' => null,
        'registration_phone' => '0812-3956-7390',
    ]);
    TpqPillar::factory()->create(['title' => 'Ekstrakurikuler & tadarus']);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Ringkasan TPQ untuk Beranda')
        ->assertSee('Ekstrakurikuler &amp; tadarus', false)
        ->assertSee('Senin–Jumat · 15.30–17.00 WIB')
        ->assertDontSee('[rentang usia]')
        ->assertDontSee('[biaya per bulan]')
        ->assertDontSee('>USIA<', false)
        ->assertDontSee('BIAYA</p>', false);
});

test('whatsapp url is built from the registration phone', function () {
    expect((new TpqPage(['registration_phone' => '0812-3956-7390']))->whatsappUrl())->toBe('https://wa.me/6281239567390')
        ->and((new TpqPage(['registration_phone' => '+62 812 3956 7390']))->whatsappUrl())->toBe('https://wa.me/6281239567390')
        ->and((new TpqPage(['registration_phone' => null]))->whatsappUrl())->toBeNull();
});
