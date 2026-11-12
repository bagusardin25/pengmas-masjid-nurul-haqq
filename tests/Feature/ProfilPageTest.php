<?php

use App\Enums\ProfileIcon;
use App\Models\AboutPage;
use App\Models\AboutRole;
use App\Models\AboutStat;
use App\Models\Facility;
use App\Models\OrganizationMember;

test('profil page shows content from the database', function () {
    AboutPage::factory()->create([
        'hero_title' => 'Judul profil dari database',
        'hero_description' => 'Deskripsi pembuka dari database',
        'intro_title' => 'Pengantar dari database',
        'intro_description' => 'Isi pengantar dari database',
        'history_title' => 'Riwayat masjid',
        'history_content' => "Paragraf pertama.\n\nParagraf kedua.",
    ]);
    AboutStat::factory()->create(['value' => '999 m²', 'label' => 'Luas uji', 'icon' => ProfileIcon::Area]);
    AboutRole::factory()->create(['title' => 'Peran uji', 'description' => 'Deskripsi peran uji', 'icon' => ProfileIcon::Heart]);
    Facility::factory()->create(['name' => 'Fasilitas tampil']);
    OrganizationMember::factory()->create(['position' => 'Ketua', 'name' => 'Pengurus Uji', 'note' => 'Pengurus inti']);

    $this->get(route('profil'))
        ->assertOk()
        ->assertSee('Judul profil dari database')
        ->assertSee('Deskripsi pembuka dari database')
        ->assertSee('Pengantar dari database')
        ->assertSee('Isi pengantar dari database')
        ->assertSee('Riwayat masjid')
        ->assertSee('<p>Paragraf pertama.</p>', false)
        ->assertSee('<p>Paragraf kedua.</p>', false)
        ->assertSee('999 m²')
        ->assertSee('Luas uji')
        ->assertSee(ProfileIcon::Area->glyph())
        ->assertSee('Peran uji')
        ->assertSee('Fasilitas tampil')
        ->assertSee('Pengurus Uji')
        ->assertSee('Pengurus inti');
});

test('profil page hides inactive facilities and members and follows sort order', function () {
    AboutPage::factory()->create();
    Facility::factory()->create(['name' => 'Fasilitas kedua', 'sort_order' => 2]);
    Facility::factory()->create(['name' => 'Fasilitas pertama', 'sort_order' => 1]);
    Facility::factory()->create(['name' => 'Fasilitas tersembunyi', 'is_active' => false]);
    OrganizationMember::factory()->create(['name' => 'Pengurus nonaktif', 'is_active' => false]);

    $this->get(route('profil'))
        ->assertOk()
        ->assertSeeInOrder(['Fasilitas pertama', 'Fasilitas kedua'])
        ->assertDontSee('Fasilitas tersembunyi')
        ->assertDontSee('Pengurus nonaktif');
});

test('profil page still renders when no content has been saved yet', function () {
    $this->get(route('profil'))
        ->assertOk()
        ->assertSee('Profil Masjid Jami Nurul Haqq')
        ->assertSee('Sejarah masjid belum ditambahkan.')
        ->assertSee('Data kepengurusan belum ditambahkan.');
});

test('history content is escaped', function () {
    AboutPage::factory()->create(['history_content' => '<script>alert(1)</script>']);

    $this->get(route('profil'))
        ->assertOk()
        ->assertDontSee('<script>alert(1)</script>', false)
        ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false);
});
