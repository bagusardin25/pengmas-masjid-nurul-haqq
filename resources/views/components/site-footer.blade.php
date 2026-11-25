@php
    $footerContact = $siteContact->setting();
    $footerWhatsapp = $siteContact->whatsappUrl();
@endphp

<footer class="site-footer">
    <div class="site-container footer-inner">
        <div class="footer-about">
            <a class="brand brand-inverted" href="{{ route('home') }}">
                <span class="footer-brand-logos" aria-hidden="true">
                    <span class="brand-mark">
                        <img src="{{ asset('images/icons/icon-512.png') }}" alt="logo masjid">
                    </span>
                    <img class="footer-partner-logo" src="{{ asset('images/logo tus.jpeg') }}" alt="logo tus">
                </span>
            </a>
            <p>{{ $footerContact?->address ?? 'Griya Taman Asri Blok FF 08-09, Tawangsari, Kec. Taman, Kabupaten Sidoarjo, Jawa Timur.' }}
            </p>
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
                    <a href="{{ $footerContact->instagramUrl() }}" target="_blank"
                        rel="noopener noreferrer">Instagram</a>
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
