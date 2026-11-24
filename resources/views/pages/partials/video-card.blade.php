<article data-reveal class="video-card">
    <a class="video-thumb" href="{{ $video->watchUrl() }}" target="_blank" rel="noopener noreferrer">
        <img src="{{ $video->thumbnailUrl() }}" alt="" loading="lazy" decoding="async" width="480" height="360">
        <span class="video-play" aria-hidden="true"><i class="fa-solid fa-play text-[14px]" aria-hidden="true"></i></span>
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
