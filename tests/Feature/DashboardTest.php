<?php

use App\Enums\PublicationStatus;
use App\Models\Agenda;
use App\Models\ContactPhone;
use App\Models\ContactSetting;
use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Models\User;
use App\Models\ZiswafReport;
use Illuminate\Support\Js;

test('verified users can view the empty dashboard shell', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertOk()
        ->assertSee('dashboard-sidebar', false)
        ->assertSee('Dashboard')
        ->assertSee('Kelola Beranda')
        ->assertSee('Profil Masjid')
        ->assertSee('href="'.route('dashboard.beranda').'"', false)
        ->assertSee('id="dashboard-main-content" class="dashboard-content"', false)
        ->assertDontSee("You're logged in!");
});

test('verified users can view the empty home management page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('dashboard.beranda'));

    $response->assertOk()
        ->assertSee('Kelola Beranda')
        ->assertSee('aria-current="page"', false)
        ->assertSee('id="dashboard-main-content" class="dashboard-content"', false)
        ->assertDontSee("You're logged in!");
});

test('the dashboard summarizes content and links every management page', function () {
    $user = User::factory()->create(['name' => 'Gatot']);

    Agenda::factory()->create([
        'title' => 'Pengajian Maulid',
        'date' => now('Asia/Jakarta')->addDays(3)->toDateString(),
        'status' => PublicationStatus::Published,
    ]);
    ZiswafReport::factory()->published()->create(['title' => 'Laporan kas pekan ini']);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertOk()
        ->assertSee('Assalamu’alaikum, Gatot')
        ->assertSee('Pengajian Maulid')
        ->assertSee('Laporan kas pekan ini')
        ->assertSee('Kelengkapan situs');

    foreach (['beranda', 'profil', 'jadwal', 'tpq', 'ziswaf', 'galeri', 'kontak'] as $page) {
        $response->assertSee('href="'.route('dashboard.'.$page).'"', false);
    }
});

test('the dashboard checklist shows what still needs to be filled in', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('0 dari 8 lengkap')
        ->assertSee('Belum ada laporan mingguan yang terbit.')
        ->assertSee('Belum ada agenda terjadwal.');

    ContactPhone::factory()->whatsapp()->create();
    ContactSetting::factory()->create(['email' => 'takmirmnh22@gmail.com', 'maps_embed_url' => 'https://www.google.com/maps/embed?pb=1']);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertSee('2 dari 8 lengkap');
});

test('each management page previews its own public page on mobile', function () {
    $user = User::factory()->create();

    $pages = [
        'dashboard' => route('home'),
        'dashboard.kontak' => route('kontak'),
        'dashboard.galeri' => route('galeri'),
        'dashboard.ziswaf' => route('ziswaf'),
    ];

    foreach ($pages as $page => $publicUrl) {
        $this->actingAs($user)
            ->get(route($page))
            ->assertOk()
            ->assertSee('Pratinjau HP')
            ->assertSee('livePreview('.Js::from($publicUrl)->toHtml().')', false);
    }
});

test('a published album previews its own album page', function () {
    $user = User::factory()->create();
    $album = GalleryAlbum::factory()->published()->create();
    GalleryPhoto::factory()->for($album, 'album')->create();

    $this->actingAs($user)
        ->get(route('dashboard.galeri.albums.edit', $album))
        ->assertSee('livePreview('.Js::from(route('galeri.album', $album))->toHtml().')', false);
});
