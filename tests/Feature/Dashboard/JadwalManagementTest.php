<?php

use App\Enums\PublicationStatus;
use App\Enums\Weekday;
use App\Models\ActivityPage;
use App\Models\Agenda;
use App\Models\KitabSchedule;
use App\Models\RoutineActivity;
use App\Models\User;
use Carbon\CarbonImmutable;
use Database\Seeders\ActivityPageSeeder;

beforeEach(function () {
    $this->travelTo(CarbonImmutable::parse('2026-11-13 10:00:00', 'Asia/Jakarta'));
    $this->user = User::factory()->create();
});

test('guests cannot open the jadwal management page', function () {
    $this->get(route('dashboard.jadwal'))->assertRedirect(route('login'));
    $this->put(route('dashboard.jadwal.update'), ['hero_title' => 'x'])->assertRedirect(route('login'));
    $this->post(route('dashboard.jadwal.agendas.store'), ['title' => 'x'])->assertRedirect(route('login'));
});

test('the jadwal management page lists content and splits past agendas', function () {
    RoutineActivity::factory()->create(['title' => 'Rutin tersimpan']);
    KitabSchedule::factory()->create(['title' => 'Kitab tersimpan']);
    Agenda::factory()->create(['title' => 'Agenda mendatang', 'date' => '2026-11-20']);
    Agenda::factory()->create(['title' => 'Agenda lampau', 'date' => '2026-11-06']);

    $this->actingAs($this->user)
        ->get(route('dashboard.jadwal'))
        ->assertOk()
        ->assertSee('href="'.route('dashboard.jadwal').'"', false)
        ->assertSee('Rutin tersimpan')
        ->assertSee('Kitab tersimpan')
        ->assertSee('Akan datang (1)')
        ->assertSee('Agenda yang sudah lewat (1)')
        ->assertSeeInOrder(['Agenda mendatang', 'Agenda yang sudah lewat', 'Agenda lampau']);
});

test('the jadwal management page works before any content exists', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.jadwal'))
        ->assertOk()
        ->assertSee('Belum ada kegiatan rutin.')
        ->assertSee('Belum ada agenda yang akan datang.')
        ->assertDontSee('Agenda yang sudah lewat');
});

test('page text can be saved and requires a title', function () {
    $this->actingAs($this->user)
        ->put(route('dashboard.jadwal.update'), [
            'hero_title' => 'Judul jadwal baru',
            'routine_note' => 'Catatan rutin baru',
        ])
        ->assertRedirect(route('dashboard.jadwal'));

    expect(ActivityPage::current()->hero_title)->toBe('Judul jadwal baru')
        ->and(ActivityPage::current()->routine_note)->toBe('Catatan rutin baru');

    $this->actingAs($this->user)
        ->from(route('dashboard.jadwal'))
        ->put(route('dashboard.jadwal.update'), ['hero_title' => ''])
        ->assertSessionHasErrors(['hero_title' => 'Judul halaman wajib diisi.'], null, 'activity');
});

test('routine activities can be created, hidden from beranda, and deleted', function () {
    $this->actingAs($this->user)
        ->post(route('dashboard.jadwal.routines.store'), [
            'title' => 'Sholat Jumat',
            'schedule' => 'Khotib & imam terjadwal oleh takmir',
            'badge' => 'Setiap Jumat',
            'show_on_home' => '1',
        ])
        ->assertRedirect(route('dashboard.jadwal').'#rutin');

    $routine = RoutineActivity::query()->where('title', 'Sholat Jumat')->firstOrFail();

    expect($routine->show_on_home)->toBeTrue()
        ->and($routine->sort_order)->toBe(1);

    $this->actingAs($this->user)
        ->put(route('dashboard.jadwal.routines.update', $routine), [
            'title' => 'Sholat Jumat',
            'badge' => str_repeat('a', 51),
            'show_on_home' => '0',
        ])
        ->assertSessionHasErrors(['badge' => 'Label waktu maksimal 50 karakter.'], null, 'routine-'.$routine->id);

    $this->actingAs($this->user)
        ->put(route('dashboard.jadwal.routines.update', $routine), [
            'title' => 'Sholat Jumat',
            'schedule' => '',
            'badge' => 'Jumat',
            'show_on_home' => '0',
        ]);

    $routine->refresh();

    expect($routine->badge)->toBe('Jumat')
        ->and($routine->schedule)->toBeNull()
        ->and($routine->show_on_home)->toBeFalse();

    $this->actingAs($this->user)->delete(route('dashboard.jadwal.routines.destroy', $routine));

    expect(RoutineActivity::query()->count())->toBe(0);
});

