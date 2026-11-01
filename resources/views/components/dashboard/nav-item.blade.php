@props([
    'label',
    'icon',
    'active' => false,
    'href' => null,
])

@php
    $tag = $href ? 'a' : 'span';
@endphp

<{{ $tag }}
    @if ($href) href="{{ $href }}" @else aria-disabled="true" @endif
    @if ($active) aria-current="page" @endif
    class="dashboard-nav-item"
>
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        @switch($icon)
            @case('dashboard')
                <rect x="3" y="3" width="8" height="8" rx="1.5"></rect>
                <rect x="13" y="3" width="8" height="5" rx="1.5"></rect>
                <rect x="13" y="10" width="8" height="11" rx="1.5"></rect>
                <rect x="3" y="13" width="8" height="8" rx="1.5"></rect>
                @break
            @case('home')
                <path d="m3 10 9-7 9 7"></path>
                <path d="M5 9v11h14V9"></path>
                <path d="M9 20v-6h6v6"></path>
                @break
            @case('mosque')
                <path d="M3 20h18"></path>
                <path d="M5 20v-9h14v9"></path>
                <path d="M4 11h16"></path>
                <path d="M8 11V8a4 4 0 0 1 8 0v3"></path>
                <path d="M12 4V2"></path>
                <path d="M10 20v-4h4v4"></path>
                @break
            @case('calendar')
                <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                <path d="M16 3v4M8 3v4M3 10h18"></path>
                <path d="M8 14h.01M12 14h.01M16 14h.01M8 17h.01M12 17h.01"></path>
                @break
            @case('book')
                <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21z"></path>
                <path d="M4 5.5v13A2.5 2.5 0 0 1 6.5 16H20"></path>
                @break
            @case('heart')
                <path d="m20.8 4.6 0 0a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1.1L12 21l7.8-7.5 1.1-1.1a5.5 5.5 0 0 0-.1-7.8z"></path>
                @break
            @case('image')
                <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                <path d="m21 15-5-5L5 21"></path>
                @break
            @case('phone')
                <path d="M22 16.9v3a2 2 0 0 1-2.2 2A19.8 19.8 0 0 1 3.1 5.2 2 2 0 0 1 5.1 3h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.5 2.1L9 11a16 16 0 0 0 4 4l1.3-1.3a2 2 0 0 1 2.1-.5c.9.3 1.9.6 2.9.7a2 2 0 0 1 1.7 2z"></path>
                @break
        @endswitch
    </svg>
    <span>{{ $label }}</span>
</{{ $tag }}>
