<header class="site-header">
    <div class="container header-inner">
        <a class="brand" href="{{ route('home') }}" aria-label="Masjid Jami Nurul Haqq, beranda">
            <span class="brand-mark" aria-hidden="true">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 20.5h18"></path><path d="M5.5 20.5v-6.2a6.5 6.5 0 0 1 13 0v6.2"></path><path d="M12 7.8V4.6"></path><circle cx="12" cy="3.4" r="1"></circle><path d="M10 20.5v-3a2 2 0 0 1 4 0v3"></path></svg>
            </span>
            <span class="brand-copy"><span>MASJID JAMI</span><strong>Nurul Haqq</strong></span>
        </a>

        <nav id="primary-navigation" class="primary-nav" aria-label="Navigasi utama">
            @foreach ([
                ['label' => 'Beranda', 'route' => 'home'],
                ['label' => 'Profil', 'route' => 'profil'],
                ['label' => 'Jadwal & Kegiatan', 'route' => 'jadwal'],
                ['label' => 'Program TPQ', 'route' => 'tpq'],
                ['label' => 'ZISWAF', 'route' => 'ziswaf'],
                ['label' => 'Galeri', 'route' => 'galeri'],
                ['label' => 'Kontak', 'route' => 'kontak'],
            ] as $item)
                <a href="{{ route($item['route']) }}"
                   @if (request()->routeIs($item['route'])) aria-current="page" @endif>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <a class="button button-dark header-donate" href="{{ route('ziswaf') }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path></svg>
            <span>Donasi ZISWAF</span>
        </a>

        <button class="nav-toggle" type="button" aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="primary-navigation">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>
