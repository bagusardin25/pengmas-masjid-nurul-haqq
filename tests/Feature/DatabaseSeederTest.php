<?php

use App\Models\Article;
use App\Models\ContactPhone;
use App\Models\ContactSetting;
use App\Models\GalleryAlbum;
use App\Models\GalleryCategory;
use App\Models\GalleryPhoto;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;

test('the database seeder can run more than once', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);
    $this->seed(DatabaseSeeder::class);

    expect(User::query()->where('email', 'admin@gmail.com')->count())->toBe(1);
});

test('the seeder fills the gallery and contacts with real masjid data', function () {
    Storage::fake('public');

    $this->seed(DatabaseSeeder::class);
    $this->seed(DatabaseSeeder::class);

    $albums = GalleryAlbum::query()->published()->withCount('photos')->get();

    expect($albums)->toHaveCount(5)
        ->and($albums->every(fn ($album) => $album->photos_count > 0 && $album->cover_image_id !== null))->toBeTrue()
        ->and(GalleryPhoto::query()->count())->toBe(12)
        ->and(Article::query()->count())->toBe(0)
        ->and(ContactSetting::current()->email)->toBe('takmirmnh22@gmail.com')
        ->and(ContactPhone::query()->where('number', '0812-3456-7890')->exists())->toBeFalse()
        ->and(ContactPhone::query()->ordered()->first()->number)->toBe('0812-3028-185');

    Storage::disk('public')->assertExists('galeri/seed/masjid-tampak-depan-malam.jpg');

    $this->get(route('galeri'))->assertOk()->assertSee("Safari Dakwah Peringatan Isra Mi'raj 1446 H");
});

test('old demo categories are removed in a single seeder run', function () {
    Storage::fake('public');

    // Keadaan database lama: kategori demo dipakai album demo tanpa foto.
    $demo = GalleryCategory::query()->create(['name' => 'Kajian rutin', 'slug' => 'kajian-rutin', 'sort_order' => 1]);
    GalleryAlbum::query()->create(['title' => 'Kajian Rutin', 'slug' => 'kajian-rutin', 'category_id' => $demo->id, 'status' => 'published']);

    $this->seed(DatabaseSeeder::class);

    expect(GalleryCategory::query()->where('slug', 'kajian-rutin')->exists())->toBeFalse()
        ->and(GalleryCategory::query()->pluck('sort_order')->all())->toBe([1, 2, 3, 4, 5]);
});
