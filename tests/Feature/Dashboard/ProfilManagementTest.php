<?php

use App\Enums\ProfileIcon;
use App\Models\AboutPage;
use App\Models\AboutRole;
use App\Models\AboutStat;
use App\Models\Facility;
use App\Models\OrganizationMember;
use App\Models\User;
use Database\Seeders\AboutPageSeeder;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('guests cannot open the profil management page', function () {
    $this->get(route('dashboard.profil'))->assertRedirect(route('login'));
    $this->put(route('dashboard.profil.update'), ['hero_title' => 'x'])->assertRedirect(route('login'));
    $this->post(route('dashboard.profil.facilities.store'), ['name' => 'x'])->assertRedirect(route('login'));
});

test('the profil management page lists current content', function () {
    AboutPage::factory()->create(['hero_title' => 'Judul tersimpan']);
    AboutStat::factory()->create(['label' => 'Statistik tersimpan']);
    AboutRole::factory()->create(['title' => 'Peran tersimpan']);
    Facility::factory()->create(['name' => 'Fasilitas tersimpan', 'is_active' => false]);
    OrganizationMember::factory()->create(['name' => 'Pengurus tersimpan']);

    $this->actingAs($this->user)
        ->get(route('dashboard.profil'))
        ->assertOk()
        ->assertSee('href="'.route('dashboard.profil').'"', false)
        ->assertSee('Judul tersimpan')
        ->assertSee('Statistik tersimpan')
        ->assertSee('Peran tersimpan')
        ->assertSee('Fasilitas tersimpan')
        ->assertSee('Pengurus tersimpan');
});

test('the profil management page works before any content exists', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.profil'))
        ->assertOk()
        ->assertSee('Belum ada statistik.')
        ->assertSee('Belum ada pengurus.');
});

test('page text can be saved, creating the row when missing', function () {
    $this->actingAs($this->user)
        ->put(route('dashboard.profil.update'), [
            'hero_title' => 'Judul baru',
            'hero_description' => 'Deskripsi baru',
            'intro_title' => 'Pengantar baru',
            'intro_description' => 'Isi pengantar baru',
            'history_title' => '',
            'history_content' => 'Sejarah baru',
        ])
        ->assertRedirect(route('dashboard.profil'))
        ->assertSessionHas('status');

    $about = AboutPage::current();

    expect($about->hero_title)->toBe('Judul baru')
        ->and($about->intro_description)->toBe('Isi pengantar baru')
        ->and($about->history_title)->toBe('Sejarah')
        ->and($about->history_content)->toBe('Sejarah baru');

    $this->actingAs($this->user)
        ->put(route('dashboard.profil.update'), ['hero_title' => 'Judul diubah'])
        ->assertRedirect(route('dashboard.profil'));

    expect(AboutPage::query()->count())->toBe(1)
        ->and(AboutPage::current()->hero_title)->toBe('Judul diubah');
});

test('page text requires a title and reports errors in its own bag', function () {
    $this->actingAs($this->user)
        ->from(route('dashboard.profil'))
        ->put(route('dashboard.profil.update'), ['hero_title' => ''])
        ->assertRedirect(route('dashboard.profil'))
        ->assertSessionHasErrors(['hero_title'], null, 'about');

    expect(AboutPage::current())->toBeNull();
});

test('stats can be created, updated, and deleted', function () {
    AboutStat::factory()->create(['sort_order' => 4]);

    $this->actingAs($this->user)
        ->post(route('dashboard.profil.stats.store'), [
            'value' => '600',
            'label' => 'Daya tampung',
            'icon' => ProfileIcon::People->value,
        ])
        ->assertRedirect(route('dashboard.profil').'#statistik');

    $stat = AboutStat::query()->where('label', 'Daya tampung')->firstOrFail();

    expect($stat->icon)->toBe(ProfileIcon::People)
        ->and($stat->sort_order)->toBe(5);

    $this->actingAs($this->user)
        ->put(route('dashboard.profil.stats.update', $stat), [
            'value' => '650',
            'label' => 'Daya tampung jamaah',
            'icon' => '',
            'sort_order' => '',
        ])
        ->assertRedirect(route('dashboard.profil').'#statistik');

    $stat->refresh();

    expect($stat->value)->toBe('650')
        ->and($stat->icon)->toBeNull()
        ->and($stat->sort_order)->toBe(5);

    $this->actingAs($this->user)
        ->delete(route('dashboard.profil.stats.destroy', $stat))
        ->assertRedirect(route('dashboard.profil').'#statistik');

    expect(AboutStat::query()->whereKey($stat->id)->exists())->toBeFalse();
});

