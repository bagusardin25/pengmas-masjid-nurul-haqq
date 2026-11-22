<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex">
    <title>{{ $title ?? 'Masuk' }} — Masjid Jami Nurul Haqq</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="auth-body">
    <main class="auth-shell">
        <section class="auth-visual" aria-hidden="true">
            <img src="{{ asset('images/beranda/hero-masjid-pintu-1200.jpg') }}" alt="" decoding="async">
            <div class="auth-visual-copy">
                <p class="auth-arabic" lang="ar" dir="rtl">إِنَّمَا يَعْمُرُ مَسَاجِدَ اللَّهِ مَنْ آمَنَ بِاللَّهِ وَالْيَوْمِ الْآخِرِ</p>
                <p class="auth-translation">“Sesungguhnya yang memakmurkan masjid-masjid Allah hanyalah orang yang beriman kepada Allah dan hari akhir…”</p>
                <p class="auth-citation">QS. At-Taubah: 18</p>
            </div>
        </section>

        <section class="auth-panel">
            <div class="auth-card">
                <a class="auth-brand" href="{{ route('home') }}">
                    <span class="auth-brand-mark" aria-hidden="true">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 20.5h18"></path><path d="M5.5 20.5v-6.2a6.5 6.5 0 0 1 13 0v6.2"></path><path d="M12 7.8V4.6"></path><circle cx="12" cy="3.4" r="1"></circle><path d="M10 20.5v-3a2 2 0 0 1 4 0v3"></path></svg>
                    </span>
                    <span class="auth-brand-copy"><span>MASJID JAMI</span><strong>Nurul Haqq</strong></span>
                </a>

                {{ $slot }}
            </div>

            <p class="auth-footer">
                <a href="{{ route('home') }}">← Kembali ke situs</a>
                <span>© {{ date('Y') }} Yayasan Nurul Haqq</span>
            </p>
        </section>
    </main>
</body>
</html>
