<?php

namespace Tests\Feature;

use App\Models\AboutPage;
use App\Models\ActivityPage;
use App\Models\HomePage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_public_pages_load_with_shared_navigation(): void
    {
        HomePage::factory()->create();
        AboutPage::factory()->create();
        ActivityPage::factory()->create();

        Http::fake(function (ClientRequest $request) {
            $date = basename((string) parse_url($request->url(), PHP_URL_PATH));

            return Http::response([
                'status' => true,
                'data' => [
                    'kabko' => 'KAB. SIDOARJO',
                    'jadwal' => [
                        $date => [
                            'tanggal' => 'Hari ini',
                            'subuh' => '03:44',
                            'terbit' => '04:58',
                            'dzuhur' => '11:16',
                            'ashar' => '14:30',
                            'maghrib' => '17:27',
                            'isya' => '18:39',
                        ],
                    ],
                ],
            ]);
        });

        $pages = [
            '/' => 'Masjid Nurul Haqq',
            '/profil' => 'Rumah ibadah warga Griya Taman Asri',
            '/jadwal-kegiatan' => 'Ibadah dan kajian setiap pekan',
            '/program-tpq' => 'Tumbuh bersama Al-Qur\'an sejak dini',
            '/ziswaf' => 'Salurkan kebaikan, kuatkan kebersamaan',
            '/galeri' => 'Momen kebersamaan jamaah',
            '/kontak' => 'Hubungi pengurus masjid',
        ];

        foreach ($pages as $url => $heading) {
            $this->get($url)
                ->assertOk()
                ->assertSee($heading)
                ->assertSee('Jadwal &amp; Kegiatan', false)
                ->assertSee('aria-current="page"', false)
                ->assertSee(route('kontak'), false);
        }
    }
}
