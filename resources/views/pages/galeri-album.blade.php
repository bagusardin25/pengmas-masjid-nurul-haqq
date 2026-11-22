@extends('layouts.app')

@section('title', $album->title)
@section('description', $album->description ?: 'Album foto '.$album->title.' di Masjid Jami Nurul Haqq.')

@php
    $photos = $album->photos;
    $meta = collect([
        $album->category?->name,
        $album->date?->locale('id')->translatedFormat('l, j F Y'),
        $album->location,
        $photos->count().' foto',
    ])->filter()->join(' · ');

    $lightboxItems = $photos->map(fn ($photo) => [
        'src' => $photo->image->url(),
        'caption' => $photo->caption,
        'alt' => $photo->caption ?: $album->title,
    ])->values();
@endphp

@section('content')
    <section class="album-hero">
        <div class="site-container">
            <p class="breadcrumb"><a href="{{ route('home') }}">Beranda</a> / <a href="{{ route('galeri') }}">Galeri</a> / {{ $album->title }}</p>
            <p class="eyebrow">{{ $meta }}</p>
            <h1>{{ $album->title }}</h1>
            @if ($album->description)
                <p>{{ $album->description }}</p>
            @endif
        </div>
    </section>

    <section class="section section-tight" x-data="lightbox(@js($lightboxItems))" @keydown.escape.window="close()"
        @keydown.arrow-right.window="open !== null && next()" @keydown.arrow-left.window="open !== null && prev()">
        <div class="site-container">
            @if ($photos->isEmpty())
                <p class="gallery-empty">Foto album ini sedang disiapkan.</p>
            @else
                <ul class="album-photo-grid">
                    @foreach ($photos as $photo)
                        <li>
                            <figure>
                                <a href="{{ $photo->image->url() }}" @click.prevent="show({{ $loop->index }})">
                                    <img src="{{ $photo->image->url() }}" alt="{{ $photo->caption ?: $album->title.' — foto '.$loop->iteration }}"
                                        @if ($photo->image->width) width="{{ $photo->image->width }}" height="{{ $photo->image->height }}" @endif
                                        loading="lazy" decoding="async">
                                </a>
                                @if ($photo->caption)
                                    <figcaption>{{ $photo->caption }}</figcaption>
                                @endif
                            </figure>
                        </li>
                    @endforeach
                </ul>
            @endif

            <p class="report-back"><a href="{{ route('galeri') }}#album">← Kembali ke galeri</a></p>
        </div>

        <div class="lightbox" x-show="open !== null" x-cloak x-transition.opacity role="dialog" aria-modal="true" aria-label="Foto album"
            @click.self="close()">
            <button type="button" class="lightbox-close" @click="close()" aria-label="Tutup foto">&times;</button>
            <button type="button" class="lightbox-nav lightbox-prev" @click="prev()" x-show="items.length > 1" aria-label="Foto sebelumnya">‹</button>
            <figure class="lightbox-figure">
                <img :src="current?.src" :alt="current?.alt">
                <figcaption>
                    <span x-text="current?.caption"></span>
                    <small x-text="(open + 1) + ' / ' + items.length"></small>
                </figcaption>
            </figure>
            <button type="button" class="lightbox-nav lightbox-next" @click="next()" x-show="items.length > 1" aria-label="Foto berikutnya">›</button>
        </div>
    </section>

    @if ($otherAlbums->isNotEmpty())
        <section class="section section-muted">
            <div class="site-container">
                <x-section-heading eyebrow="Album lainnya" title="Dokumentasi kegiatan lain" />
                <div class="gallery-grid gallery-grid-three">
                    @foreach ($otherAlbums as $other)
                        @php $cover = $other->cover(); @endphp
                        <article class="gallery-card">
                            <a class="gallery-cover" href="{{ route('galeri.album', $other) }}">
                                @if ($cover)
                                    <img src="{{ $cover->url() }}" alt="" loading="lazy" decoding="async">
                                @endif
                                <span class="sr-only">Buka album {{ $other->title }}</span>
                            </a>
                            <div class="gallery-copy">
                                <p class="eyebrow">{{ $other->category?->name ?? 'Album foto' }} · {{ $other->photos_count }} foto</p>
                                <h3><a href="{{ route('galeri.album', $other) }}">{{ $other->title }}</a></h3>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
