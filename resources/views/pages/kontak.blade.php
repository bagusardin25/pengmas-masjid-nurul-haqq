@extends('layouts.app')

@section('title', 'Kontak')
@section('description', 'Hubungi pengurus masjid, layanan sekretariat, dan informasi pendaftaran TPQ serta ZISWAF.')

@section('content')
    <section class="contact-page-hero">
        <div class="container contact-hero-inner">
            <p class="breadcrumb"><a href="{{ route('home') }}">Beranda</a> / Kontak</p>
            <p class="eyebrow eyebrow-light">Kontak</p>
            <h1>Hubungi pengurus masjid</h1>
            <p>Untuk layanan ZISWAF, pendaftaran TPQ, dan kebutuhan ibadah lainnya. Mari terhubung dengan Masjid Jami Nurul Haqq.</p>
            <a class="button button-gold" href="https://wa.me/6281239567390">♡ Tanya via WhatsApp</a>
        </div>
    </section>

    <section class="section">
        <div class="container contact-grid">
            <div class="contact-left">
                <p class="eyebrow">Kontak kami</p>
                <h2>Kami siap terhubung</h2>
                <p class="contact-intro">Pilih saluran yang sesuai dengan kebutuhan Anda.</p>

                <div class="contact-list">
                    <a class="contact-item" href="tel:081234567890">
                        <span class="contact-icon">☎</span>
                        <span class="contact-meta">
                            <small>Kontak takmir</small>
                            <strong>0812-3456-7890</strong>
                        </span>
                        <span class="contact-arrow">→</span>
                    </a>
                    <a class="contact-item" href="https://wa.me/6281239567390">
                        <span class="contact-icon">✆</span>
                        <span class="contact-meta">
                            <small>Sekretariat / WhatsApp</small>
                            <strong>0812-3956-7390</strong>
                        </span>
                        <span class="contact-arrow">→</span>
                    </a>
                    <a class="contact-item" href="mailto:masjidnurulhaqq@gmail.com">
                        <span class="contact-icon">✉</span>
                        <span class="contact-meta">
                            <small>Email</small>
                            <strong>masjidnurulhaqq@gmail.com</strong>
                        </span>
                        <span class="contact-arrow">→</span>
                    </a>
                    <a class="contact-item" href="https://instagram.com/masjidnurulhaqq" target="_blank" rel="noopener noreferrer">
                        <span class="contact-icon">◎</span>
                        <span class="contact-meta">
                            <small>Instagram</small>
                            <strong>@masjidnurulhaqq</strong>
                        </span>
                        <span class="contact-arrow">→</span>
                    </a>
                    <div class="contact-item static-item">
                        <span class="contact-meta">
                            <small>Layanan sekretariat</small>
                            <strong>Senin - Jumat, 09.00 - 15.00 WIB</strong>
                            <small>Jam layanan contoh</small>
                        </span>
                    </div>
                </div>
            </div>

            <div class="contact-right">
                <p class="eyebrow contact-location-label">Lokasi masjid</p>
                <div class="map-panel">
                    <div class="map-icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="2.5"></circle></svg>
                    </div>
                    <div class="map-content">
                        <h3>Area peta Masjid Nurul Haqq</h3>
                        <p>Perlu bantuan? Silakan kunjungi kami di lokasi atau hubungi pengurus untuk informasi lebih detail.</p>
                    </div>
                </div>

                <div class="location-card">
                    <h3>Masjid Jami Nurul Haqq</h3>
                    <p>Griya Taman Asri Blok FF 08-09, Tawangsari, Kec. Taman, Kabupaten Sidoarjo, Jawa Timur.</p>
                    <a class="button button-outline" href="https://maps.google.com/?q=Masjid+Jami+Nurul+Haqq+Sidoarjo" target="_blank" rel="noopener noreferrer">
                        <span aria-hidden="true">↗</span> Buka di Google Maps
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="section section-muted">
        <div class="container">
            <p class="eyebrow">Layanan jamaah</p>
            <h2 class="section-heading-inline">Ada yang bisa kami bantu?</h2>

            <div class="help-grid">
                <a class="help-card" href="{{ route('ziswaf') }}">
                    <span class="card-icon">♡</span>
                    <h3>Layanan ZISWAF</h3>
                    <p>Jangan khawatir jika ingin menyalurkan zakat, infak, sedekah, wakaf, atau kebutuhan donasi lainnya.</p>
                </a>
                <a class="help-card" href="{{ route('tpq') }}">
                    <span class="card-icon">▤</span>
                    <h3>Pendaftaran TPQ</h3>
                    <p>Hubungi sekretariat untuk informasi pendaftaran, jadwal kelas, dan program yang tersedia.</p>
                </a>
                <a class="help-card" href="{{ route('jadwal') }}">
                    <span class="card-icon">⌂</span>
                    <h3>Kebutuhan ibadah</h3>
                    <p>Hubungi takmir untuk pertanyaan terkait sholat berjamaah, pengajian, dan kebutuhan ibadah lainnya.</p>
                </a>
            </div>

            <a class="button button-dark contact-direct" href="https://wa.me/6281239567390">Hubungi sekretariat</a>
        </div>
    </section>

    <x-donation-cta />
@endsection
