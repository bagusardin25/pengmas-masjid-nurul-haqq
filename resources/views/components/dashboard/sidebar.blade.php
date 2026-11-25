<aside class="dashboard-sidebar" aria-label="Navigasi dashboard" x-bind:class="{ 'is-open': sidebarOpen }">
    <a class="dashboard-brand" href="{{ route('home') }}">
        <img src="{{ asset('images/icons/icon-512.png') }}" width="54" height="54" alt="logo jami nurul haqq"
            class="object-contain">
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
        <x-dashboard.nav-item label="Jadwal & Kegiatan" icon="calendar" :href="route('dashboard.jadwal')" :active="request()->routeIs('dashboard.jadwal*')" />
        <x-dashboard.nav-item label="Program TPQ" icon="book" :href="route('dashboard.tpq')" :active="request()->routeIs('dashboard.tpq*')" />
        <x-dashboard.nav-item label="ZISWAF" icon="heart" :href="route('dashboard.ziswaf')" :active="request()->routeIs('dashboard.ziswaf*')" />
        <x-dashboard.nav-item label="Galeri & Media" icon="image" :href="route('dashboard.galeri')" :active="request()->routeIs('dashboard.galeri*')" />
        <x-dashboard.nav-item label="Kontak" icon="phone" :href="route('dashboard.kontak')" :active="request()->routeIs('dashboard.kontak*')" />
        <x-dashboard.nav-item label="Tampilan Smart TV" icon="display" :href="route('tv.display')" />
    </nav>

    <p class="dashboard-sidebar-label">Pengaturan</p>

    <nav class="dashboard-sidebar-nav" aria-label="Pengaturan">
        <x-dashboard.nav-item label="Akun & Admin" icon="user" :href="route('profile.edit')" :active="request()->routeIs('profile.edit')" />
    </nav>


    <div class="dashboard-sidebar-footer">
        <strong>Yayasan Nurul Haqq</strong>
        Tawangsari, Taman, Sidoarjo<br>
        Panel pengelola · {{ date('Y') }}
    </div>
</aside>
