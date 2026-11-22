@extends('layouts.app')

@section('title', 'Galeri')
@section('description', 'Album foto, video kajian, dan artikel kegiatan Masjid Jami Nurul Haqq.')

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
                <div class="gallery-grid">
                    @foreach ($albums as $album)
                        @php $cover = $album->cover(); @endphp
                        <article class="gallery-card">
                            <a class="gallery-cover" href="{{ route('galeri.album', $album) }}">
                                @if ($cover)
                                    <img src="{{ $cover->url() }}" alt="" loading="lazy" decoding="async"
                                        @if ($cover->width) width="{{ $cover->width }}" height="{{ $cover->height }}" @endif>
                                @endif
                                @if ($album->date)
                                    <span>{{ $album->date->locale('id')->translatedFormat('j F Y') }}</span>
                                @endif
                                <span class="sr-only">Buka album {{ $album->title }}</span>
                            </a>
                            <div class="gallery-copy">
                                <p class="eyebrow">{{ $album->category?->name ?? 'Album foto' }} · {{ $album->photos_count }} foto</p>
                                <h3><a href="{{ route('galeri.album', $album) }}">{{ $album->title }}</a></h3>
                                @if ($album->description)
                                    <p>{{ \Illuminate\Support\Str::limit($album->description, 160) }}</p>
                                @endif
                                @if ($album->location)
                                    <small>{{ $album->location }}</small>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>

                @if ($albums->hasPages())
                    <div class="report-pagination">{{ $albums->links() }}</div>
                @endif
            @endif
        </div>
    </section>

    @if ($videos->isNotEmpty() || filled($setting?->youtube_url))
        <section class="section">
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
                    <div class="card-grid card-grid-three">
                        @foreach ($videos as $video)
                            <article class="video-card">
                                <a class="video-thumb" href="{{ $video->watchUrl() }}" target="_blank" rel="noopener noreferrer">
                                    <img src="{{ $video->thumbnailUrl() }}" alt="" loading="lazy" decoding="async" width="480" height="360">
                                    <span class="video-play" aria-hidden="true">▶</span>
                                    @if ($video->duration)
                                        <small>{{ $video->duration }}</small>
                                    @endif
                                    <span class="sr-only">Tonton {{ $video->title }} di YouTube</span>
                                </a>
                                <div class="video-copy">
                                    <p class="eyebrow">{{ $video->category?->name ?? 'Video' }}</p>
                                    <h3>{{ $video->title }}</h3>
                                    @if ($video->description)
                                        <p>{{ \Illuminate\Support\Str::limit($video->description, 140) }}</p>
                                    @endif
                                    <small>
                                        {{ $video->speaker ?? 'Masjid Nurul Haqq' }}@if ($video->date) · {{ $video->date->locale('id')->translatedFormat('j F Y') }}@endif
                                    </small>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    @endif

    @if ($articles->isNotEmpty())
        <section @class(['section', 'section-muted' => $videos->isNotEmpty() || filled($setting?->youtube_url)])>
            <div class="site-container">
                <x-section-heading eyebrow="Artikel kegiatan" title="Tulisan dari jamaah" />
                <div class="card-grid card-grid-two">
                    @foreach ($articles as $article)
                        <article class="article-card">
                            <a class="article-image" href="{{ route('galeri.artikel', $article) }}" tabindex="-1" aria-hidden="true">
                                @if ($article->coverImage)
                                    <img src="{{ $article->coverImage->url() }}" alt="" loading="lazy" decoding="async">
                                @endif
                            </a>
                            <div class="article-copy">
                                <p class="article-meta">
                                    {{ collect([$article->category, $article->published_at?->locale('id')->translatedFormat('j F Y'), $article->readingMinutes().' menit baca'])->filter()->join(' · ') }}
                                </p>
                                <h3><a href="{{ route('galeri.artikel', $article) }}">{{ $article->title }}</a></h3>
                                @if ($article->summary())
                                    <p>{{ $article->summary() }}</p>
                                @endif
                                @if ($article->author)
                                    <strong>● {{ $article->author }}</strong>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
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
