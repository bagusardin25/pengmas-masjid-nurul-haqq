<header class="dashboard-topbar">
    <button class="dashboard-mobile-menu" type="button" aria-label="Buka menu navigasi" x-bind:aria-expanded="sidebarOpen"
        @click="sidebarOpen = !sidebarOpen">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
            stroke-linecap="round" aria-hidden="true">
            <path d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>

    <nav class="dashboard-breadcrumbs" aria-label="Breadcrumb">
        <a href="{{ route('dashboard.index') }}" aria-label="Dashboard">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="m3 10 9-7 9 7"></path>
                <path d="M5 9v11h14V9"></path>
                <path d="M9 20v-6h6v6"></path>
            </svg>
        </a>
        <span aria-hidden="true">›</span>
        <span aria-current="page">@yield('breadcrumb', 'Dashboard')</span>
    </nav>

    <div class="dashboard-topbar-actions">
        <a class="dashboard-site-link" href="{{ route('home') }}">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M14 3h7v7"></path>
                <path d="m10 14 11-11"></path>
                <path d="M19 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h6"></path>
            </svg>
            Lihat situs
        </a>

        <details class="dashboard-user-menu">
            <summary class="dashboard-user-trigger">
                <span class="dashboard-user-avatar" aria-hidden="true">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </span>
                <span class="dashboard-user-copy">
                    <strong>{{ Auth::user()->name }}</strong>
                    <span>Administrator</span>
                </span>
                <svg class="dashboard-user-chevron" width="13" height="13" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="m7 10 5 5 5-5"></path>
                </svg>
            </summary>
            <div class="dashboard-user-dropdown">
                <a href="{{ route('profile.edit') }}">Pengaturan akun</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Keluar</button>
                </form>
            </div>
        </details>
    </div>
</header>
