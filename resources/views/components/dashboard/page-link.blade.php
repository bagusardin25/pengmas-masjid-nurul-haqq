{{-- Tombol "Lihat halaman" di kepala halaman kelola. --}}
@props(['href', 'label' => 'Lihat halaman'])

<a {{ $attributes->class(['dash-button', 'dash-button-ghost']) }} href="{{ $href }}" target="_blank" rel="noopener">
    {{ $label }}
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M14 3h7v7"></path>
        <path d="m10 14 11-11"></path>
        <path d="M19 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h6"></path>
    </svg>
</a>
