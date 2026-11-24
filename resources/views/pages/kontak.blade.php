@extends('layouts.app')

@section('title', 'Kontak')
@section('description', 'Hubungi pengurus masjid, layanan sekretariat, dan informasi pendaftaran TPQ serta ZISWAF.')

@php
    $contact = $siteContact->setting();
    $phones = $siteContact->phones();
    $whatsappUrl = $siteContact->whatsappUrl();
    $mapsLink = $contact?->mapsLink();
@endphp

@section('content')
    <section class="contact-page-hero">
        <div class="site-container contact-hero-inner">
            <p class="breadcrumb"><a href="{{ route('home') }}">Beranda</a> / Kontak</p>
            <p class="eyebrow eyebrow-light">Kontak</p>
            <h1>Hubungi pengurus masjid</h1>
            <p>Untuk layanan ZISWAF, pendaftaran TPQ, dan kebutuhan ibadah lainnya. Mari terhubung dengan Masjid Jami Nurul Haqq.</p>
            @if ($whatsappUrl)
                <a class="button button-gold" href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer"><i class="fa-solid fa-comment-dots text-[14px]" aria-hidden="true"></i> Tanya via WhatsApp</a>
            @endif
        </div>
    </section>

    <section class="section">
        <div class="site-container contact-grid">
            <div class="contact-left" data-reveal>
                <p class="eyebrow">Kontak kami</p>
                <h2>Kami siap terhubung</h2>
                <p class="contact-intro">Pilih saluran yang sesuai dengan kebutuhan Anda.</p>

                <div class="contact-list" data-reveal-group>
                    @foreach ($phones as $phone)
                        <a data-reveal class="contact-item" href="{{ $phone->url() }}" @if ($phone->is_whatsapp) target="_blank" rel="noopener noreferrer" @endif>
                            <span class="contact-icon" aria-hidden="true"><i class="fa-solid {{ $phone->is_whatsapp ? 'fa-comment-dots' : 'fa-phone' }} text-[14px]" aria-hidden="true"></i></span>
                            <span class="contact-meta">
                                <small>{{ $phone->label }}{{ $phone->is_whatsapp ? ' · WhatsApp' : '' }}</small>
                                <strong class="tabular-nums">{{ $phone->number }}</strong>
                            </span>
                            <span class="contact-arrow" aria-hidden="true"><i class="fa-solid fa-arrow-right text-[13px]" aria-hidden="true"></i></span>
                        </a>
                    @endforeach

                    @if (filled($contact?->email))
                        <a data-reveal class="contact-item" href="mailto:{{ $contact->email }}">
                            <span class="contact-icon" aria-hidden="true"><i class="fa-solid fa-envelope text-[14px]" aria-hidden="true"></i></span>
                            <span class="contact-meta">
                                <small>Email</small>
                                <strong>{{ $contact->email }}</strong>
                            </span>
                            <span class="contact-arrow" aria-hidden="true"><i class="fa-solid fa-arrow-right text-[13px]" aria-hidden="true"></i></span>
                        </a>
                    @endif

                    @if ($contact?->instagramUrl())
                        <a data-reveal class="contact-item" href="{{ $contact->instagramUrl() }}" target="_blank" rel="noopener noreferrer">
                            <span class="contact-icon" aria-hidden="true"><i class="fa-solid fa-hashtag text-[14px]" aria-hidden="true"></i></span>
                            <span class="contact-meta">
                                <small>Instagram</small>
                                <strong>{{ '@'.$contact->instagram }}</strong>
                            </span>
                            <span class="contact-arrow" aria-hidden="true"><i class="fa-solid fa-arrow-right text-[13px]" aria-hidden="true"></i></span>
                        </a>
                    @endif

                    @if (filled($contact?->youtube_url))
                        <a data-reveal class="contact-item" href="{{ $contact->youtube_url }}" target="_blank" rel="noopener noreferrer">
                            <span class="contact-icon" aria-hidden="true"><i class="fa-solid fa-play text-[13px]" aria-hidden="true"></i></span>
                            <span class="contact-meta">
                                <small>YouTube</small>
                                <strong>Kanal Masjid Nurul Haqq</strong>
                            </span>
                            <span class="contact-arrow" aria-hidden="true"><i class="fa-solid fa-arrow-right text-[13px]" aria-hidden="true"></i></span>
                        </a>
                    @endif

                    @if (filled($contact?->service_hours))
                        <div data-reveal class="contact-item static-item">
                            <span class="contact-meta">
                                <small>Layanan sekretariat</small>
                                <strong>{{ $contact->service_hours }}</strong>
                            </span>
                        </div>
                    @endif

                    @if ($phones->isEmpty() && blank($contact?->email))
                        <p class="gallery-empty">Kontak pengurus sedang diperbarui. Silakan datang langsung ke sekretariat masjid.</p>
                    @endif
                </div>
            </div>

            <div class="contact-right" data-reveal>
                <p class="eyebrow contact-location-label">Lokasi masjid</p>
                @if (filled($contact?->maps_embed_url))
                    <div class="map-embed">
                        <iframe src="{{ $contact->maps_embed_url }}" title="Peta lokasi Masjid Nurul Haqq di Google Maps"
                            loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                    </div>
                @else
                    <div class="map-panel">
                        <div class="map-icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="2.5"></circle></svg>
                        </div>
                        <div class="map-content">
                            <h3>Masjid Nurul Haqq</h3>
                            <p>Perlu bantuan? Silakan kunjungi kami di lokasi atau hubungi pengurus untuk informasi lebih detail.</p>
                        </div>
                    </div>
                @endif

                <div class="location-card">
                    <h3>Masjid Jami Nurul Haqq</h3>
                    @if (filled($contact?->address))
                        <p>{{ $contact->address }}</p>
                    @endif
                    @if ($mapsLink)
                        <a class="button button-outline" href="{{ $mapsLink }}" target="_blank" rel="noopener noreferrer">
                            <i class="fa-solid fa-arrow-up-right-from-square text-[11px]" aria-hidden="true"></i> Buka di Google Maps
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="section section-muted">
        <div class="site-container">
            <div data-reveal>
                <p class="eyebrow">Layanan jamaah</p>
                <h2 class="section-heading-inline">Ada yang bisa kami bantu?</h2>
            </div>

            <div class="help-grid" data-reveal-group>
                <a data-reveal class="help-card" href="{{ route('ziswaf') }}">
                    <span class="card-icon"><i class="fa-solid fa-hand-holding-heart text-[14px]" aria-hidden="true"></i></span>
                    <h3>Layanan ZISWAF</h3>
                    <p>Informasi rekening, QRIS, dan konfirmasi penyaluran zakat, infak, sedekah, dan wakaf.</p>
                </a>
                <a data-reveal class="help-card" href="{{ route('tpq') }}">
                    <span class="card-icon"><i class="fa-solid fa-book-quran text-[14px]" aria-hidden="true"></i></span>
                    <h3>Pendaftaran TPQ</h3>
                    <p>Informasi pendaftaran santri baru, jadwal mengaji, dan program TPQ Nurul Haqq.</p>
                </a>
                <a data-reveal class="help-card" href="{{ route('jadwal') }}">
                    <span class="card-icon"><i class="fa-solid fa-mosque text-[14px]" aria-hidden="true"></i></span>
                    <h3>Kebutuhan ibadah</h3>
                    <p>Jadwal sholat berjamaah, kajian rutin, ngaji kitab, dan agenda masjid.</p>
                </a>
            </div>

            @if ($whatsappUrl)
                <a data-reveal class="button button-dark contact-direct" href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer">Hubungi sekretariat</a>
            @endif
        </div>
    </section>

    <x-donation-cta />
@endsection
