<?php

use App\Models\AboutStat;
use App\Models\HomePage;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
    $this->user = User::factory()->create();
});

function heroPayload(array $overrides = []): array
{
    return [
        'hero_eyebrow' => 'MASJID JAMI · YAYASAN NURUL HAQQ',
        'hero_title' => "Masjid\nNurul Haqq",
        'hero_description' => 'Tempat sholat berjamaah lima waktu.',
        'hero_arabic_text' => 'إِنَّمَا يَعْمُرُ مَسَاجِدَ اللَّهِ',
        'hero_translation' => 'Sesungguhnya yang memakmurkan masjid-masjid Allah…',
        'hero_citation' => 'QS. AT-TAUBAH: 18',
        ...$overrides,
    ];
}

test('guests cannot open the beranda management page', function () {
    $this->get(route('dashboard.beranda'))->assertRedirect(route('login'));
    $this->put(route('dashboard.beranda.update'), heroPayload())->assertRedirect(route('login'));
});

test('the beranda management page shows the hero form and where other sections are managed', function () {
    HomePage::factory()->create(['hero_title' => 'Judul hero tersimpan']);
    AboutStat::factory()->count(4)->create();

    $this->actingAs($this->user)
        ->get(route('dashboard.beranda'))
        ->assertOk()
        ->assertSee('aria-current="page"', false)
        ->assertSee('Judul hero tersimpan')
        ->assertSee('Foto bawaan')
        ->assertSee(asset(HomePage::DEFAULT_HERO_IMAGE), false)
        ->assertSee('enctype="multipart/form-data"', false)
        ->assertSee('4 statistik')
        ->assertSee('href="'.route('dashboard.profil').'"', false)
        ->assertSee('href="'.route('dashboard.jadwal').'"', false)
        ->assertSee('href="'.route('dashboard.tpq').'"', false)
        ->assertSee('Menyusul');
});

test('hero text can be saved and the title is required', function () {
    $this->actingAs($this->user)
        ->put(route('dashboard.beranda.update'), heroPayload())
        ->assertRedirect(route('dashboard.beranda'))
        ->assertSessionHas('status');

    $hero = HomePage::current();

    expect($hero->hero_title)->toBe("Masjid\nNurul Haqq")
        ->and($hero->hero_citation)->toBe('QS. AT-TAUBAH: 18')
        ->and($hero->hero_image_id)->toBeNull();

    $this->actingAs($this->user)
        ->from(route('dashboard.beranda'))
        ->put(route('dashboard.beranda.update'), heroPayload(['hero_title' => '']))
        ->assertSessionHasErrors(['hero_title' => 'Judul wajib diisi.'], null, 'hero');
});

test('a hero photo can be uploaded, replaced, and removed without leaving files behind', function () {
    $this->actingAs($this->user)->put(route('dashboard.beranda.update'), heroPayload([
        'hero_image' => UploadedFile::fake()->createWithContent('masjid.png', testPng(60, 50)),
        'hero_image_alt' => 'Masjid dari depan',
    ]))->assertRedirect(route('dashboard.beranda'));

    $first = HomePage::current()->heroImage;

    expect($first->path)->toStartWith('beranda/')
        ->and($first->alt)->toBe('Masjid dari depan')
        ->and($first->width)->toBe(60);
    Storage::disk('public')->assertExists($first->path);

    $this->actingAs($this->user)->put(route('dashboard.beranda.update'), heroPayload([
        'hero_image' => UploadedFile::fake()->createWithContent('masjid-baru.png', testPng(80, 60)),
        'hero_image_alt' => 'Masjid saat senja',
    ]));

    $second = HomePage::current()->heroImage;

    expect($second->id)->not->toBe($first->id)
        ->and(Media::query()->whereKey($first->id)->exists())->toBeFalse();
    Storage::disk('public')->assertMissing($first->path);

    $this->actingAs($this->user)->put(route('dashboard.beranda.update'), heroPayload([
        'remove_hero_image' => '1',
    ]));

    expect(HomePage::current()->hero_image_id)->toBeNull()
        ->and(Media::query()->count())->toBe(0);
    Storage::disk('public')->assertMissing($second->path);
});

test('the hero photo description can be changed without uploading again', function () {
    $media = Media::factory()->create(['alt' => 'Lama']);
    HomePage::factory()->create(['hero_image_id' => $media->id]);

    $this->actingAs($this->user)->put(route('dashboard.beranda.update'), heroPayload([
        'hero_image_alt' => 'Pintu masuk masjid',
        'remove_hero_image' => '0',
    ]));

    expect($media->refresh()->alt)->toBe('Pintu masuk masjid')
        ->and(HomePage::current()->hero_image_id)->toBe($media->id);
});

test('the hero photo must be a supported image', function () {
    $this->actingAs($this->user)
        ->from(route('dashboard.beranda'))
        ->put(route('dashboard.beranda.update'), heroPayload([
            'hero_image' => UploadedFile::fake()->createWithContent('catatan.txt', 'bukan foto'),
        ]))
        ->assertSessionHasErrors(['hero_image' => 'Berkas harus berupa foto berformat JPG, PNG, atau WebP.'], null, 'hero');

    expect(HomePage::current())->toBeNull();
});
