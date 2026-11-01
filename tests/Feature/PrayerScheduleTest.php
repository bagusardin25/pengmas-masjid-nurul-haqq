<?php

namespace Tests\Feature;

use App\Models\HomePage;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PrayerScheduleTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        CarbonImmutable::setTestNow();

        parent::tearDown();
    }

    public function test_homepage_displays_prayer_times_from_the_api_for_today(): void
    {
        Carbon::setTestNow(CarbonImmutable::parse('2026-11-01 10:00:00', 'Asia/Jakarta'));
        CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-11-01 10:00:00', 'Asia/Jakarta'));

        HomePage::factory()->create();

        Http::fake([
            'https://api.myquran.com/*' => Http::response([
                'status' => true,
                'message' => 'success',
                'data' => [
                    'id' => 'cfa0860e83a4c3a763a7e62d825349f7',
                    'kabko' => 'KAB. SIDOARJO',
                    'prov' => 'JAWA TIMUR',
                    'jadwal' => [
                        '2026-11-01' => [
                            'tanggal' => 'Minggu, 01/11/2026',
                            'imsak' => '03:34',
                            'subuh' => '03:44',
                            'terbit' => '04:58',
                            'dhuha' => '05:26',
                            'dzuhur' => '11:16',
                            'ashar' => '14:30',
                            'maghrib' => '17:27',
                            'isya' => '18:39',
                        ],
                    ],
                ],
            ]),
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Minggu, 01/11/2026')
            ->assertSee('KAB. SIDOARJO dan sekitarnya')
            ->assertSee('11:16')
            ->assertSee('14:30')
            ->assertSee('Sholat berikutnya:')
            ->assertSee('aria-current="time"', false);

        Http::assertSent(fn (ClientRequest $request) => str_ends_with(
            $request->url(),
            '/cfa0860e83a4c3a763a7e62d825349f7/2026-11-01'
        ));
    }
}
