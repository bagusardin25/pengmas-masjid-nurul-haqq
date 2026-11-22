@extends('layouts.app')

@section('title', 'Program TPQ')
@section('description', 'Program Taman Pendidikan Al-Qur’an (TPQ) Nurul Haqq dengan metode Tartil di Masjid Jami Nurul Haqq.')

@php
    $registrationUrl = $tpq?->registrationUrl();
    $whatsappUrl = $tpq?->whatsappUrl();
    $details = array_filter([
        'Usia' => $tpq?->info_age_range,
        'Jadwal' => $tpq?->info_schedule,
        'Biaya' => $tpq?->info_fee,
    ]);
@endphp

@section('content')
    <x-page-hero eyebrow="Program TPQ" :title="$tpq?->hero_title ?? 'Program TPQ'" :description="$tpq?->hero_description" :action-url="$registrationUrl" action-label="Daftar TPQ" />

    @if ($pillars->isNotEmpty())
        <section class="section">
            <div class="site-container">
                <x-section-heading eyebrow="Program unggulan" title="Belajar mengaji, membangun akhlak" />
                <div class="card-grid card-grid-two">
                    @foreach ($pillars as $pillar)
                        <article class="info-card"><span class="card-icon" aria-hidden="true">{{ $pillar->icon?->glyph() ?? '✓' }}</span><h3>{{ $pillar->title }}</h3>@if ($pillar->description)<p>{{ $pillar->description }}</p>@endif</article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section section-muted">
        <div class="site-container registration-grid">
            <div>
                <x-section-heading eyebrow="Informasi program" title="Kenali program sebelum mendaftar" :description="$tpq?->info_description" />
                @if ($details)
                    <dl class="detail-list">
                        @foreach ($details as $term => $value)
                            <div><dt>{{ $term }}</dt><dd>{{ $value }}</dd></div>
                        @endforeach
                    </dl>
                @endif
            </div>
            <aside class="registration-card">
                <p class="eyebrow eyebrow-light">Pendaftaran TPQ</p>
                <h2>{{ $tpq?->registration_title ?: 'Pendaftaran santri baru' }}</h2>
                @if ($tpq?->registration_description)
                    <p>{{ $tpq->registration_description }}</p>
                @endif
                @if ($tpq?->registration_phone)
                    <strong>{{ $tpq->registration_phone }}</strong>
                @endif
                @if ($registrationUrl)
                    <a class="button button-gold button-full" href="{{ $registrationUrl }}">Daftar TPQ</a>
                @endif
                @if ($whatsappUrl)
                    <a class="button button-outline-light button-full" href="{{ $whatsappUrl }}">Tanya via WhatsApp</a>
                @endif
            </aside>
        </div>
    </section>

    @if ($faqs->isNotEmpty())
        <section class="section">
            <div class="site-container">
                <x-section-heading eyebrow="Sebelum mendaftar" title="Pertanyaan yang sering diajukan" />
                <div @class(['card-grid', 'card-grid-three' => $faqs->count() % 3 === 0, 'card-grid-two' => $faqs->count() % 3 !== 0])>
                    @foreach ($faqs as $faq)
                        <article class="question-card"><h3>{{ $faq->question }}</h3><p>{{ $faq->answer }}</p></article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($documentations->isNotEmpty())
        <section @class(['section', 'section-muted' => $faqs->isNotEmpty()])>
            <div class="site-container">
                <x-section-heading eyebrow="Dokumentasi" title="Kegiatan belajar santri" />
                <ul class="documentation-grid">
                    @foreach ($documentations as $documentation)
                        @php $caption = $documentation->caption ?: $documentation->image->alt; @endphp
                        <li>
                            <figure>
                                <a href="{{ $documentation->image->url() }}" target="_blank" rel="noopener">
                                    <img src="{{ $documentation->image->url() }}" alt="{{ $caption ?: 'Dokumentasi kegiatan TPQ Nurul Haqq' }}"
                                        @if ($documentation->image->width) width="{{ $documentation->image->width }}" height="{{ $documentation->image->height }}" @endif
                                        loading="lazy" decoding="async">
                                </a>
                                @if ($caption)
                                    <figcaption>{{ $caption }}</figcaption>
                                @endif
                            </figure>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    <x-donation-cta />
@endsection
