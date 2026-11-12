<aside class="dashboard-sidebar" aria-label="Navigasi dashboard" x-bind:class="{ 'is-open': sidebarOpen }">
    <a class="dashboard-brand" href="{{ route('home') }}">
        <span class="dashboard-brand-mark" aria-hidden="true">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 20.5h18"></path>
                <path d="M5.5 20.5v-6.2a6.5 6.5 0 0 1 13 0v6.2"></path>
                <path d="M12 7.8V4.6"></path>
                <circle cx="12" cy="3.4" r="1"></circle>
                <path d="M10 20.5v-3a2 2 0 0 1 4 0v3"></path>
            </svg>
        </span>
        <span class="dashboard-brand-copy">
            <span>MASJID JAMI</span>
            <strong>Nurul Haqq</strong>
        </span>
    </a>

    <p class="dashboard-sidebar-label">Kelola situs</p>

    <nav class="dashboard-sidebar-nav">
        <x-dashboard.nav-item label="Dashboard" icon="dashboard" :href="route('dashboard')" :active="request()->routeIs('dashboard')" />
        <x-dashboard.nav-item label="Kelola Beranda" icon="home" :href="route('dashboard.beranda')" :active="request()->routeIs('dashboard.beranda')" />
        <x-dashboard.nav-item label="Profil Masjid" icon="mosque" :href="route('dashboard.profil')" :active="request()->routeIs('dashboard.profil*')" />
        <x-dashboard.nav-item label="Jadwal & Kegiatan" icon="calendar" />
        <x-dashboard.nav-item label="Program TPQ" icon="book" />
        <x-dashboard.nav-item label="ZISWAF" icon="heart" />
        <x-dashboard.nav-item label="Galeri & Media" icon="image" />
        <x-dashboard.nav-item label="Kontak" icon="phone" />
    </nav>


    <div class="dashboard-sidebar-footer">
        <strong>Yayasan Nurul Haqq</strong>
        Tawangsari, Taman, Sidoarjo<br>
        Panel pengelola · {{ date('Y') }}
    </div>
</aside>
