<?php

use App\Enums\PublicationStatus;
use App\Models\Article;
use App\Models\GalleryAlbum;
use App\Models\GalleryCategory;
use App\Models\GalleryPhoto;
use App\Models\GalleryVideo;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->user = User::factory()->create();
});

function galleryPhotoUpload(string $name = 'foto.png'): UploadedFile
{
    return UploadedFile::fake()->createWithContent($name, testPng(16, 12));
}

test('guests cannot open the gallery management pages', function () {
    $album = GalleryAlbum::factory()->create();

    $this->get(route('dashboard.galeri'))->assertRedirect(route('login'));
    $this->get(route('dashboard.galeri.albums.edit', $album))->assertRedirect(route('login'));
    $this->post(route('dashboard.galeri.albums.store'), ['title' => 'x'])->assertRedirect(route('login'));
    $this->post(route('dashboard.galeri.albums.photos.store', $album))->assertRedirect(route('login'));
    $this->post(route('dashboard.galeri.videos.store'), ['title' => 'x'])->assertRedirect(route('login'));
    $this->post(route('dashboard.galeri.articles.store'), ['title' => 'x'])->assertRedirect(route('login'));
});

test('the gallery management page lists albums, videos, articles, and categories', function () {
    $category = GalleryCategory::factory()->create(['name' => 'Hari besar Islam']);
    GalleryAlbum::factory()->published()->create(['title' => 'Album Maulid', 'category_id' => $category->id]);
    GalleryVideo::factory()->create(['title' => 'Video kajian tersimpan']);
    Article::factory()->create(['title' => 'Artikel tersimpan']);

    $this->actingAs($this->user)
        ->get(route('dashboard.galeri'))
        ->assertOk()
        ->assertSee('href="'.route('dashboard.galeri').'"', false)
        ->assertSee('Album Maulid')
        ->assertSee('Video kajian tersimpan')
        ->assertSee('Artikel tersimpan')
        ->assertSee('Hari besar Islam');
});

test('the gallery management page works before any content exists', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.galeri'))
        ->assertOk()
        ->assertSee('Belum ada album.')
        ->assertSee('Belum ada video.')
        ->assertSee('Belum ada artikel.')
        ->assertSee('Belum ada kategori.');
});

test('creating an album leads to its photo page', function () {
    $category = GalleryCategory::factory()->create();

    $response = $this->actingAs($this->user)->post(route('dashboard.galeri.albums.store'), [
        'title' => 'Peringatan Maulid Nabi',
        'category_id' => $category->id,
        'date' => '2026-09-05',
        'status' => 'published',
    ]);

    $album = GalleryAlbum::query()->sole();

    $response->assertRedirect(route('dashboard.galeri.albums.edit', $album).'#foto');

    expect($album->slug)->toBe('peringatan-maulid-nabi')
        ->and($album->category_id)->toBe($category->id)
        ->and($album->status)->toBe(PublicationStatus::Published);

    $this->actingAs($this->user)
        ->get(route('dashboard.galeri.albums.edit', $album))
        ->assertOk()
        ->assertSee('Peringatan Maulid Nabi')
        ->assertSee('Album ini belum berisi foto.');
});

test('albums are validated with indonesian messages', function () {
    $this->actingAs($this->user)
        ->post(route('dashboard.galeri.albums.store'), ['title' => '', 'category_id' => 999, 'status' => 'published'])
        ->assertRedirect(route('dashboard.galeri').'#album')
        ->assertSessionHasErrors([
            'title' => 'Judul album wajib diisi.',
            'category_id' => 'Pilihan kategori tidak valid.',
        ], null, 'album-new');

    $album = GalleryAlbum::factory()->create();

    $this->actingAs($this->user)
        ->put(route('dashboard.galeri.albums.update', $album), ['title' => '', 'status' => 'draft'])
        ->assertRedirect(route('dashboard.galeri.albums.edit', $album).'#detail')
        ->assertSessionHasErrors(['title'], null, 'album-'.$album->id);
});

