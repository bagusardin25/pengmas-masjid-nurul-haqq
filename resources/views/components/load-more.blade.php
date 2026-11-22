{{--
    Tombol "Muat lebih banyak" di halaman publik. Tanpa JavaScript, tautannya memuat ulang halaman
    dengan item lebih banyak; dengan JavaScript, kartu berikutnya ditambahkan ke grid (data-load-more-grid).
--}}
@props(['url', 'label'])

<div class="load-more" x-data="publicLoadMore()" data-url="{{ $url }}" data-param="{{ $label }}">
    {{-- Tetap tampil dan berfungsi sebagai tautan biasa bila JS-nya versi lama atau gagal dimuat. --}}
    <a class="button button-outline load-more-button" href="{{ $url }}"
        x-show="typeof url === 'undefined' || url" @click="typeof load === 'function' && load($event)"
        :aria-busy="typeof busy !== 'undefined' && busy ? 'true' : 'false'" :class="{ 'is-loading': typeof busy !== 'undefined' && busy }">
        <span x-show="typeof busy === 'undefined' || !busy">Muat lebih banyak {{ $label }}</span>
        <span x-show="typeof busy !== 'undefined' && busy" x-cloak>Memuat…</span>
        <svg x-show="typeof busy === 'undefined' || !busy" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"></path></svg>
    </a>
    <p class="load-more-status" role="status" x-text="typeof status === 'undefined' ? '' : status"></p>
</div>
