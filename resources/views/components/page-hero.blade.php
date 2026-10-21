@props([
    'eyebrow',
    'title',
    'description',
    'actionUrl' => null,
    'actionLabel' => null,
])

<section class="page-hero">
    <div class="container">
        <p class="breadcrumb"><a href="{{ route('home') }}">Beranda</a> / {{ $eyebrow }}</p>
        <p class="eyebrow eyebrow-light">{{ $eyebrow }}</p>
        <h1>{{ $title }}</h1>
        <p class="hero-description">{{ $description }}</p>
        @if ($actionUrl && $actionLabel)
            <a class="button button-gold" href="{{ $actionUrl }}">{{ $actionLabel }} <span aria-hidden="true">→</span></a>
        @endif
    </div>
</section>