test('several photos can be uploaded to an album at once', function () {
    Storage::fake('public');
    $album = GalleryAlbum::factory()->create(['title' => 'Kerja bakti']);

    $this->actingAs($this->user)
        ->post(route('dashboard.galeri.albums.photos.store', $album), [
            'photos' => [galleryPhotoUpload('a.png'), galleryPhotoUpload('b.png'), galleryPhotoUpload('c.png')],
        ])
        ->assertRedirect(route('dashboard.galeri.albums.edit', $album).'#foto')
        ->assertSessionHas('status', '3 foto diunggah ke album.');

    $photos = $album->photos()->with('image')->get();

    expect($photos)->toHaveCount(3)
        ->and($photos->pluck('sort_order')->all())->toBe([1, 2, 3])
        ->and($photos->first()->image->alt)->toBe('Kerja bakti')
        ->and($photos->first()->image->path)->toStartWith('galeri/'.$album->id.'/');

    Storage::disk('public')->assertExists($photos->first()->image->path);
    expect($album->fresh()->load(['coverImage', 'firstPhoto.image'])->cover()->id)->toBe($photos->first()->image_id);
});

test('photo uploads are limited and validated', function () {
    Storage::fake('public');
    $album = GalleryAlbum::factory()->create();

    $this->actingAs($this->user)
        ->post(route('dashboard.galeri.albums.photos.store', $album), [])
        ->assertSessionHasErrors(['photos' => 'Pilih minimal satu foto.'], null, 'photo-new');

    $this->actingAs($this->user)
        ->post(route('dashboard.galeri.albums.photos.store', $album), [
            'photos' => array_map(fn ($i) => galleryPhotoUpload("f{$i}.png"), range(1, 11)),
        ])
        ->assertSessionHasErrors(['photos' => 'Maksimal 10 foto sekali unggah. Unggah sisanya setelah ini.'], null, 'photo-new');

    $this->actingAs($this->user)
        ->post(route('dashboard.galeri.albums.photos.store', $album), [
            'photos' => [UploadedFile::fake()->createWithContent('laporan.pdf', testPdf())],
        ])
        ->assertSessionHasErrors(['photos.0' => 'Berkas harus berupa foto berformat JPG, PNG, atau WebP.'], null, 'photo-new');

    expect(GalleryPhoto::query()->count())->toBe(0);
});

test('a photo caption can be edited and a photo can become the album cover', function () {
    $album = GalleryAlbum::factory()->create();
    $first = GalleryPhoto::factory()->for($album, 'album')->create(['sort_order' => 1]);
    $second = GalleryPhoto::factory()->for($album, 'album')->create(['sort_order' => 2]);

    $this->actingAs($this->user)
        ->put(route('dashboard.galeri.albums.photos.update', [$album, $second]), ['caption' => 'Pemotongan tumpeng', 'sort_order' => ''])
        ->assertRedirect(route('dashboard.galeri.albums.edit', $album).'#foto');

    expect($second->fresh()->caption)->toBe('Pemotongan tumpeng')
        ->and($second->fresh()->sort_order)->toBe(2)
        ->and($second->image->fresh()->alt)->toBe('Pemotongan tumpeng');

    $this->actingAs($this->user)
        ->put(route('dashboard.galeri.albums.photos.cover', [$album, $second]))
        ->assertRedirect(route('dashboard.galeri.albums.edit', $album).'#foto');

    expect($album->fresh()->cover_image_id)->toBe($second->image_id);
});

test('photos cannot be changed through another album', function () {
    $album = GalleryAlbum::factory()->create();
    $otherPhoto = GalleryPhoto::factory()->create();

    $this->actingAs($this->user)
        ->delete(route('dashboard.galeri.albums.photos.destroy', [$album, $otherPhoto]))
        ->assertNotFound();

    expect($otherPhoto->fresh())->not->toBeNull();
});

