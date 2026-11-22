<?php

use App\Models\Article;
use App\Models\ContactSetting;
use App\Models\GalleryAlbum;
use App\Models\GalleryCategory;
use App\Models\GalleryPhoto;
use App\Models\GalleryVideo;
use App\Models\Media;

function albumWithPhotos(array $attributes = [], int $photos = 1): GalleryAlbum
{
    $album = GalleryAlbum::factory()->published()->create($attributes);
    GalleryPhoto::factory()->count($photos)->for($album, 'album')->create();

    return $album;
}

test('the gallery page shows published albums with photos, videos, and articles', function () {
    $album = albumWithPhotos(['title' => 'Safari Dakwah Isra Miraj', 'date' => '2025-01-31'], 2);
    GalleryAlbum::factory()->create(['title' => 'Album draf']);
    GalleryAlbum::factory()->published()->create(['title' => 'Album tanpa foto']);
    GalleryVideo::factory()->published()->create(['title' => 'Kajian Tajwid', 'youtube_id' => 'abcdefghijk', 'youtube_url' => 'https://youtu.be/abcdefghijk']);
    GalleryVideo::factory()->create(['title' => 'Video draf']);
    $article = Article::factory()->published()->create(['title' => 'Semarak Maulid']);

    $this->get(route('galeri'))
        ->assertOk()
        ->assertSee('Safari Dakwah Isra Miraj')
        ->assertSee('2 foto')
        ->assertSee('31 Januari 2025')
        ->assertSee('href="'.route('galeri.album', $album).'"', false)
        ->assertDontSee('Album draf')
        ->assertDontSee('Album tanpa foto')
        ->assertSee('Kajian Tajwid')
        ->assertSee('https://i.ytimg.com/vi/abcdefghijk/hqdefault.jpg', false)
        ->assertDontSee('Video draf')
        ->assertSee('href="'.route('galeri.artikel', $article).'"', false)
        ->assertDontSee('Konten demo');
});

test('the gallery can be filtered by category', function () {
    $hariBesar = GalleryCategory::factory()->create(['name' => 'Hari besar Islam']);
    $kajian = GalleryCategory::factory()->create(['name' => 'Kajian']);
    GalleryCategory::factory()->create(['name' => 'Kategori kosong']);
    albumWithPhotos(['title' => 'Maulid Nabi', 'category_id' => $hariBesar->id]);
    albumWithPhotos(['title' => 'Kajian Rabu', 'category_id' => $kajian->id]);

    $this->get(route('galeri', ['kategori' => $hariBesar->slug]))
        ->assertOk()
        ->assertSee('Maulid Nabi')
        ->assertDontSee('Kajian Rabu')
        ->assertDontSee('Kategori kosong');
});

test('the gallery page renders before any content exists', function () {
    $this->get(route('galeri'))
        ->assertOk()
        ->assertSee('Dokumentasi kegiatan sedang disiapkan pengurus.')
        ->assertDontSee('Video kajian');
});

test('the youtube channel link comes from the contact settings', function () {
    ContactSetting::factory()->create(['youtube_url' => 'https://www.youtube.com/@nurulhaqq']);

    $this->get(route('galeri'))
        ->assertOk()
        ->assertSee('href="https://www.youtube.com/@nurulhaqq"', false)
        ->assertSee('Kunjungi kanal');
});

test('an album page shows its photos with captions', function () {
    $album = GalleryAlbum::factory()->published()->create(['title' => 'Silaturahmi Pengurus']);
    $media = Media::factory()->create(['path' => 'galeri/1/foto-bersama.jpg']);
    GalleryPhoto::factory()->for($album, 'album')->create(['image_id' => $media->id, 'caption' => 'Foto bersama pengurus']);
    albumWithPhotos(['title' => 'Album lain']);

    $this->get(route('galeri.album', $album))
        ->assertOk()
        ->assertSee('Silaturahmi Pengurus')
        ->assertSee('storage/galeri/1/foto-bersama.jpg', false)
        ->assertSee('Foto bersama pengurus')
        ->assertSee('Album lain');
});

test('draft albums and articles are not reachable', function () {
    $album = GalleryAlbum::factory()->create();
    $article = Article::factory()->create();

    $this->get(route('galeri.album', $album))->assertNotFound();
    $this->get(route('galeri.artikel', $article))->assertNotFound();
});

test('an article page shows its paragraphs', function () {
    $article = Article::factory()->published()->create([
        'title' => 'Semarak Maulid Nabi',
        'author' => 'Takmir Masjid',
        'excerpt' => null,
        'body' => "Paragraf pembuka <b>tebal</b>.\n\nParagraf penutup.",
    ]);

    $this->get(route('galeri.artikel', $article))
        ->assertOk()
        ->assertSee('Semarak Maulid Nabi')
        ->assertSee('Takmir Masjid')
        ->assertSee('<p>Paragraf pembuka &lt;b&gt;tebal&lt;/b&gt;.</p>', false)
        ->assertSee('<p>Paragraf penutup.</p>', false);
});

