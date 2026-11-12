<?php

use App\Models\Media;
use App\Models\TpqDocumentation;
use App\Models\User;
use App\Services\MediaUploader;
use Database\Seeders\TpqPageSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
    $this->user = User::factory()->create();
});

test('a documentation photo can be uploaded', function () {
    $this->actingAs($this->user)
        ->post(route('dashboard.tpq.documentations.store'), [
            'image' => UploadedFile::fake()->createWithContent('santri-mengaji.png', testPng(40, 30)),
            'caption' => 'Santri belajar membaca Al-Qur\'an',
        ])
        ->assertRedirect(route('dashboard.tpq').'#dokumentasi')
        ->assertSessionHas('status', 'Foto dokumentasi diunggah.');

    $documentation = TpqDocumentation::query()->with('image')->firstOrFail();
    $media = $documentation->image;

    expect($documentation->caption)->toBe('Santri belajar membaca Al-Qur\'an')
        ->and($documentation->sort_order)->toBe(1)
        ->and($media->disk)->toBe('public')
        ->and($media->path)->toStartWith('tpq/')
        ->and($media->original_name)->toBe('santri-mengaji.png')
        ->and($media->alt)->toBe('Santri belajar membaca Al-Qur\'an')
        ->and($media->width)->toBe(40)
        ->and($media->height)->toBe(30)
        ->and($media->url())->toBe(asset('storage/'.$media->path));

    Storage::disk('public')->assertExists($media->path);
});

test('uploading requires a photo in a supported format and size', function () {
    $this->actingAs($this->user)
        ->post(route('dashboard.tpq.documentations.store'), ['caption' => 'Tanpa foto'])
        ->assertRedirect(route('dashboard.tpq').'#dokumentasi')
        ->assertSessionHasErrors(['image' => 'Pilih foto yang akan diunggah.'], null, 'documentation-new');

    $this->actingAs($this->user)
        ->post(route('dashboard.tpq.documentations.store'), [
            'image' => UploadedFile::fake()->createWithContent('catatan.txt', 'bukan foto'),
        ])
        ->assertSessionHasErrors(['image' => 'Berkas harus berupa foto berformat JPG, PNG, atau WebP.'], null, 'documentation-new');

    $this->actingAs($this->user)
        ->post(route('dashboard.tpq.documentations.store'), [
            'image' => UploadedFile::fake()->create('besar.jpg', MediaUploader::MAX_KILOBYTES + 500, 'image/jpeg'),
        ])
        ->assertSessionHasErrors(['image' => 'Ukuran foto maksimal 2 MB.'], null, 'documentation-new');

    expect(TpqDocumentation::query()->count())->toBe(0)
        ->and(Media::query()->count())->toBe(0);
});

test('a documentation caption and order can be updated without uploading again', function () {
    $this->actingAs($this->user)->post(route('dashboard.tpq.documentations.store'), [
        'image' => UploadedFile::fake()->createWithContent('foto.png', testPng(4, 3)),
    ]);

    $documentation = TpqDocumentation::query()->firstOrFail();

    $this->actingAs($this->user)
        ->put(route('dashboard.tpq.documentations.update', $documentation), [
            'caption' => 'Tadarus bersama',
            'sort_order' => 5,
        ])
        ->assertRedirect(route('dashboard.tpq').'#dokumentasi');

    $documentation->refresh();

    expect($documentation->caption)->toBe('Tadarus bersama')
        ->and($documentation->sort_order)->toBe(5)
        ->and($documentation->image->alt)->toBe('Tadarus bersama');
});

test('deleting a documentation photo removes the file and its media row', function () {
    $this->actingAs($this->user)->post(route('dashboard.tpq.documentations.store'), [
        'image' => UploadedFile::fake()->createWithContent('foto.png', testPng(4, 3)),
    ]);

    $documentation = TpqDocumentation::query()->with('image')->firstOrFail();
    $path = $documentation->image->path;

    $this->actingAs($this->user)
        ->delete(route('dashboard.tpq.documentations.destroy', $documentation))
        ->assertRedirect(route('dashboard.tpq').'#dokumentasi');

    expect(TpqDocumentation::query()->count())->toBe(0)
        ->and(Media::query()->count())->toBe(0);

    Storage::disk('public')->assertMissing($path);
});

test('the tpq management page lists documentation photos and an upload form', function () {
    $this->actingAs($this->user)->post(route('dashboard.tpq.documentations.store'), [
        'image' => UploadedFile::fake()->createWithContent('foto.png', testPng(4, 3)),
        'caption' => 'Foto tersimpan',
    ]);

    $media = Media::query()->firstOrFail();

    $this->actingAs($this->user)
        ->get(route('dashboard.tpq'))
        ->assertOk()
        ->assertSee('Foto tersimpan')
        ->assertSee($media->url())
        ->assertSee('enctype="multipart/form-data"', false)
        ->assertSee('x-data="photoUpload()"', false);
});

test('the public tpq page shows documentation photos only when there are any', function () {
    Http::fake();

    $this->get(route('tpq'))->assertOk()->assertDontSee('Kegiatan belajar santri');

    $this->actingAs($this->user)->post(route('dashboard.tpq.documentations.store'), [
        'image' => UploadedFile::fake()->createWithContent('foto.png', testPng(40, 30)),
        'caption' => 'Santri menulis Al-Qur\'an',
    ]);

    $media = Media::query()->firstOrFail();

    $this->get(route('tpq'))
        ->assertOk()
        ->assertSee('Kegiatan belajar santri')
        ->assertSee('src="'.$media->url().'"', false)
        ->assertSee('width="40" height="30"', false)
        ->assertSee('<figcaption>Santri menulis Al-Qur&#039;an</figcaption>', false);
});

test('the seeder imports the real tpq board photo once', function () {
    $this->seed(TpqPageSeeder::class);
    $this->seed(TpqPageSeeder::class);

    $documentation = TpqDocumentation::query()->with('image')->sole();

    expect($documentation->caption)->toBe('Papan nama serta visi dan misi TPQ Nurul Haqq')
        ->and($documentation->image->path)->toBe('tpq/papan-visi-misi-tpq.jpg')
        ->and($documentation->image->width)->toBe(1200)
        ->and(Media::query()->count())->toBe(1);

    Storage::disk('public')->assertExists('tpq/papan-visi-misi-tpq.jpg');
});
