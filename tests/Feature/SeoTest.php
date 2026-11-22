<?php

use App\Models\Article;
use App\Models\ContactPhone;
use App\Models\ContactSetting;
use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Models\User;

test('public pages have seo meta tags and icons', function () {
    $this->get(route('profil'))
        ->assertOk()
        ->assertSee('<title>Profil Masjid — Masjid Jami Nurul Haqq</title>', false)
        ->assertSee('<meta name="description" content="Profil, fasilitas, sejarah, dan kepengurusan Masjid Jami Nurul Haqq.">', false)
        ->assertSee('<link rel="canonical" href="'.route('profil').'">', false)
        ->assertSee('<meta property="og:image" content="'.asset('images/og-default.jpg').'">', false)
        ->assertSee('<meta name="twitter:card" content="summary_large_image">', false)
        ->assertSee('images/icons/apple-touch-icon.png', false)
        ->assertSee('site.webmanifest', false);
});

test('the home page describes the mosque with structured data', function () {
    ContactSetting::factory()->create(['email' => 'takmirmnh22@gmail.com']);
    ContactPhone::factory()->create(['number' => '0812-3028-185']);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('<title>Masjid Jami Nurul Haqq — Griya Taman Asri, Sidoarjo</title>', false)
        ->assertSee('"@type":"Mosque"', false)
        ->assertSee('"email":"takmirmnh22@gmail.com"', false)
        ->assertSee('"telephone":"0812-3028-185"', false);

    $this->get(route('kontak'))->assertDontSee('application/ld+json', false);
});

test('albums and articles share their own cover image', function () {
    $album = GalleryAlbum::factory()->published()->create();
    $photo = GalleryPhoto::factory()->for($album, 'album')->create();
    $article = Article::factory()->published()->create();

    $this->get(route('galeri.album', $album))
        ->assertSee('<meta property="og:image" content="'.$photo->image->url().'">', false);

    $this->get(route('galeri.artikel', $article))
        ->assertSee('<meta property="og:type" content="article">', false)
        ->assertSee('<meta property="og:image" content="'.asset('images/og-default.jpg').'">', false);
});

test('admin pages are hidden from search engines', function () {
    $this->get('/login')->assertSee('<meta name="robots" content="noindex, nofollow">', false);
    $this->actingAs(User::factory()->create())
        ->get(route('dashboard'))
        ->assertSee('<meta name="robots" content="noindex, nofollow">', false);
});

test('robots.txt blocks the dashboard and points to the sitemap', function () {
    $this->get('/robots.txt')
        ->assertOk()
        ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
        ->assertSee('Disallow: /dashboard')
        ->assertSee('Sitemap: '.route('sitemap'));
});

test('the sitemap lists public pages and only published content', function () {
    $album = GalleryAlbum::factory()->published()->create();
    GalleryPhoto::factory()->for($album, 'album')->create();
    $draftAlbum = GalleryAlbum::factory()->create();
    $article = Article::factory()->published()->create();
    $draftArticle = Article::factory()->create();

    $this->get('/sitemap.xml')
        ->assertOk()
        ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
        ->assertSee('<loc>'.route('kontak').'</loc>', false)
        ->assertSee('<loc>'.route('galeri.album', $album).'</loc>', false)
        ->assertSee('<loc>'.route('galeri.artikel', $article).'</loc>', false)
        ->assertDontSee(route('galeri.album', $draftAlbum), false)
        ->assertDontSee(route('galeri.artikel', $draftArticle), false)
        ->assertDontSee('/dashboard', false);
});

test('titles and descriptions with special characters are escaped only once', function () {
    $album = GalleryAlbum::factory()->published()->create([
        'title' => "Isra Mi'raj & Maulid",
        'description' => 'Kajian "Tafsir" & doa bersama',
    ]);
    GalleryPhoto::factory()->for($album, 'album')->create();

    $this->get(route('jadwal'))
        ->assertSee('<title>Jadwal &amp; Kegiatan — Masjid Jami Nurul Haqq</title>', false)
        ->assertDontSee('&amp;amp;', false);

    $this->get(route('galeri.album', $album))
        ->assertSee('<title>Isra Mi&#039;raj &amp; Maulid — Masjid Jami Nurul Haqq</title>', false)
        ->assertSee('content="Kajian &quot;Tafsir&quot; &amp; doa bersama"', false)
        ->assertDontSee('&amp;amp;', false)
        ->assertDontSee('&amp;#039;', false);
});
