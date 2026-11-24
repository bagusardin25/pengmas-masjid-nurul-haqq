@php
    $footerContact = $siteContact->setting();
    $footerWhatsapp = $siteContact->whatsappUrl();
    $traffic = $siteTraffic->stats();
@endphp

<footer class="site-footer">
    <div class="site-container footer-traffic-wrap" data-reveal>
        <div class="footer-traffic" aria-label="Statistik pengunjung situs">
            <div class="footer-traffic-top">
                <div class="footer-traffic-head">
                    <span class="footer-traffic-badge">
                        <span class="prayer-live-dot" aria-hidden="true"></span>
                        STATISTIK PENGUNJUNG
                    </span>
                    <p class="footer-traffic-subtitle">Grafik kunjungan jamaah 7 hari terakhir</p>
                </div>
                <div class="footer-traffic-metrics tabular-nums">
                    <span>Online: <strong>{{ number_format($traffic['online'], 0, ',', '.') }}</strong></span>
                    <span>Hari ini: <strong>{{ number_format($traffic['today_hits'], 0, ',', '.') }}</strong> <small>({{ number_format($traffic['today_visitors'], 0, ',', '.') }} unik)</small></span>
                    <span>Bulan ini: <strong>{{ number_format($traffic['month_hits'], 0, ',', '.') }}</strong></span>
                    <span>Total: <strong>{{ number_format($traffic['total_hits'], 0, ',', '.') }}</strong></span>
                </div>
            </div>
            <div class="footer-chart-box">
                <canvas
                    class="footer-line-chart"
                    data-traffic-chart='@json($traffic["series"])'
                    aria-label="Grafik kunjungan harian 7 hari terakhir"
                    role="img"></canvas>
            </div>
        </div>
    </div>

    <div class="site-container footer-inner">
        <div class="footer-about">
            <a class="brand brand-inverted" href="{{ route('home') }}">
                <span class="brand-mark" aria-hidden="true">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 20.5h18"></path><path d="M5.5 20.5v-6.2a6.5 6.5 0 0 1 13 0v6.2"></path><path d="M12 7.8V4.6"></path><circle cx="12" cy="3.4" r="1"></circle><path d="M10 20.5v-3a2 2 0 0 1 4 0v3"></path></svg>
                </span>
                <span class="brand-copy"><span>MASJID JAMI</span><strong>Nurul Haqq</strong></span>
            </a>
            <p>{{ $footerContact?->address ?? 'Griya Taman Asri Blok FF 08-09, Tawangsari, Kec. Taman, Kabupaten Sidoarjo, Jawa Timur.' }}</p>
        </div>
        <div class="footer-links">
            <div>
                <h2>Navigasi</h2>
                <a href="{{ route('home') }}">Beranda</a>
                <a href="{{ route('profil') }}">Profil</a>
                <a href="{{ route('jadwal') }}">Jadwal &amp; Kegiatan</a>
                <a href="{{ route('galeri') }}">Galeri</a>
                <a href="{{ route('kontak') }}">Kontak</a>
            </div>
            <div>
                <h2>Layanan</h2>
                <a href="{{ route('tpq') }}">Program TPQ</a>
                <a href="{{ route('ziswaf') }}">ZISWAF</a>
                <a href="{{ route('ziswaf.laporan') }}">Laporan keuangan</a>
            </div>
            <div>
                <h2>Ikuti kami</h2>
                @if ($footerContact?->instagramUrl())
                    <a href="{{ $footerContact->instagramUrl() }}" target="_blank" rel="noopener noreferrer">Instagram</a>
                @endif
                @if (filled($footerContact?->youtube_url))
                    <a href="{{ $footerContact->youtube_url }}" target="_blank" rel="noopener noreferrer">YouTube</a>
                @endif
                @if ($footerWhatsapp)
                    <a href="{{ $footerWhatsapp }}" target="_blank" rel="noopener noreferrer">WhatsApp</a>
                @endif
                @if (filled($footerContact?->email))
                    <a href="mailto:{{ $footerContact->email }}">Email</a>
                @endif
            </div>
        </div>
        <div class="footer-bottom">
            <span>© {{ date('Y') }} Masjid Nurul Haqq · Yayasan Nurul Haqq</span>
            <a href="#main-content">Kembali ke atas ↑</a>
        </div>
    </div>
</footer>
