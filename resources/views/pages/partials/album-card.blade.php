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
