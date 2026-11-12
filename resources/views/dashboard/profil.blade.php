@extends('layouts.dashboard')

@section('title', 'Profil Masjid')
@section('breadcrumb', 'Profil Masjid')

@php
    $iconOptions = ['' => 'Tanpa ikon'] + collect($icons)
        ->mapWithKeys(fn ($icon) => [$icon->value => $icon->glyph().'  '.$icon->label()])
        ->all();

    $sections = [
        'teks' => 'Teks halaman',
        'statistik' => 'Statistik',
        'peran' => 'Peran masjid',
        'fasilitas' => 'Fasilitas',
        'pengurus' => 'Pengurus',
    ];
@endphp

@section('content')
    <div class="dash-page">
        <header class="dash-page-header">
            <div>
                <p class="dash-eyebrow">Kelola situs</p>
                <h1>Profil Masjid</h1>
                <p>Ubah isi halaman Profil yang dilihat jamaah. Perubahan langsung tampil setelah disimpan.</p>
            </div>
            <a class="dash-button dash-button-ghost" href="{{ route('profil') }}" target="_blank" rel="noopener">
                Lihat halaman
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

        <nav class="dash-section-nav" aria-label="Bagian halaman profil">
            @foreach ($sections as $anchor => $sectionLabel)
                <a href="#{{ $anchor }}">{{ $sectionLabel }}</a>
            @endforeach
        </nav>

        {{-- Teks halaman --}}
        <section id="teks" class="dash-panel" aria-labelledby="teks-title">
            <header class="dash-panel-header">
                <h2 id="teks-title">Teks halaman</h2>
                <p>Judul pembuka, pengantar, dan sejarah masjid.</p>
            </header>

            <form method="POST" action="{{ route('dashboard.profil.update') }}" class="dash-form">
                @csrf
                @method('PUT')

                <fieldset class="dash-fieldset">
                    <legend>Bagian pembuka</legend>
                    <x-dashboard.field bag="about" name="hero_title" label="Judul halaman" :value="$about?->hero_title" required />
                    <x-dashboard.field bag="about" name="hero_description" label="Deskripsi singkat" type="textarea" rows="2" :value="$about?->hero_description" />
                </fieldset>

                <fieldset class="dash-fieldset">
                    <legend>Pengantar</legend>
                    <x-dashboard.field bag="about" name="intro_title" label="Judul pengantar" :value="$about?->intro_title" />
                    <x-dashboard.field bag="about" name="intro_description" label="Isi pengantar" type="textarea" rows="3" :value="$about?->intro_description" />
                </fieldset>

                <fieldset class="dash-fieldset">
                    <legend>Sejarah</legend>
                    <x-dashboard.field bag="about" name="history_title" label="Judul bagian sejarah" :value="$about?->history_title" placeholder="Sejarah" />
                    <x-dashboard.field bag="about" name="history_content" label="Isi sejarah" type="textarea" rows="8" :value="$about?->history_content"
                        hint="Pisahkan paragraf dengan satu baris kosong. Teks ini juga tampil di Beranda." />
                </fieldset>

                <div class="dash-form-actions">
                    <button type="submit" class="dash-button dash-button-primary">Simpan teks halaman</button>
                </div>
            </form>
        </section>

        {{-- Statistik --}}
        <section id="statistik" class="dash-panel" aria-labelledby="statistik-title">
            <header class="dash-panel-header">
                <h2 id="statistik-title">Statistik</h2>
                <p>Angka singkat di samping pengantar, misalnya luas tanah atau daya tampung.</p>
            </header>

            <div class="dash-list">
                @forelse ($stats as $stat)
                    <div class="dash-item">
                        <form method="POST" action="{{ route('dashboard.profil.stats.update', $stat) }}" class="dash-item-form dash-grid-stat">
                            @csrf
                            @method('PUT')
                            <x-dashboard.field :bag="'stat-'.$stat->id" name="value" label="Nilai" :value="$stat->value" required />
                            <x-dashboard.field :bag="'stat-'.$stat->id" name="label" label="Keterangan" :value="$stat->label" required />
                            <x-dashboard.field :bag="'stat-'.$stat->id" name="icon" label="Ikon" type="select" :options="$iconOptions" :value="$stat->icon?->value" />
                            <x-dashboard.field :bag="'stat-'.$stat->id" name="sort_order" label="Urutan" type="number" :show-optional="false" :value="$stat->sort_order" />
                            <x-dashboard.item-actions :delete-form="'hapus-stat-'.$stat->id" :label="'statistik “'.$stat->label.'”'" />
                        </form>
                        <x-dashboard.delete-form :id="'hapus-stat-'.$stat->id" :action="route('dashboard.profil.stats.destroy', $stat)" :label="'statistik “'.$stat->label.'”'" />
                    </div>
                @empty
                    <p class="dash-empty">Belum ada statistik. Tambahkan lewat form di bawah.</p>
                @endforelse
            </div>

            <form method="POST" action="{{ route('dashboard.profil.stats.store') }}" class="dash-add">
                @csrf
                <h3>Tambah statistik</h3>
                <div class="dash-item-form dash-grid-stat">
                    <x-dashboard.field bag="stat-new" name="value" label="Nilai" placeholder="270 m²" required />
                    <x-dashboard.field bag="stat-new" name="label" label="Keterangan" placeholder="Luas tanah" required />
                    <x-dashboard.field bag="stat-new" name="icon" label="Ikon" type="select" :options="$iconOptions" />
                    <x-dashboard.field bag="stat-new" name="sort_order" label="Urutan" type="number" :show-optional="false" placeholder="Otomatis" />
                    <x-dashboard.item-actions submit-label="Tambah" />
                </div>
            </form>
        </section>

        {{-- Peran masjid --}}
        <section id="peran" class="dash-panel" aria-labelledby="peran-title">
            <header class="dash-panel-header">
                <h2 id="peran-title">Peran masjid</h2>
                <p>Kartu di bagian “Lebih dari tempat sholat”.</p>
            </header>

            <div class="dash-list">
                @forelse ($roles as $role)
                    <div class="dash-item">
                        <form method="POST" action="{{ route('dashboard.profil.roles.update', $role) }}" class="dash-item-form dash-grid-role">
                            @csrf
                            @method('PUT')
                            <x-dashboard.field :bag="'role-'.$role->id" name="title" label="Judul" :value="$role->title" required />
                            <x-dashboard.field :bag="'role-'.$role->id" name="icon" label="Ikon" type="select" :options="$iconOptions" :value="$role->icon?->value" />
                            <x-dashboard.field :bag="'role-'.$role->id" name="sort_order" label="Urutan" type="number" :show-optional="false" :value="$role->sort_order" />
                            <x-dashboard.field :bag="'role-'.$role->id" name="description" label="Deskripsi" type="textarea" rows="2" :value="$role->description" class="dash-span-full" />
                            <x-dashboard.item-actions :delete-form="'hapus-role-'.$role->id" :label="'peran “'.$role->title.'”'" />
                        </form>
                        <x-dashboard.delete-form :id="'hapus-role-'.$role->id" :action="route('dashboard.profil.roles.destroy', $role)" :label="'peran “'.$role->title.'”'" />
                    </div>
                @empty
                    <p class="dash-empty">Belum ada peran masjid. Tambahkan lewat form di bawah.</p>
                @endforelse
            </div>

            <form method="POST" action="{{ route('dashboard.profil.roles.store') }}" class="dash-add">
                @csrf
                <h3>Tambah peran</h3>
                <div class="dash-item-form dash-grid-role">
                    <x-dashboard.field bag="role-new" name="title" label="Judul" placeholder="Ibadah & pengajian" required />
                    <x-dashboard.field bag="role-new" name="icon" label="Ikon" type="select" :options="$iconOptions" />
                    <x-dashboard.field bag="role-new" name="sort_order" label="Urutan" type="number" :show-optional="false" placeholder="Otomatis" />
                    <x-dashboard.field bag="role-new" name="description" label="Deskripsi" type="textarea" rows="2" class="dash-span-full" />
                    <x-dashboard.item-actions submit-label="Tambah" />
                </div>
            </form>
        </section>

        {{-- Fasilitas --}}
        <section id="fasilitas" class="dash-panel" aria-labelledby="fasilitas-title">
            <header class="dash-panel-header">
                <h2 id="fasilitas-title">Fasilitas</h2>
                <p>Daftar sarana masjid. Fasilitas yang disembunyikan tidak tampil di Profil maupun Beranda.</p>
            </header>

            <div class="dash-list">
                @forelse ($facilities as $facility)
                    <div @class(['dash-item', 'is-hidden' => ! $facility->is_active])>
                        <form method="POST" action="{{ route('dashboard.profil.facilities.update', $facility) }}" class="dash-item-form dash-grid-facility">
                            @csrf
                            @method('PUT')
                            <x-dashboard.field :bag="'facility-'.$facility->id" name="name" label="Nama fasilitas" :value="$facility->name" required />
                            <x-dashboard.field :bag="'facility-'.$facility->id" name="sort_order" label="Urutan" type="number" :show-optional="false" :value="$facility->sort_order" />
                            <x-dashboard.visibility :bag="'facility-'.$facility->id" :checked="$facility->is_active" />
                            <x-dashboard.item-actions :delete-form="'hapus-facility-'.$facility->id" :label="'fasilitas “'.$facility->name.'”'" />
                        </form>
                        <x-dashboard.delete-form :id="'hapus-facility-'.$facility->id" :action="route('dashboard.profil.facilities.destroy', $facility)" :label="'fasilitas “'.$facility->name.'”'" />
                    </div>
                @empty
                    <p class="dash-empty">Belum ada fasilitas. Tambahkan lewat form di bawah.</p>
                @endforelse
            </div>

            <form method="POST" action="{{ route('dashboard.profil.facilities.store') }}" class="dash-add">
                @csrf
                <h3>Tambah fasilitas</h3>
                <div class="dash-item-form dash-grid-facility">
                    <x-dashboard.field bag="facility-new" name="name" label="Nama fasilitas" placeholder="Tempat wudhu" required />
                    <x-dashboard.field bag="facility-new" name="sort_order" label="Urutan" type="number" :show-optional="false" placeholder="Otomatis" />
                    <x-dashboard.visibility bag="facility-new" :checked="true" />
                    <x-dashboard.item-actions submit-label="Tambah" />
                </div>
            </form>
        </section>

        {{-- Pengurus --}}
        <section id="pengurus" class="dash-panel" aria-labelledby="pengurus-title">
            <header class="dash-panel-header">
                <h2 id="pengurus-title">Pengurus</h2>
                <p>Struktur organisasi yang tampil di bagian sejarah &amp; kepengurusan.</p>
            </header>

            <div class="dash-list">
                @forelse ($members as $member)
                    <div @class(['dash-item', 'is-hidden' => ! $member->is_active])>
                        <form method="POST" action="{{ route('dashboard.profil.members.update', $member) }}" class="dash-item-form dash-grid-member">
                            @csrf
                            @method('PUT')
                            <x-dashboard.field :bag="'member-'.$member->id" name="position" label="Jabatan" :value="$member->position" required />
                            <x-dashboard.field :bag="'member-'.$member->id" name="name" label="Nama" :value="$member->name" required />
                            <x-dashboard.field :bag="'member-'.$member->id" name="note" label="Keterangan" :value="$member->note" />
                            <x-dashboard.field :bag="'member-'.$member->id" name="sort_order" label="Urutan" type="number" :show-optional="false" :value="$member->sort_order" />
                            <x-dashboard.visibility :bag="'member-'.$member->id" :checked="$member->is_active" />
                            <x-dashboard.item-actions :delete-form="'hapus-member-'.$member->id" :label="'pengurus “'.$member->name.'”'" />
                        </form>
                        <x-dashboard.delete-form :id="'hapus-member-'.$member->id" :action="route('dashboard.profil.members.destroy', $member)" :label="'pengurus “'.$member->name.'”'" />
                    </div>
                @empty
                    <p class="dash-empty">Belum ada pengurus. Tambahkan lewat form di bawah.</p>
                @endforelse
            </div>

            <form method="POST" action="{{ route('dashboard.profil.members.store') }}" class="dash-add">
                @csrf
                <h3>Tambah pengurus</h3>
                <div class="dash-item-form dash-grid-member">
                    <x-dashboard.field bag="member-new" name="position" label="Jabatan" placeholder="Ketua" required />
                    <x-dashboard.field bag="member-new" name="name" label="Nama" required />
                    <x-dashboard.field bag="member-new" name="note" label="Keterangan" placeholder="Pengurus masjid" />
                    <x-dashboard.field bag="member-new" name="sort_order" label="Urutan" type="number" :show-optional="false" placeholder="Otomatis" />
                    <x-dashboard.visibility bag="member-new" :checked="true" />
                    <x-dashboard.item-actions submit-label="Tambah" />
                </div>
            </form>
        </section>
    </div>
@endsection
