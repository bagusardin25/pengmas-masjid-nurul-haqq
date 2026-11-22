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
