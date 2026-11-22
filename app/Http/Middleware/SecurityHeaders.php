<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Vite;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Header keamanan untuk semua halaman (publik dan dashboard).
 *
 * CSP disesuaikan dengan yang dipakai situs: Google Fonts, peta Google Maps, gambar pratinjau
 * YouTube, dan Alpine.js (butuh 'unsafe-eval'). Pratinjau HP di dashboard memuat situs sendiri
 * di dalam iframe, jadi frame diizinkan dari domain sendiri saja.
 */
class SecurityHeaders
{
    public function __construct(private readonly Vite $vite) {}

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $headers = $response->headers;

        $headers->set('X-Content-Type-Options', 'nosniff');
        $headers->set('X-Frame-Options', 'SAMEORIGIN');
        $headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=(), usb=(), interest-cohort=()');

        // Situs live selalu lewat HTTPS (HTTP sudah dialihkan hosting). Tidak memakai $request->isSecure()
        // karena di balik CDN hosting permintaan sampai ke PHP sebagai HTTP.
        if (str_starts_with((string) config('app.url'), 'https://')) {
            $headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // Saat `npm run dev`, aset dimuat dari server Vite lokal; CSP dimatikan agar tidak memblokirnya.
        if (! $this->vite->isRunningHot()) {
            $headers->set('Content-Security-Policy', $this->contentSecurityPolicy());
        }

        // Jangan perlihatkan versi PHP.
        $headers->remove('X-Powered-By');
        if (! headers_sent()) {
            header_remove('X-Powered-By');
        }

        return $response;
    }

    private function contentSecurityPolicy(): string
    {
        return implode('; ', [
            "default-src 'self'",
            // 'unsafe-inline' untuk konfirmasi hapus (onsubmit) dan 'unsafe-eval' untuk Alpine.js.
            "script-src 'self' 'unsafe-inline' 'unsafe-eval'",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "font-src 'self' data: https://fonts.gstatic.com",
            "img-src 'self' data: blob: https://i.ytimg.com",
            "frame-src 'self' https://www.google.com https://maps.google.com https://www.youtube.com",
            "connect-src 'self'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'",
            'upgrade-insecure-requests',
        ]);
    }
}