test('kitab schedules can be created, updated, and deleted', function () {
    $this->actingAs($this->user)
        ->post(route('dashboard.jadwal.kitab.store'), [
            'title' => 'Ngaji Kitab Irsyadul Ibad',
            'speaker' => 'Ust. Ainur Rofiq Muslimin',
            'day' => Weekday::Ahad->value,
            'time' => "Ba'da Maghrib",
        ])
        ->assertRedirect(route('dashboard.jadwal').'#kitab');

    $kitab = KitabSchedule::query()->firstOrFail();

    expect($kitab->day)->toBe(Weekday::Ahad);

    $this->actingAs($this->user)
        ->put(route('dashboard.jadwal.kitab.update', $kitab), [
            'title' => 'Ngaji Kitab Irsyadul Ibad',
            'speaker' => 'Ust. Ainur Rofiq Muslimin',
            'day' => 'minggu',
            'time' => '',
        ])
        ->assertSessionHasErrors(['day', 'time'], null, 'kitab-'.$kitab->id);

    $this->actingAs($this->user)
        ->put(route('dashboard.jadwal.kitab.update', $kitab), [
            'title' => 'Ngaji Kitab Irsyadul Ibad',
            'speaker' => 'Ust. Ainur Rofiq Muslimin',
            'day' => Weekday::Senin->value,
            'time' => "Ba'da Isya'",
            'location' => 'Serambi masjid',
        ]);

    $kitab->refresh();

    expect($kitab->day)->toBe(Weekday::Senin)
        ->and($kitab->location)->toBe('Serambi masjid');

    $this->actingAs($this->user)->delete(route('dashboard.jadwal.kitab.destroy', $kitab));

    expect(KitabSchedule::query()->count())->toBe(0);
});

test('agendas can be created, unpublished, and deleted', function () {
    $this->actingAs($this->user)
        ->post(route('dashboard.jadwal.agendas.store'), [
            'title' => 'Sholat Jumat',
            'date' => '2026-11-20',
            'time' => 'Waktu Dzuhur',
            'speaker' => 'Ust. Achmad Muaidi Jakfar',
            'location' => 'Masjid Nurul Haqq',
            'status' => PublicationStatus::Published->value,
        ])
        ->assertRedirect(route('dashboard.jadwal').'#agenda');

    $agenda = Agenda::query()->firstOrFail();

    expect($agenda->status)->toBe(PublicationStatus::Published)
        ->and($agenda->published_at)->not->toBeNull()
        ->and($agenda->slug)->not->toBe('');

    $this->actingAs($this->user)
        ->put(route('dashboard.jadwal.agendas.update', $agenda), [
            'title' => 'Sholat Jumat',
            'date' => '20-11-2026',
            'status' => 'arsip',
        ])
        ->assertSessionHasErrors(['date', 'status'], null, 'agenda-'.$agenda->id);

    $this->actingAs($this->user)
        ->put(route('dashboard.jadwal.agendas.update', $agenda), [
            'title' => 'Sholat Jumat',
            'date' => '2026-11-27',
            'time' => '',
            'speaker' => 'Ust. Abdul Fattah',
            'status' => PublicationStatus::Draft->value,
        ]);

    $agenda->refresh();

    expect($agenda->date->toDateString())->toBe('2026-11-27')
        ->and($agenda->speaker)->toBe('Ust. Abdul Fattah')
        ->and($agenda->time)->toBeNull()
        ->and($agenda->status)->toBe(PublicationStatus::Draft);

    $this->actingAs($this->user)->delete(route('dashboard.jadwal.agendas.destroy', $agenda));

    expect(Agenda::query()->count())->toBe(0);
});

test('the add agenda form suggests the friday after the last scheduled jumat', function () {
    Agenda::factory()->create(['title' => 'Sholat Jumat', 'date' => '2026-12-25']);

    $this->actingAs($this->user)
        ->get(route('dashboard.jadwal'))
        ->assertSee('id="agenda-new-date" name="date" type="date" value="2027-01-01"', false);
});

test('the add agenda form suggests the coming friday when no jumat is scheduled', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.jadwal'))
        ->assertSee('id="agenda-new-date" name="date" type="date" value="2026-11-20"', false);
});

test('the seeder loads the official 2026 jumat schedule on fridays without duplicates', function () {
    $this->seed(ActivityPageSeeder::class);
    $this->seed(ActivityPageSeeder::class);

    $jumat = Agenda::query()->where('title', 'Sholat Jumat')->orderBy('date')->get();

    expect($jumat)->toHaveCount(52)
        ->and($jumat->every(fn (Agenda $agenda) => $agenda->date->isFriday()))->toBeTrue()
        ->and($jumat->first()->date->toDateString())->toBe('2026-01-02')
        ->and($jumat->last()->speaker)->toBe('Ust. Ibnu Abdillah, S.Ag')
        ->and($jumat->every(fn (Agenda $agenda) => $agenda->status === PublicationStatus::Published))->toBeTrue()
        ->and(KitabSchedule::query()->count())->toBe(6)
        ->and(RoutineActivity::query()->count())->toBe(2);
});
