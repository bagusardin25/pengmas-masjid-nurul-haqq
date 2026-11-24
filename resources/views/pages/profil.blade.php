@extends('layouts.app')

@section('title', 'Profil Masjid')
@section('description', 'Profil, fasilitas, sejarah, dan kepengurusan Masjid Jami Nurul Haqq.')

@php
    $historyParagraphs = filled($about?->history_content)
        ? preg_split('/\R{2,}/', trim($about->history_content))
        : [];
@endphp

@section('content')
    <x-page-hero eyebrow="Profil" :title="$about?->hero_title ?? 'Profil Masjid Jami Nurul Haqq'" :description="$about?->hero_description" />

    <section class="section">
        <div class="site-container profile-overview">
            <div data-reveal>
                <x-section-heading eyebrow="Tentang Nurul Haqq" :title="$about?->intro_title ?? 'Masjid Jami Nurul Haqq'" :description="$about?->intro_description" />
            </div>
            @if ($stats->isNotEmpty())
                <div class="stat-grid" data-reveal-group>
                    @foreach ($stats as $stat)
                        <article data-reveal class="stat-card"><span class="card-icon" aria-hidden="true">{{ $stat->icon?->glyph() ?? '✓' }}</span><strong>{{ $stat->value }}</strong><span>{{ $stat->label }}</span></article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    @if ($roles->isNotEmpty())
        <section class="section section-muted">
            <div class="site-container">
                <div data-reveal><x-section-heading eyebrow="Bersama jamaah" title="Lebih dari tempat sholat" /></div>
                <div class="card-grid card-grid-three" data-reveal-group>
                    @foreach ($roles as $role)
                        <article data-reveal class="info-card"><span class="card-icon" aria-hidden="true">{{ $role->icon?->glyph() ?? '✓' }}</span><h3>{{ $role->title }}</h3>@if ($role->description)<p>{{ $role->description }}</p>@endif</article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($facilities->isNotEmpty())
        <section class="section">
            <div class="site-container">
                <div data-reveal><x-section-heading eyebrow="Fasilitas" title="Nyaman untuk beribadah" description="Sarana yang tersedia untuk kenyamanan ibadah jamaah." /></div>
                <div class="facility-grid" data-reveal-group>
                    @foreach ($facilities as $facility)
                        <div data-reveal class="facility-item"><span class="facility-mark"><x-facility-icon :name="$facility->name" /></span>{{ $facility->name }}</div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section section-muted">
        <div class="site-container">
            <div data-reveal><x-section-heading eyebrow="Sejarah & kepengurusan" title="Mengenal perjalanan masjid" /></div>
            @php($profilContact = $siteContact->setting())
            <div class="history-grid" data-reveal-group>
                <article data-reveal class="content-panel history-story-panel">
                    <div>
                        <h3>{{ $about?->history_title ?: 'Sejarah' }}</h3>
                        @forelse ($historyParagraphs as $paragraph)
                            <p>{!! nl2br(e($paragraph)) !!}</p>
                        @empty
                            <p>Sejarah masjid belum ditambahkan.</p>
                        @endforelse
                    </div>
                    <div class="history-footer">
                        @if (filled($profilContact?->address) || filled($profilContact?->service_hours))
                            <div class="history-meta-box">
                                @if (filled($profilContact?->address))
                                    <div>
                                        <small>Lokasi masjid</small>
                                        <strong>{{ $profilContact->address }}</strong>
                                    </div>
                                @endif
                                @if (filled($profilContact?->service_hours))
                                    <div>
                                        <small>Layanan sekretariat</small>
                                        <strong>{{ $profilContact->service_hours }}</strong>
                                    </div>
                                @endif
                            </div>
                        @endif
                        <div class="button-row">
                            <a class="button button-dark" href="{{ $siteContact->contactUrl() }}">☎ Hubungi takmir</a>
                            <a class="button button-outline" href="{{ route('jadwal') }}">Jadwal &amp; kegiatan</a>
                        </div>
                    </div>
                </article>
                <article data-reveal class="content-panel">
                    <h3>Struktur organisasi</h3>
                    @forelse ($members as $member)
                        <div class="organization-row"><span>{{ $member->position }}@if ($member->note)<small>{{ $member->note }}</small>@endif</span><strong>{{ $member->name }}</strong></div>
                    @empty
                        <p>Data kepengurusan belum ditambahkan.</p>
                    @endforelse
                </article>
            </div>
        </div>
    </section>

    <x-donation-cta />
@endsection