test('deleting a photo removes its file and clears the cover', function () {
    Storage::fake('public');
    $album = GalleryAlbum::factory()->create();

    $this->actingAs($this->user)->post(route('dashboard.galeri.albums.photos.store', $album), ['photos' => [galleryPhotoUpload()]]);

    $photo = $album->photos()->with('image')->sole();
    $album->update(['cover_image_id' => $photo->image_id]);

    $this->actingAs($this->user)
        ->delete(route('dashboard.galeri.albums.photos.destroy', [$album, $photo]))
        ->assertRedirect(route('dashboard.galeri.albums.edit', $album).'#foto');

    Storage::disk('public')->assertMissing($photo->image->path);
    expect(Media::query()->count())->toBe(0)
        ->and($album->fresh()->cover_image_id)->toBeNull();
});

test('deleting an album removes its photos and files', function () {
    Storage::fake('public');
    $album = GalleryAlbum::factory()->create();

    $this->actingAs($this->user)->post(route('dashboard.galeri.albums.photos.store', $album), [
        'photos' => [galleryPhotoUpload('a.png'), galleryPhotoUpload('b.png')],
    ]);

    $paths = Media::query()->pluck('path');

    $this->actingAs($this->user)
        ->delete(route('dashboard.galeri.albums.destroy', $album))
        ->assertRedirect(route('dashboard.galeri').'#album');

    expect(GalleryAlbum::query()->count())->toBe(0)
        ->and(GalleryPhoto::query()->count())->toBe(0)
        ->and(Media::query()->count())->toBe(0);

    $paths->each(fn ($path) => Storage::disk('public')->assertMissing($path));
});

test('videos are saved from a youtube link', function () {
    $this->actingAs($this->user)
        ->post(route('dashboard.galeri.videos.store'), [
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ&t=10',
            'title' => 'Kajian Riyadhus Shalihin',
            'duration' => '1:05:30',
            'status' => 'published',
        ])
        ->assertRedirect(route('dashboard.galeri').'#video');

    $video = GalleryVideo::query()->sole();

    expect($video->youtube_id)->toBe('dQw4w9WgXcQ')
        ->and($video->thumbnailUrl())->toBe('https://i.ytimg.com/vi/dQw4w9WgXcQ/hqdefault.jpg');

    $this->actingAs($this->user)
        ->put(route('dashboard.galeri.videos.update', $video), [
            'youtube_url' => 'https://youtu.be/abcdefghijk',
            'title' => 'Kajian Riyadhus Shalihin',
            'status' => 'draft',
        ]);

    expect($video->fresh()->youtube_id)->toBe('abcdefghijk');
});

test('videos are validated with indonesian messages', function () {
    $this->actingAs($this->user)
        ->post(route('dashboard.galeri.videos.store'), [
            'youtube_url' => 'https://vimeo.com/123',
            'title' => '',
            'duration' => 'setengah jam',
            'status' => 'published',
        ])
        ->assertRedirect(route('dashboard.galeri').'#video')
        ->assertSessionHasErrors([
            'youtube_url' => 'Tautan YouTube tidak dikenali. Salin tautan dari tombol Bagikan di YouTube, mis. https://youtu.be/abc123XYZ_0.',
            'title' => 'Judul video wajib diisi.',
            'duration' => 'Durasi ditulis seperti di YouTube, mis. 24:18 atau 1:05:30.',
        ], null, 'video-new');
});

