<?php

use App\Enums\PublicationStatus;
use App\Enums\Weekday;
use App\Models\ActivityPage;
use App\Models\Agenda;
use App\Models\KitabSchedule;
use App\Models\RoutineActivity;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    // Jumat, 13 November 2026 pukul 10.00 WIB: Subuh sudah lewat, Dzuhur berikutnya.
    $this->travelTo(CarbonImmutable::parse('2026-11-13 10:00:00', 'Asia/Jakarta'));

    $this->prayerApiDown = false;

    Http::fake(fn () => $this->prayerApiDown ? Http::response([], 500) : Http::response([
        'status' => true,
        'data' => [
            'kabko' => 'KAB. SIDOARJO',
            'jadwal' => [
                '2026-11-13' => [
                    'tanggal' => 'Jumat, 13/11/2026',
                    'subuh' => '03:44',
                    'terbit' => '04:58',
                    'dzuhur' => '11:16',
                    'ashar' => '14:30',
                    'maghrib' => '17:27',
                    'isya' => '18:39',
                ],
            ],
        ],
    ]));
});

test('jadwal page shows schedule content from the database', function () {
    ActivityPage::factory()->create([
        'hero_title' => 'Judul jadwal dari database',
        'routine_note' => 'Catatan rutin dari database',
        'kitab_note' => 'Catatan kitab dari database',
    ]);
    RoutineActivity::factory()->create(['title' => 'Sholat Jumat', 'schedule' => 'Khotib terjadwal', 'badge' => 'Setiap Jumat']);
    KitabSchedule::factory()->create(['title' => 'Ngaji Kitab Irsyadul Ibad', 'speaker' => 'Ust. Ainur Rofiq', 'day' => Weekday::Ahad, 'time' => "Ba'da Maghrib", 'location' => null]);
    Agenda::factory()->create(['title' => 'Sholat Jumat', 'speaker' => 'Ust. Achmad Muaidi Jakfar', 'date' => '2026-11-20', 'status' => PublicationStatus::Published]);
    Agenda::factory()->create(['title' => 'Agenda draf', 'date' => '2026-11-21', 'status' => PublicationStatus::Draft]);
    Agenda::factory()->create(['title' => 'Agenda lewat', 'date' => '2026-11-06', 'status' => PublicationStatus::Published]);

    $this->get(route('jadwal'))
        ->assertOk()
        ->assertSee('Judul jadwal dari database')
        ->assertSee('Catatan rutin dari database')
        ->assertSee('Catatan kitab dari database')
        ->assertSee('Khotib terjadwal')
        ->assertSee('Setiap Jumat')
        ->assertSee('Ngaji Kitab Irsyadul Ibad')
        ->assertSee("Ust. Ainur Rofiq · Ahad, Ba'da Maghrib")
        ->assertSee('Ust. Achmad Muaidi Jakfar')
        ->assertSee('Jumat, 20 November 2026')
        ->assertDontSee('Agenda draf')
        ->assertDontSee('Agenda lewat')
        ->assertDontSee('simulasi');
});

test('jadwal page shows live prayer times and marks the next prayer', function () {
    $this->get(route('jadwal'))
        ->assertOk()
        ->assertSee('Jumat, 13/11/2026')
        ->assertSee('KAB. SIDOARJO dan sekitarnya')
        ->assertSee('03:44')
        ->assertSee('18:39')
        ->assertSee('prayer-time is-next', false)
        ->assertSee('aria-current="time"', false);
});

test('jadwal page explains when prayer times are unavailable', function () {
    $this->prayerApiDown = true;

    $this->get(route('jadwal'))
        ->assertOk()
        ->assertSee('Jadwal sholat tidak tersedia saat ini');
});

test('jadwal page renders before any content is saved', function () {
    $this->get(route('jadwal'))
        ->assertOk()
        ->assertSee('Belum ada kegiatan rutin.')
        ->assertSee('Belum ada agenda terjadwal.')
        ->assertSee('Belum ada jadwal ngaji kitab.');
});

test('beranda shows routines, grouped kitab sessions, and the nearest agenda from the database', function () {
    RoutineActivity::factory()->create(['title' => 'Sholat fardhu berjamaah', 'show_on_home' => true]);
    RoutineActivity::factory()->create(['title' => 'Kegiatan khusus jadwal', 'show_on_home' => false]);
    KitabSchedule::factory()->create(['title' => 'Ngaji Kitab Nashoihud Diniyyah', 'speaker' => 'KH. Ahmad Jazuli', 'day' => Weekday::Sabtu, 'time' => 'Pekan ke-1 & 3', 'sort_order' => 1]);
    KitabSchedule::factory()->create(['title' => 'Ngaji Kitab Nashoihud Diniyyah', 'speaker' => 'KH. Ahmad Jazuli', 'day' => Weekday::Rabu, 'time' => 'Pekan ke-2 & 4', 'sort_order' => 2]);
    Agenda::factory()->create(['title' => 'Sholat Jumat', 'speaker' => 'Ust. Achmad Muaidi Jakfar', 'date' => '2026-11-20', 'time' => 'Waktu Dzuhur', 'status' => PublicationStatus::Published]);
    Agenda::factory()->create(['title' => 'Sholat Jumat', 'speaker' => 'Ust. Abdul Fattah', 'date' => '2026-11-27', 'status' => PublicationStatus::Published]);

    $response = $this->get(route('home'))->assertOk();

    $response->assertSee('Sholat fardhu berjamaah')
        ->assertDontSee('Kegiatan khusus jadwal')
        ->assertSee('Sabtu')
        ->assertSee('Rabu')
        ->assertSee('Ust. Achmad Muaidi Jakfar')
        ->assertSee('Jumat, 20 November 2026 · Waktu Dzuhur')
        ->assertSee('Berikutnya: 27 November 2026')
        ->assertSee('Ust. Abdul Fattah');

    expect(substr_count($response->getContent(), 'Ngaji Kitab Nashoihud Diniyyah'))->toBe(1);
});
