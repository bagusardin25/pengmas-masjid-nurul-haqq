@extends('layouts.app')

@section('title', $article->title)
@section('description', $article->summary(160) ?: 'Artikel kegiatan Masjid Jami Nurul Haqq.')

@php
    $meta = collect([
        $article->category,
        $article->published_at?->locale('id')->translatedFormat('j F Y'),
        $article->readingMinutes().' menit baca',
    ])->filter()->join(' · ');
@endphp

@section('content')
    <article>
        <header class="album-hero">
            <div class="site-container article-container">
                <p class="breadcrumb"><a href="{{ route('home') }}">Beranda</a> / <a href="{{ route('galeri') }}">Galeri</a> / Artikel</p>
                <p class="eyebrow">{{ $meta }}</p>
                <h1>{{ $article->title }}</h1>
                @if ($article->author)
                    <p class="article-byline">Ditulis oleh <strong>{{ $article->author }}</strong></p>
                @endif
            </div>
        </header>

        <div class="section section-tight">
            <div class="site-container article-container">
                @if ($article->coverImage)
                    <figure class="article-cover">
                        <img src="{{ $article->coverImage->url() }}" alt="{{ $article->coverImage->alt ?: $article->title }}"
                            @if ($article->coverImage->width) width="{{ $article->coverImage->width }}" height="{{ $article->coverImage->height }}" @endif
                            decoding="async">
                    </figure>
                @endif

                <div class="article-body">
                    @if ($article->excerpt)
                        <p class="article-lead">{{ $article->excerpt }}</p>
                    @endif
                    @foreach ($article->paragraphs() as $paragraph)
                        <p>{!! nl2br(e($paragraph)) !!}</p>
                    @endforeach
                </div>

                <p class="report-back"><a href="{{ route('galeri') }}">← Kembali ke galeri</a></p>
            </div>
        </div>
    </article>

    @if ($otherArticles->isNotEmpty())
        <section class="section section-muted">
            <div class="site-container">
                <x-section-heading eyebrow="Artikel lainnya" title="Tulisan dari jamaah" />
                <ul class="article-list">
                    @foreach ($otherArticles as $other)
                        <li>
                            <a href="{{ route('galeri.artikel', $other) }}">
                                <strong>{{ $other->title }}</strong>
                                <small>{{ collect([$other->author, $other->published_at?->locale('id')->translatedFormat('j F Y')])->filter()->join(' · ') }}</small>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif
@endsection
