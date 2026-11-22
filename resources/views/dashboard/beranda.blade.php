@extends('layouts.dashboard')

@section('title', 'Kelola Beranda')
@section('breadcrumb', 'Kelola Beranda')

@php
    $errorBag = $errors->getBag('hero');
    $removeChecked = $errorBag->any() && old('remove_hero_image');

    $sources = [
        [
            'title' => 'Profil singkat & statistik',
            'text' => 'Judul, sejarah singkat, '.$counts['stats'].' statistik, dan '.$counts['facilities'].' fasilitas yang tampil.',
            'route' => 'dashboard.profil',
            'link' => 'Kelola Profil Masjid',
        ],
        [
            'title' => 'Jadwal sholat hari ini',
            'text' => 'Diambil otomatis setiap hari dari myQuran untuk wilayah Sidoarjo.',
            'route' => null,
            'link' => null,
        ],
        [
            'title' => 'Kegiatan & agenda terdekat',
            'text' => $counts['routines'].' kegiatan rutin, '.$counts['kitab'].' jadwal ngaji kitab, dan '.$counts['agendas'].' agenda akan datang.',
            'route' => 'dashboard.jadwal',
            'link' => 'Kelola Jadwal & Kegiatan',
        ],
        [
            'title' => 'Program TPQ',
            'text' => 'Ringkasan, jadwal, dan '.$counts['tpqPillars'].' program unggulan.',
            'route' => 'dashboard.tpq',
            'link' => 'Kelola Program TPQ',
        ],
        [
            'title' => 'ZISWAF, Galeri, dan Kontak',
            'text' => 'Belum bisa dikelola. Bagian ini akan terhubung saat modul ZISWAF, Galeri, dan Kontak selesai.',
            'route' => null,
            'link' => null,
            'pending' => true,
        ],
    ];
@endphp

@section('content')
    <div class="dash-page">
        <header class="dash-page-header">
            <div>
                <p class="dash-eyebrow">Kelola situs</p>
                <h1>Kelola Beranda</h1>
                <p>Atur bagian pembuka Beranda. Bagian lain di Beranda diambil dari halaman kelola masing-masing.</p>
            </div>
            <a class="dash-button dash-button-ghost" href="{{ route('home') }}" target="_blank" rel="noopener">
                Lihat Beranda
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M14 3h7v7"></path>
                    <path d="m10 14 11-11"></path>
                    <path d="M19 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h6"></path>
                </svg>
            </a>
        </header>

        @if (session('status'))
            <div class="dash-alert dash-toast" role="status"
                x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M20 6 9 17l-5-5"></path>
                </svg>
                {{ session('status') }}
                <button type="button" class="dash-toast-close" @click="show = false" aria-label="Tutup pemberitahuan">&times;</button>
            </div>
        @endif

        {{-- Bagian pembuka --}}
        <section id="hero" class="dash-panel" aria-labelledby="hero-title">
            <header class="dash-panel-header">
                <h2 id="hero-title">Bagian pembuka</h2>
                <p>Bagian paling atas Beranda: judul, deskripsi, ayat, dan foto masjid.</p>
            </header>

            <form method="POST" action="{{ route('dashboard.beranda.update') }}" enctype="multipart/form-data" class="dash-form" x-data="photoUpload()">
                @csrf
                @method('PUT')

                <fieldset class="dash-fieldset">
                    <legend>Teks</legend>
                    <x-dashboard.field bag="hero" name="hero_eyebrow" label="Label kecil di atas judul" :value="$hero?->hero_eyebrow" placeholder="MASJID JAMI · YAYASAN NURUL HAQQ" />
                    <x-dashboard.field bag="hero" name="hero_title" label="Judul" type="textarea" rows="2" :value="$hero?->hero_title" required
                        hint="Tekan Enter untuk memindahkan kata ke baris berikutnya." />
                    <x-dashboard.field bag="hero" name="hero_description" label="Deskripsi" type="textarea" rows="3" :value="$hero?->hero_description" />
                </fieldset>

                <fieldset class="dash-fieldset">
                    <legend>Ayat di kartu foto</legend>
                    <x-dashboard.field bag="hero" name="hero_arabic_text" label="Teks Arab" type="textarea" rows="2" :value="$hero?->hero_arabic_text" class="dash-field-arabic" />
                    <x-dashboard.field bag="hero" name="hero_translation" label="Terjemahan" type="textarea" rows="2" :value="$hero?->hero_translation" />
                    <x-dashboard.field bag="hero" name="hero_citation" label="Sumber ayat" :value="$hero?->hero_citation" placeholder="QS. AT-TAUBAH: 18" />
                </fieldset>

                <fieldset class="dash-fieldset">
                    <legend>Foto</legend>
                    <div class="dash-hero-photo">
                        <figure class="dash-hero-current">
                            @if ($hero?->heroImage)
                                <img src="{{ $hero->heroImage->url() }}" alt="{{ $hero->heroImage->alt ?: 'Foto hero saat ini' }}">
                                <figcaption>Foto unggahan saat ini</figcaption>
                            @else
                                <img src="{{ asset(\App\Models\HomePage::DEFAULT_HERO_IMAGE) }}" alt="{{ \App\Models\HomePage::DEFAULT_HERO_ALT }}">
                                <figcaption>Foto bawaan</figcaption>
                            @endif
                        </figure>
                        <div class="dash-hero-photo-fields">
                            <x-dashboard.photo-field bag="hero" name="hero_image" label="Ganti foto"
                                hint="JPG, PNG, atau WebP. Sebaiknya foto mendatar; bagian atas foto tampil melengkung." />
                            <x-dashboard.field bag="hero" name="hero_image_alt" label="Keterangan foto" :value="$hero?->heroImage?->alt"
                                hint="Dibacakan untuk pengunjung tunanetra. Contoh: Pintu masuk Masjid Nurul Haqq pada malam hari." />
                            @if ($hero?->heroImage)
                                <label class="dash-inline-check">
                                    <input type="hidden" name="remove_hero_image" value="0">
                                    <input type="checkbox" name="remove_hero_image" value="1" @checked($removeChecked)>
                                    Kembali ke foto bawaan (foto unggahan akan dihapus)
                                </label>
                            @endif
                        </div>
                    </div>
                </fieldset>

                <div class="dash-form-actions">
                    <button type="submit" class="dash-button dash-button-primary" :disabled="busy">Simpan bagian pembuka</button>
                </div>
            </form>
        </section>

        {{-- Sumber bagian lain --}}
        <section id="bagian-lain" class="dash-panel" aria-labelledby="bagian-lain-title">
            <header class="dash-panel-header">
                <h2 id="bagian-lain-title">Bagian lain di Beranda</h2>
                <p>Isi bagian-bagian ini diubah dari halaman kelolanya masing-masing, jadi cukup diubah di satu tempat.</p>
            </header>

            <ul class="dash-source-grid">
                @foreach ($sources as $source)
                    <li @class(['dash-source-card', 'is-pending' => $source['pending'] ?? false])>
                        <h3>{{ $source['title'] }}</h3>
                        <p>{{ $source['text'] }}</p>
                        @if ($source['route'])
                            <a href="{{ route($source['route']) }}">{{ $source['link'] }} →</a>
                        @elseif ($source['pending'] ?? false)
                            <span class="dash-badge">Menyusul</span>
                        @else
                            <span class="dash-badge dash-badge-auto">Otomatis</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </section>
    </div>
@endsection
