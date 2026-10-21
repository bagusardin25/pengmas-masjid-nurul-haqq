<footer class="site-footer">
    <div class="container footer-inner">
        <div class="footer-about">
            <a class="brand brand-inverted" href="{{ route('home') }}">
                <span class="brand-mark" aria-hidden="true">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 20.5h18"></path><path d="M5.5 20.5v-6.2a6.5 6.5 0 0 1 13 0v6.2"></path><path d="M12 7.8V4.6"></path><circle cx="12" cy="3.4" r="1"></circle><path d="M10 20.5v-3a2 2 0 0 1 4 0v3"></path></svg>
                </span>
                <span class="brand-copy"><span>MASJID JAMI</span><strong>Nurul Haqq</strong></span>
            </a>
            <p>Griya Taman Asri Blok FF 08-09, Tawangsari, Kec. Taman, Kabupaten Sidoarjo, Jawa Timur.</p>
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
                <a href="{{ route('ziswaf') }}">Laporan keuangan</a>
            </div>
            <div>
                <h2>Ikuti kami</h2>
                <a href="https://instagram.com/masjidnurulhaqq" target="_blank" rel="noopener noreferrer">Instagram</a>
                <a href="https://youtube.com" target="_blank" rel="noopener noreferrer">YouTube</a>
                <a href="https://wa.me/6281239567390" target="_blank" rel="noopener noreferrer">WhatsApp</a>
            </div>
        </div>
        <div class="footer-bottom">
            <span>© {{ date('Y') }} Masjid Nurul Haqq · Yayasan Nurul Haqq</span>
            <a href="#main-content">Kembali ke atas ↑</a>
        </div>
    </div>
</footer>
