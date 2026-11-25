@extends('layouts.app')

@section('title', 'Galeri')
@section('description', 'Album foto, video kajian, dan artikel kegiatan Masjid Nurul Haqq.')

@php
    $setting = $siteContact->setting();
@endphp

@section('content')
    <x-page-hero eyebrow="Galeri" title="Momen kebersamaan jamaah" description="Album foto, video kajian, dan artikel kegiatan Masjid Nurul Haqq." />

    <section id="album" class="section section-muted">
        <div class="site-container">
            <div class="gallery-heading">
                <x-section-heading eyebrow="Galeri kegiatan" title="Dari ibadah hingga belajar mengaji" />
            </div>

            @if ($categories->isNotEmpty())
                <nav class="filter-list" aria-label="Kategori galeri">
                    <a href="{{ route('galeri') }}#album" @class(['filter-chip', 'filter-chip-active' => $activeCategory === null])
                        @if ($activeCategory === null) aria-current="page" @endif>Semua</a>
                    @foreach ($categories as $category)
                        <a href="{{ route('galeri', ['kategori' => $category->slug]) }}#album"
                            @class(['filter-chip', 'filter-chip-active' => $activeCategory?->is($category)])
                            @if ($activeCategory?->is($category)) aria-current="page" @endif>{{ $category->name }}</a>
                    @endforeach
                </nav>
            @endif

            @if ($albums->isEmpty())
                <p class="gallery-empty">
                    @if ($activeCategory)
                        Belum ada album di kategori {{ $activeCategory->name }}. <a href="{{ route('galeri') }}#album">Lihat semua album</a>.
                    @else
                        Dokumentasi kegiatan sedang disiapkan pengurus. Silakan kembali lagi nanti.
                    @endif
                </p>
            @else
                <div class="gallery-grid" data-load-more-grid>
                    @foreach ($albums as $album)
                        @include('pages.partials.album-card')
                    @endforeach
                </div>
                @if ($moreAlbumsUrl)
                    <x-load-more :url="$moreAlbumsUrl" label="album" />
                @endif
            @endif
        </div>
    </section>

    @if ($videos->isNotEmpty() || filled($setting?->youtube_url))
        <section id="video" class="section">
            <div class="site-container">
                <div class="gallery-heading">
                    <x-section-heading eyebrow="Video kajian" title="Tonton kajian pilihan" />
                    @if (filled($setting?->youtube_url))
                        <a class="channel-card" href="{{ $setting->youtube_url }}" target="_blank" rel="noopener noreferrer">
                            <span class="channel-avatar">NH</span>
                            <span><strong>Nurul Haqq</strong><small>Kanal YouTube masjid</small></span>
                            <span class="tag">Kunjungi kanal</span>
                        </a>
                    @endif
                </div>
                @if ($videos->isEmpty())
                    <p class="gallery-empty">Video kajian belum tersedia di sini. Kunjungi kanal YouTube masjid untuk menonton kajian terbaru.</p>
                @else
                    <div class="card-grid card-grid-three" data-load-more-grid>
                        @foreach ($videos as $video)
                            @include('pages.partials.video-card')
                        @endforeach
                    </div>
                    @if ($moreVideosUrl)
                        <x-load-more :url="$moreVideosUrl" label="video" />
                    @endif
                @endif
            </div>
        </section>
    @endif

    @if ($articles->isNotEmpty())
        <section id="artikel" @class(['section', 'section-muted' => $videos->isNotEmpty() || filled($setting?->youtube_url)])>
            <div class="site-container">
                <x-section-heading eyebrow="Artikel kegiatan" title="Tulisan dari jamaah" />
                <div class="card-grid card-grid-two" data-load-more-grid>
                    @foreach ($articles as $article)
                        @include('pages.partials.article-card')
                    @endforeach
                </div>
                @if ($moreArticlesUrl)
                    <x-load-more :url="$moreArticlesUrl" label="artikel" />
                @endif
            </div>
        </section>
    @endif

    <section class="section">
        <div class="site-container gallery-contact">
            <div><h2>Ingin mengetahui kegiatan masjid?</h2><p>Lihat jadwal kegiatan rutin atau hubungi pengurus untuk informasi dokumentasi yang tersedia.</p></div>
            <div class="button-row">
                <a class="button button-dark" href="{{ route('jadwal') }}">Lihat jadwal</a>
                <a class="button button-outline" href="{{ $siteContact->contactUrl() }}">Hubungi pengurus</a>
            </div>
        </div>
    </section>
    <x-donation-cta />
@endsection
