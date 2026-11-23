<?php

use App\Enums\PublicationStatus;
use App\Enums\Weekday;
use App\Models\Agenda;
use App\Models\KitabSchedule;
use App\Models\RoutineActivity;
use App\Models\ZiswafFund;
use App\Models\ZiswafReport;
use App\Services\PrayerSchedule;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->travelTo(CarbonImmutable::parse('2026-11-13 10:00:00', 'Asia/Jakarta'));
    Http::fake([
        'https://api.myquran.com/*' => Http::response([
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
        ]),
    ]);
});

test('TV display uses current published database content and the mosque logo', function () {
    RoutineActivity::factory()->create(['title' => 'Kajian rutin TV', 'schedule' => 'Ahad · 19.30 WIB']);
    RoutineActivity::factory()->create(['title' => 'Kegiatan internal', 'show_on_home' => false]);
    KitabSchedule::factory()->create([
        'title' => 'Ngaji Safinatun Najah TV',
        'day' => Weekday::Sabtu,
        'time' => '18.00 WIB',
    ]);
    Agenda::factory()->create([
        'title' => 'Agenda diterbitkan TV',
        'date' => '2026-11-20',
        'status' => PublicationStatus::Published,
    ]);
    Agenda::factory()->create([
        'title' => 'Agenda draf TV',
        'date' => '2026-11-21',
        'status' => PublicationStatus::Draft,
    ]);
    ZiswafFund::factory()->create(['name' => 'Sedekah aktif TV']);
    ZiswafFund::factory()->create(['name' => 'Dana tersembunyi TV', 'is_active' => false]);
    ZiswafReport::factory()->published()->create(['title' => 'Laporan kas TV']);
    ZiswafReport::factory()->create(['title' => 'Laporan draf TV']);

    $this->get(route('tv.display'))
        ->assertOk()
        ->assertSee('images/icons/icon-512.png')
        ->assertSee('03:44')
        ->assertSee('18:39')
        ->assertDontSee('04:58')
        ->assertSee('Kajian rutin TV')
        ->assertSee('Ngaji Safinatun Najah TV')
        ->assertSee('Agenda diterbitkan TV')
        ->assertDontSee('Agenda draf TV')
        ->assertSee('Sedekah aktif TV')
        ->assertDontSee('Dana tersembunyi TV')
        ->assertSee('Laporan kas TV')
        ->assertDontSee('Laporan draf TV')
        ->assertDontSee('Rp 23.122.000');
});

test('TV display clearly indicates unavailable prayer times and handles empty database content', function () {
    app()->instance(PrayerSchedule::class, new class extends PrayerSchedule
    {
        public function forDay(CarbonImmutable $now): array
        {
            return ['schedule' => null, 'prayers' => [], 'next' => null];
        }
    });

    $this->get(route('tv.display'))
        ->assertOk()
        ->assertSee('Jadwal salat belum tersedia')
        ->assertSee('Belum ada kegiatan rutin.')
        ->assertSee('Belum ada agenda yang diterbitkan.')
        ->assertSee('Belum ada laporan keuangan yang diterbitkan.');
});
