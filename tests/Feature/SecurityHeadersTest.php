<?php

use App\Models\User;
use Illuminate\Foundation\Vite;

beforeEach(function () {
    // Abaikan public/hot milik `npm run dev` agar CSP diuji seperti di produksi.
    app(Vite::class)->useHotFile(storage_path('framework/tidak-ada.hot'));
    config(['app.url' => 'https://masjidnurulhaqq.com']);
});

test('public pages send security headers', function () {
    $response = $this->get(route('home'));

    $response->assertOk()
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
        ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
        ->assertHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains')
        ->assertHeaderMissing('X-Powered-By');

    expect($response->headers->get('Permissions-Policy'))->toContain('camera=()');
});

test('the content security policy allows only what the site uses', function () {
    $csp = $this->get(route('kontak'))->headers->get('Content-Security-Policy');

    expect($csp)
        ->toContain("default-src 'self'")
        ->toContain("frame-ancestors 'self'")
        ->toContain("object-src 'none'")
        ->toContain("form-action 'self'")
        ->toContain('https://fonts.googleapis.com')
        ->toContain('https://fonts.gstatic.com')
        ->toContain('https://www.google.com')
        ->toContain('https://i.ytimg.com');
});

test('dashboard, login, and error pages also send security headers', function () {
    $this->get('/login')->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    $this->get('/halaman-tidak-ada')->assertNotFound()->assertHeader('X-Content-Type-Options', 'nosniff');

    $this->actingAs(User::factory()->create())
        ->get(route('dashboard'))
        ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
        ->assertHeader('Content-Security-Policy');
});

test('hsts is only sent when the site runs on https', function () {
    config(['app.url' => 'http://localhost']);

    $this->get(route('home'))->assertHeaderMissing('Strict-Transport-Security');
});

test('the content security policy is skipped while the vite dev server runs', function () {
    $hot = storage_path('framework/vite-uji.hot');
    file_put_contents($hot, 'http://[::1]:5173');
    app(Vite::class)->useHotFile($hot);

    try {
        $this->get(route('home'))->assertHeaderMissing('Content-Security-Policy');
    } finally {
        unlink($hot);
    }
});

test('array and malformed query parameters never cause server errors', function () {
    $user = User::factory()->create();

    $publicUrls = [
        '/galeri?kategori[]=x',
        '/galeri?kategori[a][b]=x',
        '/galeri?halaman[]=1',
        '/galeri?halaman=abc',
        '/ziswaf/laporan?kategori[]=x',
        '/ziswaf/laporan?page[]=1',
        '/ziswaf/laporan?kategori=%00',
        '/?redirect[]=x',
    ];

    foreach ($publicUrls as $url) {
        expect($this->get($url)->status())->toBeLessThan(500, $url);
    }

    expect($this->actingAs($user)->getJson('/dashboard/galeri/video/info-youtube?url[]=x')->status())->toBe(422);
});
