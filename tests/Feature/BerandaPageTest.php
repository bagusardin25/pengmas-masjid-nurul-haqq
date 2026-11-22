<?php

use App\Enums\ProfileIcon;
use App\Enums\PublicationStatus;
use App\Models\AboutStat;
use App\Models\Agenda;
use App\Models\ContactPhone;
use App\Models\HomePage;
use App\Models\Media;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->travelTo(CarbonImmutable::parse('2026-11-13 10:00:00', 'Asia/Jakarta'));
    Http::fake(['https://api.myquran.com/*' => Http::response([], 500)]);
});

test('beranda hero text comes from the database and keeps line breaks in the title', function () {
    HomePage::factory()->create([
        'hero_eyebrow' => 'LABEL HERO DARI DATABASE',
        'hero_title' => "Masjid\nNurul Haqq",
        'hero_description' => 'Deskripsi hero dari database',
        'hero_arabic_text' => 'نص عربي',
        'hero_translation' => 'Terjemahan dari database',
        'hero_citation' => 'QS. UJI: 1',
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('LABEL HERO DARI DATABASE')
        ->assertSee('Masjid<br />', false)
        ->assertSee('Deskripsi hero dari database')
        ->assertSee('نص عربي')
        ->assertSee('Terjemahan dari database')
        ->assertSee('QS. UJI: 1');
});

test('beranda uses the default hero photo until one is uploaded', function () {
    HomePage::factory()->create();

    $this->get(route('home'))
        ->assertOk()
        ->assertSee(asset(HomePage::DEFAULT_HERO_IMAGE), false)
        ->assertSee(HomePage::DEFAULT_HERO_ALT);
});

test('beranda shows the uploaded hero photo with its description', function () {
    $media = Media::factory()->create(['path' => 'beranda/foto-hero.jpg', 'alt' => 'Masjid saat senja', 'width' => 1600, 'height' => 1200]);
    HomePage::factory()->create(['hero_image_id' => $media->id]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('src="'.$media->url().'"', false)
        ->assertSee('alt="Masjid saat senja"', false)
        ->assertSee('width="1600" height="1200"', false)
        ->assertDontSee(asset(HomePage::DEFAULT_HERO_IMAGE), false);
});

test('beranda statistics come from the profil statistics', function () {
    AboutStat::factory()->create(['value' => '999 m²', 'label' => 'Luas uji', 'icon' => ProfileIcon::Area, 'sort_order' => 1]);
    AboutStat::factory()->create(['value' => '42', 'label' => 'Statistik kedua', 'icon' => ProfileIcon::People, 'sort_order' => 2]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSeeInOrder(['999 m²', 'Luas uji', '42', 'Statistik kedua'])
        ->assertDontSee('270 m²')
        ->assertDontSee('Daya tampung jamaah');
});

test('beranda links to the full pages instead of its own sections', function () {
    $response = $this->get(route('home'))->assertOk();

    $response->assertSee('href="'.route('jadwal').'"', false)
        ->assertSee('href="'.route('galeri').'"', false)
        ->assertSee('href="'.route('ziswaf').'"', false);

    foreach (['#jadwal', '#galeri', '#ziswaf'] as $anchor) {
        $response->assertDontSee('href="'.$anchor.'"', false);
    }
});

test('the save to calendar button links to the nearest agenda and hides without one', function () {
    $this->get(route('home'))->assertOk()->assertDontSee('Simpan ke kalender');

    $agenda = Agenda::factory()->create([
        'title' => 'Sholat Jumat',
        'slug' => 'sholat-jumat-2026-11-20',
        'date' => '2026-11-20',
        'status' => PublicationStatus::Published,
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('href="'.route('agenda.calendar', $agenda).'" download', false)
        ->assertSee('Simpan ke kalender');
});

test('beranda contact numbers link to valid whatsapp and phone urls', function () {
    ContactPhone::factory()->create(['label' => 'Sekretariat', 'number' => '0812-3956-7390', 'is_whatsapp' => true]);
    ContactPhone::factory()->create(['label' => 'Takmir', 'number' => '0812-3456-7890', 'is_whatsapp' => false]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('href="https://wa.me/6281239567390"', false)
        ->assertSee('href="tel:081234567890"', false)
        ->assertDontSee('wa.me/0812', false);
});