test('beranda highlights the latest albums once four albums have photos', function () {
    foreach (['Album satu', 'Album dua', 'Album tiga', 'Album empat'] as $index => $title) {
        albumWithPhotos(['title' => $title, 'date' => '2026-0'.($index + 1).'-01'], 2);
    }

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Album empat')
        ->assertSee('Album satu')
        ->assertSee('+4')
        ->assertDontSee('images/beranda/galeri-prasasti-peresmian.jpg', false);
});

test('beranda keeps the bundled gallery photos until enough albums exist', function () {
    albumWithPhotos(['title' => 'Album satu-satunya']);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('images/beranda/galeri-prasasti-peresmian.jpg', false)
        ->assertDontSee('Album satu-satunya');
});

function publishedVideos(int $count): void
{
    foreach (range(1, $count) as $index) {
        GalleryVideo::factory()->published()->create([
            'title' => sprintf('Video ke-%02d', $index),
            'date' => now()->subDays($index)->toDateString(),
        ]);
    }
}

test('videos are not limited: the first six show with a load more link', function () {
    publishedVideos(8);

    $response = $this->get(route('galeri'))->assertOk();

    $response->assertSee('Video ke-01')->assertSee('Video ke-06')->assertDontSee('Video ke-07')
        ->assertSee('Muat lebih banyak video')
        ->assertSee('video=12#video', false);
});

test('the load more link works without javascript', function () {
    publishedVideos(8);

    $this->get(route('galeri', ['video' => 12]))
        ->assertOk()
        ->assertSee('Video ke-08')
        ->assertDontSee('Muat lebih banyak video');
});

test('load more returns the next videos until all are shown', function () {
    publishedVideos(14);

    $first = $this->getJson(route('galeri', ['partial' => 'video', 'dari' => 6]))
        ->assertOk()
        ->assertHeader('Cache-Control', 'no-store, private')
        ->assertJsonPath('count', 6);

    expect($first->json('html'))->toContain('Video ke-07')->toContain('Video ke-12')->not->toContain('Video ke-06')
        ->and($first->json('next'))->toContain('video=18')
        ->and($first->json('url'))->toContain('video=12');

    $last = $this->getJson(route('galeri', ['partial' => 'video', 'dari' => 12]))->assertOk();

    expect($last->json('count'))->toBe(2)
        ->and($last->json('html'))->toContain('Video ke-14')
        ->and($last->json('next'))->toBeNull();
});

test('load more keeps the category filter', function () {
    $kajian = GalleryCategory::factory()->create(['name' => 'Kajian']);
    $lain = GalleryCategory::factory()->create(['name' => 'Lain']);
    GalleryVideo::factory()->count(7)->published()->create(['category_id' => $kajian->id]);
    GalleryVideo::factory()->published()->create(['category_id' => $lain->id, 'title' => 'Video kategori lain']);

    $response = $this->getJson(route('galeri', ['kategori' => $kajian->slug, 'partial' => 'video', 'dari' => 6]));

    expect($response->json('count'))->toBe(1)
        ->and($response->json('html'))->not->toContain('Video kategori lain')
        ->and($response->json('url'))->toContain('kategori='.$kajian->slug);
});

test('articles are not limited and can be loaded four at a time', function () {
    foreach (range(1, 6) as $index) {
        Article::factory()->published()->create([
            'title' => sprintf('Artikel ke-%02d', $index),
            'published_at' => now()->subDays($index),
        ]);
    }

    $this->get(route('galeri'))
        ->assertSee('Artikel ke-04')->assertDontSee('Artikel ke-05')
        ->assertSee('Muat lebih banyak artikel');

    $more = $this->getJson(route('galeri', ['partial' => 'artikel', 'dari' => 4]));

    expect($more->json('count'))->toBe(2)
        ->and($more->json('html'))->toContain('Artikel ke-06')
        ->and($more->json('next'))->toBeNull();
});

test('odd load more values never break the page', function () {
    publishedVideos(3);

    foreach (['?video=abc', '?video=-5', '?video=999999', '?video[]=1', '?partial=video&dari=abc', '?partial=video&dari[]=1', '?partial=lain'] as $query) {
        expect($this->get(route('galeri').$query)->status())->toBeLessThan(500, $query);
    }
});

test('album pagination uses the site design in indonesian', function () {
    foreach (range(1, 11) as $index) {
        albumWithPhotos(['title' => 'Album '.$index]);
    }

    $this->get(route('galeri', ['halaman' => 2]))
        ->assertOk()
        ->assertSee('Menampilkan')
        ->assertSee('dari <strong>11</strong> album', false)
        ->assertSee('aria-current="page"', false)
        ->assertSee('Sebelumnya')
        ->assertDontSee('Showing')
        ->assertDontSee('results');
});
