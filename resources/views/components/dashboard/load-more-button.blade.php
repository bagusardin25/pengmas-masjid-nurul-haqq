{{-- Tombol "Tampilkan lebih banyak" untuk daftar di dalam x-data="loadMore(...)". --}}
@props(['label' => 'item'])

<div class="dash-load-more" x-show="total > step" x-cloak>
    <button type="button" class="dash-button dash-button-ghost" x-show="remaining > 0" @click="more()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"></path></svg>
        <span x-text="`Tampilkan ${Math.min(step, remaining)} {{ $label }} lagi`"></span>
        <small x-text="`(${remaining} tersisa)`"></small>
    </button>
    <button type="button" class="dash-text-button" x-show="remaining === 0" @click="less()">Ringkas daftar</button>
    <span class="dash-load-more-count" x-text="`Menampilkan ${Math.min(shown, total)} dari ${total}`"></span>
</div>
