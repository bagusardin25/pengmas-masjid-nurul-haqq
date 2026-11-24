<?php

use App\Models\HomePage;
use App\Models\User;
use App\Support\SiteTraffic;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::fake(['https://api.myquran.com/*' => Http::response([], 500)]);
    HomePage::factory()->create();
});

test('public page visits increment visitor traffic stats and show in footer', function () {
    $this->withServerVariables(['REMOTE_ADDR' => '10.10.1.1'])
        ->get(route('home'))
        ->assertOk()
        ->assertSee('STATISTIK PENGUNJUNG')
        ->assertSee('Grafik kunjungan harian 7 hari terakhir')
        ->assertSee('footer-line-chart');

    $this->withServerVariables(['REMOTE_ADDR' => '10.10.1.1'])
        ->get(route('profil'))
        ->assertOk();

    $this->withServerVariables(['REMOTE_ADDR' => '10.10.1.2'])
        ->get(route('kontak'))
        ->assertOk();

    $stats = app(SiteTraffic::class)->stats();

    expect($stats['today_hits'])->toBe(3)
        ->and($stats['today_visitors'])->toBe(2)
        ->and($stats['month_hits'])->toBe(3)
        ->and($stats['total_hits'])->toBe(3)
        ->and($stats['online'])->toBe(2);
});

test('dashboard and login requests are excluded from visitor traffic', function () {
    $user = User::factory()->create();

    $this->get('/login')->assertOk();
    $this->actingAs($user)->get(route('dashboard'))->assertOk();

    $stats = app(SiteTraffic::class)->stats();

    expect($stats['total_hits'])->toBe(0)
        ->and($stats['today_visitors'])->toBe(0);
});
