<header class="site-header">
    <div class="site-container header-inner">
        <a class="brand" href="{{ route('home') }}" aria-label="Masjid Nurul Haqq, beranda">
            <img src="{{ asset('images/icons/icon-512.png') }}" width="54" height="54" alt="logo nurul haqq"
                class="object-contain">
            <span class="brand-copy"><span>MASJID</span><strong>Nurul Haqq</strong></span>
        </a>

        <nav id="primary-navigation" class="primary-nav" aria-label="Navigasi utama">
            @foreach ([['label' => 'Beranda', 'route' => 'home'], ['label' => 'Profil', 'route' => 'profil'], ['label' => 'Jadwal & Kegiatan', 'route' => 'jadwal'], ['label' => 'Program TPQ', 'route' => 'tpq'], ['label' => 'ZISWAF', 'route' => 'ziswaf'], ['label' => 'Galeri', 'route' => 'galeri'], ['label' => 'Kontak', 'route' => 'kontak']] as $item)
                <a href="{{ route($item['route']) }}" @if (request()->routeIs($item['route'], $item['route'] . '.*')) aria-current="page" @endif>
                    {{ $item['label'] }}
                </a>
            @endforeach
            {{-- <a class="header-login-link-mobile"
                href="{{ url('/login') }}">{{ Auth::user() == null ? 'Login' : 'Dashboard' }}</a> --}}
        </nav>

        {{-- <a class="header-login-link" href="{{ url('/login') }}">{{ Auth::user() == null ? 'Login' : 'Dashboard' }}</a> --}}
        <a class="button button-dark header-donate" href="{{ route('ziswaf') }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path
                    d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                </path>
            </svg>
            <span>Donasi ZISWAF</span>
        </a>

        <button class="nav-toggle" type="button" aria-label="Buka menu navigasi" aria-expanded="false"
            aria-controls="primary-navigation">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>