test('stat validation errors go to the bag of the submitted form', function () {
    $stat = AboutStat::factory()->create();

    $this->actingAs($this->user)
        ->put(route('dashboard.profil.stats.update', $stat), ['value' => '', 'label' => 'x', 'icon' => 'tidak-ada'])
        ->assertRedirect(route('dashboard.profil').'#statistik')
        ->assertSessionHasErrors(['value', 'icon'], null, 'stat-'.$stat->id);

    $this->actingAs($this->user)
        ->post(route('dashboard.profil.stats.store'), [])
        ->assertSessionHasErrors(['value', 'label'], null, 'stat-new');
});

test('roles can be created, updated, and deleted', function () {
    $this->actingAs($this->user)
        ->post(route('dashboard.profil.roles.store'), [
            'title' => 'Pendidikan',
            'description' => 'Program TPQ',
            'icon' => ProfileIcon::Quran->value,
        ])
        ->assertRedirect(route('dashboard.profil').'#peran');

    $role = AboutRole::query()->where('title', 'Pendidikan')->firstOrFail();

    $this->actingAs($this->user)
        ->put(route('dashboard.profil.roles.update', $role), [
            'title' => 'Pendidikan anak',
            'description' => '',
            'icon' => ProfileIcon::Book->value,
            'sort_order' => 3,
        ]);

    $role->refresh();

    expect($role->title)->toBe('Pendidikan anak')
        ->and($role->description)->toBeNull()
        ->and($role->icon)->toBe(ProfileIcon::Book)
        ->and($role->sort_order)->toBe(3);

    $this->actingAs($this->user)->delete(route('dashboard.profil.roles.destroy', $role));

    expect(AboutRole::query()->count())->toBe(0);
});

test('facilities can be created, hidden, and deleted', function () {
    $this->actingAs($this->user)
        ->post(route('dashboard.profil.facilities.store'), ['name' => 'Area parkir', 'is_active' => '1'])
        ->assertRedirect(route('dashboard.profil').'#fasilitas');

    $facility = Facility::query()->where('name', 'Area parkir')->firstOrFail();

    expect($facility->is_active)->toBeTrue()
        ->and($facility->sort_order)->toBe(1);

    $this->actingAs($this->user)
        ->put(route('dashboard.profil.facilities.update', $facility), ['name' => 'Area parkir luas', 'is_active' => '0']);

    $facility->refresh();

    expect($facility->name)->toBe('Area parkir luas')
        ->and($facility->is_active)->toBeFalse();

    $this->actingAs($this->user)->delete(route('dashboard.profil.facilities.destroy', $facility));

    expect(Facility::query()->count())->toBe(0);
});

test('members can be created, updated, and deleted', function () {
    $this->actingAs($this->user)
        ->post(route('dashboard.profil.members.store'), [
            'position' => 'Bendahara',
            'name' => 'Muhammad Ridwan',
            'note' => 'Pengurus masjid',
            'is_active' => '1',
        ])
        ->assertRedirect(route('dashboard.profil').'#pengurus');

    $member = OrganizationMember::query()->where('name', 'Muhammad Ridwan')->firstOrFail();

    $this->actingAs($this->user)
        ->put(route('dashboard.profil.members.update', $member), [
            'position' => 'Bendahara',
            'name' => 'Muhammad Ridwan, S.E.',
            'note' => '',
            'is_active' => '0',
        ]);

    $member->refresh();

    expect($member->name)->toBe('Muhammad Ridwan, S.E.')
        ->and($member->note)->toBeNull()
        ->and($member->is_active)->toBeFalse();

    $this->actingAs($this->user)
        ->post(route('dashboard.profil.members.store'), ['position' => '', 'name' => '', 'note' => str_repeat('a', 256)])
        ->assertSessionHasErrors([
            'position' => 'Jabatan wajib diisi.',
            'name' => 'Nama wajib diisi.',
            'note' => 'Keterangan maksimal 255 karakter.',
        ], null, 'member-new');

    $this->actingAs($this->user)->delete(route('dashboard.profil.members.destroy', $member));

    expect(OrganizationMember::query()->count())->toBe(0);
});

test('the seeder fills profil content with real facility names', function () {
    $this->seed(AboutPageSeeder::class);

    expect(Facility::query()->ordered()->pluck('name')->all())
        ->toBe(['Ruang sholat', 'Tempat wudhu', 'Perpustakaan', 'Akses internet', 'Area parkir', 'Toilet bersih'])
        ->and(AboutPage::current()->intro_description)->not->toBeNull()
        ->and(AboutStat::query()->whereNull('icon')->count())->toBe(0);
});