test('articles can be written with a cover photo, published, and deleted', function () {
    Storage::fake('public');

    $this->actingAs($this->user)
        ->post(route('dashboard.galeri.articles.store'), [
            'title' => 'Semarak Maulid Nabi',
            'author' => 'Takmir',
            'body' => "Paragraf pertama.\n\nParagraf kedua.",
            'status' => 'published',
            'cover_image' => galleryPhotoUpload('sampul.png'),
        ])
        ->assertRedirect(route('dashboard.galeri').'#artikel');

    $article = Article::query()->sole();

    expect($article->published_at)->not->toBeNull()
        ->and($article->paragraphs())->toBe(['Paragraf pertama.', 'Paragraf kedua.'])
        ->and($article->coverImage->path)->toStartWith('artikel/');

    $coverPath = $article->coverImage->path;

    $this->actingAs($this->user)
        ->put(route('dashboard.galeri.articles.update', $article), [
            'title' => 'Semarak Maulid Nabi',
            'body' => 'Isi baru.',
            'status' => 'published',
            'published_at' => '2026-09-05',
            'remove_cover_image' => '1',
        ]);

    expect($article->fresh()->cover_image_id)->toBeNull()
        ->and($article->fresh()->published_at->toDateString())->toBe('2026-09-05');
    Storage::disk('public')->assertMissing($coverPath);

    $this->actingAs($this->user)
        ->delete(route('dashboard.galeri.articles.destroy', $article))
        ->assertRedirect(route('dashboard.galeri').'#artikel');

    expect(Article::query()->count())->toBe(0);
});

test('articles are validated in their own error bag', function () {
    $this->actingAs($this->user)
        ->post(route('dashboard.galeri.articles.store'), ['title' => '', 'body' => '', 'status' => 'published'])
        ->assertSessionHasErrors([
            'title' => 'Judul artikel wajib diisi.',
            'body' => 'Isi artikel wajib diisi.',
        ], null, 'article-new');
});

test('categories can be managed and deleting one keeps its albums', function () {
    $this->actingAs($this->user)
        ->post(route('dashboard.galeri.categories.store'), ['name' => 'Ramadhan'])
        ->assertRedirect(route('dashboard.galeri').'#kategori');

    $category = GalleryCategory::query()->sole();
    $album = GalleryAlbum::factory()->create(['category_id' => $category->id]);

    $this->actingAs($this->user)
        ->put(route('dashboard.galeri.categories.update', $category), ['name' => 'Kegiatan Ramadhan'])
        ->assertRedirect(route('dashboard.galeri').'#kategori');

    expect($category->fresh()->name)->toBe('Kegiatan Ramadhan')
        ->and($category->fresh()->slug)->toBe('ramadhan');

    $this->actingAs($this->user)->delete(route('dashboard.galeri.categories.destroy', $category));

    expect(GalleryCategory::query()->count())->toBe(0)
        ->and($album->fresh()->category_id)->toBeNull();
});

test('long gallery lists show five items first with a load more button', function () {
    GalleryVideo::factory()->count(7)->sequence(fn ($sequence) => ['title' => 'Video ke-'.($sequence->index + 1)])->create();

    $response = $this->actingAs($this->user)->get(route('dashboard.galeri'));

    $response->assertOk()
        ->assertSee("loadMore({ key: 'galeri-video', total: 7, step: 5, min: 0 })", false)
        ->assertSee('Tampilkan ${Math.min(step, remaining)} video lagi', false);

    // Item ke-6 dst. disembunyikan sejak awal (x-cloak) agar tidak berkedip sebelum Alpine aktif.
    expect($response->getContent())
        ->toMatch('/isShown\(6\)"\s+x-cloak/')
        ->not->toMatch('/isShown\(4\)"\s+x-cloak/');
});

test('an item with validation errors stays visible beyond the first five', function () {
    $videos = GalleryVideo::factory()->count(8)->create();
    $eighth = GalleryVideo::query()->latestFirst()->get()->last();

    $this->actingAs($this->user)
        ->from(route('dashboard.galeri'))
        ->put(route('dashboard.galeri.videos.update', $eighth), ['title' => '', 'youtube_url' => 'x', 'status' => 'draft']);

    $this->actingAs($this->user)
        ->get(route('dashboard.galeri'))
        ->assertSee("loadMore({ key: 'galeri-video', total: 8, step: 5, min: 8 })", false);
});
