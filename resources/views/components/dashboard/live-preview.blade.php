{{--
    Pratinjau langsung halaman publik versi HP di sisi kanan dashboard (hanya di layar lebar).
    Halaman yang ditampilkan mengikuti halaman kelola yang sedang dibuka; setelah form disimpan,
    halaman dimuat ulang sehingga pratinjau ikut menampilkan isi terbaru.
--}}
@props(['url' => null])

@php
    $routeName = request()->route()?->getName();

    $previewUrl = $url ?? match (true) {
        $routeName === 'dashboard.profil' => route('profil'),
        $routeName === 'dashboard.jadwal' => route('jadwal'),
        $routeName === 'dashboard.tpq' => route('tpq'),
        $routeName === 'dashboard.ziswaf' => route('ziswaf'),
        $routeName === 'dashboard.galeri' => route('galeri'),
        $routeName === 'dashboard.kontak' => route('kontak'),
        default => route('home'),
    };

    $previewPath = '/'.ltrim(parse_url($previewUrl, PHP_URL_PATH) ?? '', '/');
@endphp

<aside class="dash-preview" aria-label="Pratinjau halaman versi HP" x-data="livePreview(@js($previewUrl))" :class="{ 'is-collapsed': collapsed }">
    <button type="button" class="dash-preview-open" x-show="collapsed" x-cloak @click="toggle()">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="6" y="2" width="12" height="20" rx="2.5"></rect><path d="M11 18h2"></path></svg>
        <span>Pratinjau HP</span>
    </button>

    <div class="dash-preview-inner" x-show="!collapsed">
        <header class="dash-preview-header">
            <div>
                <strong>Pratinjau HP</strong>
                <span title="{{ $previewUrl }}">{{ $previewPath }}</span>
            </div>
            <div class="dash-preview-actions">
                <button type="button" @click="reload()" aria-label="Muat ulang pratinjau" title="Muat ulang">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12a9 9 0 1 1-2.6-6.4"></path><path d="M21 3v6h-6"></path></svg>
                </button>
                <a href="{{ $previewUrl }}" target="_blank" rel="noopener" aria-label="Buka halaman di tab baru" title="Buka di tab baru">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 3h7v7"></path><path d="m10 14 11-11"></path><path d="M19 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h6"></path></svg>
                </a>
                <button type="button" @click="toggle()" aria-label="Sembunyikan pratinjau" title="Sembunyikan">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"></path></svg>
                </button>
            </div>
        </header>

        <div class="dash-preview-stage" x-ref="stage">
            <div class="dash-preview-phone" :style="`--preview-scale: ${scale}`">
                <iframe x-ref="frame" title="Pratinjau {{ $previewPath }} versi HP" tabindex="-1" loading="lazy"
                    @load="loading = false"></iframe>
                <span class="dash-preview-loading" x-show="loading">Memuat…</span>
            </div>
        </div>
        <p class="dash-preview-note">Simpan perubahan untuk melihat hasilnya di sini.</p>
    </div>
</aside>
