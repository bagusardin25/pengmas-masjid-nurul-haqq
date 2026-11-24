@props(['name' => ''])

@php
    $normalized = \Illuminate\Support\Str::lower((string) $name);

    $icon = match (true) {
        str_contains($normalized, 'sholat') || str_contains($normalized, 'ibadah') => 'fa-mosque',
        str_contains($normalized, 'wudhu') => 'fa-faucet-drip',
        str_contains($normalized, 'perpustakaan') || str_contains($normalized, 'baca') => 'fa-book-open',
        str_contains($normalized, 'quran') || str_contains($normalized, "qur'an") => 'fa-book-quran',
        str_contains($normalized, 'internet') || str_contains($normalized, 'wifi') || str_contains($normalized, 'wi-fi') => 'fa-wifi',
        str_contains($normalized, 'parkir') => 'fa-square-parking',
        str_contains($normalized, 'toilet') || str_contains($normalized, 'wc') || str_contains($normalized, 'kamar mandi') => 'fa-restroom',
        str_contains($normalized, 'ac') || str_contains($normalized, 'pendingin') => 'fa-snowflake',
        str_contains($normalized, 'sound') || str_contains($normalized, 'audio') => 'fa-volume-high',
        str_contains($normalized, 'aula') || str_contains($normalized, 'serbaguna') => 'fa-building',
        str_contains($normalized, 'taman') || str_contains($normalized, 'halaman') => 'fa-tree',
        str_contains($normalized, 'difabel') || str_contains($normalized, 'kursi roda') => 'fa-wheelchair',
        default => 'fa-check',
    };
@endphp

<i class="fa-solid {{ $icon }}" aria-hidden="true"></i>
