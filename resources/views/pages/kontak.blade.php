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
            <p class="mb-5">Untuk layanan ZISWAF, pendaftaran TPQ, dan kebutuhan ibadah lainnya. Mari terhubung dengan
                Masjid Nurul Haqq.</p>
            @if ($whatsappUrl)
                <a class="button button-gold" href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer">Tanya via
                    WhatsApp</a>
            @endif
        </div>
    </section>

    <section class="section">
        <div class="site-container contact-grid">
            <div class="contact-left">
                <p class="eyebrow">Kontak kami</p>
                <h2>Kami siap terhubung</h2>
                <p class="contact-intro">Pilih saluran yang sesuai dengan kebutuhan Anda.</p>

                <div class="contact-list">
                    @foreach ($phones as $phone)
                        <a class="contact-item" href="{{ $phone->url() }}"
                            @if ($phone->is_whatsapp) target="_blank" rel="noopener noreferrer" @endif>
                            <span class="contact-icon" aria-hidden="true">{{ $phone->is_whatsapp ? '✆' : '☎' }}</span>
                            <span class="contact-meta">
                                <small>{{ $phone->label }}{{ $phone->is_whatsapp ? ' · WhatsApp' : '' }}</small>
                                <strong>{{ $phone->number }}</strong>
                            </span>
                            <span class="contact-arrow" aria-hidden="true">→</span>
                        </a>
                    @endforeach

                    @if (filled($contact?->email))
                        <a class="contact-item" href="mailto:{{ $contact->email }}">
                            <span class="contact-icon" aria-hidden="true">✉</span>
                            <span class="contact-meta">
                                <small>Email</small>
                                <strong>{{ $contact->email }}</strong>
                            </span>
                            <span class="contact-arrow" aria-hidden="true">→</span>
                        </a>
                    @endif

                    @if ($contact?->instagramUrl())
                        <a class="contact-item" href="{{ $contact->instagramUrl() }}" target="_blank"
                            rel="noopener noreferrer">
                            <span class="contact-icon" aria-hidden="true">◎</span>
                            <span class="contact-meta">
                                <small>Instagram</small>
                                <strong>{{ '@' . $contact->instagram }}</strong>
                            </span>
                            <span class="contact-arrow" aria-hidden="true">→</span>
                        </a>
                    @endif

                    @if (filled($contact?->youtube_url))
                        <a class="contact-item" href="{{ $contact->youtube_url }}" target="_blank"
                            rel="noopener noreferrer">
                            <span class="contact-icon" aria-hidden="true">▶</span>
                            <span class="contact-meta">
                                <small>YouTube</small>
                                <strong>Kanal Masjid Nurul Haqq</strong>
                            </span>
                            <span class="contact-arrow" aria-hidden="true">→</span>
                        </a>
                    @endif

                    @if (filled($contact?->service_hours))
                        <div class="contact-item static-item">
                            <span class="contact-meta">
                                <small>Layanan sekretariat</small>
                                <strong>{{ $contact->service_hours }}</strong>
                            </span>
                        </div>
                    @endif

                    @if ($phones->isEmpty() && blank($contact?->email))
                        <p class="gallery-empty">Kontak pengurus sedang diperbarui. Silakan datang langsung ke sekretariat
                            masjid.</p>
                    @endif
                </div>
            </div>

            <div class="contact-right">
                @if (filled($contact?->maps_embed_url))
                    <div class="map-embed h-[340px]">
                        <iframe src="{{ $contact->maps_embed_url }}" title="Peta lokasi Masjid Nurul Haqq di Google Maps"
                            loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                    </div>
                @else
                    <div class="map-panel">
                        <div class="map-icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                <circle cx="12" cy="10" r="2.5"></circle>
                            </svg>
                        </div>
                        <div class="map-content">
                            <h3>Masjid Nurul Haqq</h3>
                            <p>Perlu bantuan? Silakan kunjungi kami di lokasi atau hubungi pengurus untuk informasi lebih
                                detail.</p>
                        </div>
                    </div>
                @endif

                @if (filled($contact?->address) || $mapsLink)
                    <div class="mt-[16px] flex flex-wrap items-center justify-between [gap:12px_20px]">
                        @if (filled($contact?->address))
                            <p
                                class="[margin:0] [flex:1_1_260px] flex [gap:10px] items-start text-[15px] leading-[1.6] text-[#42524B]">
                                <span class="shrink-0 mt-[1px] text-[#8A6A12] flex">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                        stroke-linejoin="round" aria-hidden="true">
                                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                </span>
                                {{ $contact->address }}
                            </p>
                        @endif
                        @if ($mapsLink)
                            <a href="{{ $mapsLink }}" target="_blank" rel="noopener noreferrer"
                                class="inline-flex items-center [gap:8px] min-h-[48px] [padding:0_20px] rounded-[14px] [border:1.5px_solid_#0B3B2C] text-[#0B3B2C] text-[15px] font-bold no-underline">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M15 3h6v6"></path>
                                    <path d="M10 14 21 3"></path>
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6">
                                    </path>
                                </svg>
                                Buka di Google Maps
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="section section-muted">
        <div class="site-container">
            <p class="eyebrow">Layanan jamaah</p>
            <h2 class="section-heading-inline">Ada yang bisa kami bantu?</h2>

            <div class="help-grid">
                <a class="help-card" href="{{ route('ziswaf') }}">
                    <span class="card-icon">♡</span>
                    <h3>Layanan ZISWAF</h3>
                    <p>Informasi rekening, QRIS, dan konfirmasi penyaluran zakat, infak, sedekah, dan wakaf.</p>
                </a>
                <a class="help-card" href="{{ route('tpq') }}">
                    <span class="card-icon">▤</span>
                    <h3>Pendaftaran TPQ</h3>
                    <p>Informasi pendaftaran santri baru, jadwal mengaji, dan program TPQ Nurul Haqq.</p>
                </a>
                <a class="help-card" href="{{ route('jadwal') }}">
                    <span class="card-icon">⌂</span>
                    <h3>Kebutuhan ibadah</h3>
                    <p>Jadwal sholat berjamaah, kajian rutin, ngaji kitab, dan agenda masjid.</p>
                </a>
            </div>

            @if ($whatsappUrl)
                <a class="button button-dark contact-direct" href="{{ $whatsappUrl }}" target="_blank"
                    rel="noopener noreferrer">Hubungi sekretariat</a>
            @endif
        </div>
    </section>

    <x-donation-cta />
@endsection
