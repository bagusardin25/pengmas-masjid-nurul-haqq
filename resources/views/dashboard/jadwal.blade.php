@extends('layouts.dashboard')

@section('title', 'Jadwal & Kegiatan')
@section('breadcrumb', 'Jadwal & Kegiatan')

@php
    $dayOptions = collect($weekdays)->mapWithKeys(fn ($day) => [$day->value => $day->label()])->all();
    $statusOptions = collect($statuses)->mapWithKeys(fn ($status) => [$status->value => $status->label()])->all();
    $pastHasErrors = $pastAgendas->contains(fn ($agenda) => $errors->getBag('agenda-'.$agenda->id)->any());

    $sections = [
        'teks' => 'Teks halaman',
        'rutin' => 'Kegiatan rutin',
        'kitab' => 'Ngaji kitab',
        'agenda' => 'Agenda & Jumat',
    ];
@endphp

@section('content')
    <div class="dash-page">
        <header class="dash-page-header">
            <div>
                <p class="dash-eyebrow">Kelola situs</p>
                <h1>Jadwal &amp; Kegiatan</h1>
                <p>Atur kegiatan rutin, jadwal ngaji kitab, dan agenda masjid termasuk khotib sholat Jumat. Jadwal sholat harian diambil otomatis.</p>
            </div>
            <a class="dash-button dash-button-ghost" href="{{ route('jadwal') }}" target="_blank" rel="noopener">
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

        <nav class="dash-section-nav" aria-label="Bagian halaman jadwal">
            @foreach ($sections as $anchor => $sectionLabel)
                <a href="#{{ $anchor }}">{{ $sectionLabel }}</a>
            @endforeach
        </nav>

        {{-- Teks halaman --}}
        <section id="teks" class="dash-panel" aria-labelledby="teks-title">
            <header class="dash-panel-header">
                <h2 id="teks-title">Teks halaman</h2>
                <p>Judul pembuka dan catatan kecil di bawah daftar kegiatan.</p>
            </header>

            <form method="POST" action="{{ route('dashboard.jadwal.update') }}" class="dash-form">
                @csrf
                @method('PUT')

                <fieldset class="dash-fieldset">
                    <legend>Bagian pembuka</legend>
                    <x-dashboard.field bag="activity" name="hero_title" label="Judul halaman" :value="$page?->hero_title" required />
                    <x-dashboard.field bag="activity" name="hero_description" label="Deskripsi singkat" type="textarea" rows="2" :value="$page?->hero_description" />
                </fieldset>

                <fieldset class="dash-fieldset">
                    <legend>Catatan</legend>
                    <x-dashboard.field bag="activity" name="routine_note" label="Catatan kegiatan rutin" :value="$page?->routine_note" />
                    <x-dashboard.field bag="activity" name="kitab_note" label="Catatan ngaji kitab" :value="$page?->kitab_note" />
                </fieldset>

                <div class="dash-form-actions">
                    <button type="submit" class="dash-button dash-button-primary">Simpan teks halaman</button>
                </div>
            </form>
        </section>

        {{-- Kegiatan rutin --}}
        <section id="rutin" class="dash-panel" aria-labelledby="rutin-title">
            <header class="dash-panel-header">
                <h2 id="rutin-title">Kegiatan rutin</h2>
                <p>Kegiatan tetap seperti sholat berjamaah. Pilih mana yang juga tampil di Beranda.</p>
            </header>

            <div class="dash-list">
                @forelse ($routines as $routine)
                    @php $bag = 'routine-'.$routine->id; @endphp
                    <div class="dash-item">
                        <form method="POST" action="{{ route('dashboard.jadwal.routines.update', $routine) }}" class="dash-item-form dash-grid-routine">
                            @csrf
                            @method('PUT')
                            <x-dashboard.field :bag="$bag" name="title" label="Nama kegiatan" :value="$routine->title" required />
                            <x-dashboard.field :bag="$bag" name="schedule" label="Keterangan" :value="$routine->schedule" />
                            <x-dashboard.field :bag="$bag" name="badge" label="Label waktu" :value="$routine->badge" />
                            <x-dashboard.field :bag="$bag" name="sort_order" label="Urutan" type="number" :value="$routine->sort_order" :show-optional="false" />
                            <x-dashboard.visibility :bag="$bag" name="show_on_home" label="Di Beranda" off-text="Tidak" :checked="$routine->show_on_home" />
                            <x-dashboard.item-actions :delete-form="'hapus-'.$bag" :label="'kegiatan “'.$routine->title.'”'" />
                        </form>
                        <x-dashboard.delete-form :id="'hapus-'.$bag" :action="route('dashboard.jadwal.routines.destroy', $routine)" :label="'kegiatan “'.$routine->title.'”'" />
                    </div>
                @empty
                    <p class="dash-empty">Belum ada kegiatan rutin. Tambahkan lewat form di bawah.</p>
                @endforelse
            </div>

            <form method="POST" action="{{ route('dashboard.jadwal.routines.store') }}" class="dash-add">
                @csrf
                <h3>Tambah kegiatan rutin</h3>
                <div class="dash-item-form dash-grid-routine">
                    <x-dashboard.field bag="routine-new" name="title" label="Nama kegiatan" placeholder="Sholat fardhu berjamaah" required />
                    <x-dashboard.field bag="routine-new" name="schedule" label="Keterangan" placeholder="Imam rawatib bergiliran" />
                    <x-dashboard.field bag="routine-new" name="badge" label="Label waktu" placeholder="5 waktu sehari" />
                    <x-dashboard.field bag="routine-new" name="sort_order" label="Urutan" type="number" placeholder="Otomatis" :show-optional="false" />
                    <x-dashboard.visibility bag="routine-new" name="show_on_home" label="Di Beranda" off-text="Tidak" :checked="true" />
                    <x-dashboard.item-actions submit-label="Tambah" />
                </div>
            </form>
        </section>

        {{-- Ngaji kitab --}}
        <section id="kitab" class="dash-panel" aria-labelledby="kitab-title">
            <header class="dash-panel-header">
                <h2 id="kitab-title">Ngaji kitab</h2>
                <p>Satu baris untuk satu hari. Kitab yang diajarkan di dua hari ditulis dua baris dengan nama yang sama, nanti tampil jadi satu di Beranda.</p>
            </header>

            <div class="dash-list">
                @forelse ($kitabSchedules as $kitab)
                    @php $bag = 'kitab-'.$kitab->id; @endphp
                    <div class="dash-item">
                        <form method="POST" action="{{ route('dashboard.jadwal.kitab.update', $kitab) }}" class="dash-item-form dash-grid-six">
                            @csrf
                            @method('PUT')
                            <x-dashboard.field :bag="$bag" name="title" label="Nama kitab" :value="$kitab->title" required class="dash-span-2" />
                            <x-dashboard.field :bag="$bag" name="speaker" label="Pengajar" :value="$kitab->speaker" required class="dash-span-2" />
                            <x-dashboard.field :bag="$bag" name="day" label="Hari" type="select" :options="$dayOptions" :value="$kitab->day->value" required />
                            <x-dashboard.field :bag="$bag" name="sort_order" label="Urutan" type="number" :value="$kitab->sort_order" :show-optional="false" />
                            <x-dashboard.field :bag="$bag" name="time" label="Waktu" :value="$kitab->time" required class="dash-span-2" />
                            <x-dashboard.field :bag="$bag" name="location" label="Tempat" :value="$kitab->location" class="dash-span-2" />
                            <x-dashboard.item-actions :delete-form="'hapus-'.$bag" :label="'jadwal “'.$kitab->title.'” hari '.$kitab->day->label()" class="dash-span-2" />
                        </form>
                        <x-dashboard.delete-form :id="'hapus-'.$bag" :action="route('dashboard.jadwal.kitab.destroy', $kitab)" :label="'jadwal “'.$kitab->title.'” hari '.$kitab->day->label()" />
                    </div>
                @empty
                    <p class="dash-empty">Belum ada jadwal ngaji kitab. Tambahkan lewat form di bawah.</p>
                @endforelse
            </div>

            <form method="POST" action="{{ route('dashboard.jadwal.kitab.store') }}" class="dash-add">
                @csrf
                <h3>Tambah jadwal ngaji kitab</h3>
                <div class="dash-item-form dash-grid-six">
                    <x-dashboard.field bag="kitab-new" name="title" label="Nama kitab" placeholder="Ngaji Kitab Irsyadul Ibad" required class="dash-span-2" />
                    <x-dashboard.field bag="kitab-new" name="speaker" label="Pengajar" placeholder="Ust. Ainur Rofiq Muslimin" required class="dash-span-2" />
                    <x-dashboard.field bag="kitab-new" name="day" label="Hari" type="select" :options="$dayOptions" required />
                    <x-dashboard.field bag="kitab-new" name="sort_order" label="Urutan" type="number" placeholder="Otomatis" :show-optional="false" />
                    <x-dashboard.field bag="kitab-new" name="time" label="Waktu" placeholder="Ba'da Maghrib" required class="dash-span-2" />
                    <x-dashboard.field bag="kitab-new" name="location" label="Tempat" class="dash-span-2" />
                    <x-dashboard.item-actions submit-label="Tambah" class="dash-span-2" />
                </div>
            </form>
        </section>

        {{-- Agenda --}}
        <section id="agenda" class="dash-panel" aria-labelledby="agenda-title">
            <header class="dash-panel-header">
                <h2 id="agenda-title">Agenda &amp; sholat Jumat</h2>
                <p>Agenda berstatus Terbit tampil di halaman Jadwal, dan yang paling dekat tampil di Beranda. Jadwal khotib Jumat dicatat sebagai agenda "Sholat Jumat".</p>
            </header>

            <h3 class="dash-subheading">Akan datang ({{ $upcomingAgendas->count() }})</h3>
            <div class="dash-list">
                @forelse ($upcomingAgendas as $agenda)
                    @include('dashboard.partials.agenda-item', ['agenda' => $agenda, 'statusOptions' => $statusOptions])
                @empty
                    <p class="dash-empty">Belum ada agenda yang akan datang. Tambahkan lewat form di bawah.</p>
                @endforelse
            </div>

            <form method="POST" action="{{ route('dashboard.jadwal.agendas.store') }}" class="dash-add">
                @csrf
                <h3>Tambah agenda</h3>
                <p class="dash-hint dash-add-hint">Isian awal disiapkan untuk sholat Jumat berikutnya. Ubah nama, waktu, dan tempat bila menambah agenda lain.</p>
                <div class="dash-item-form dash-grid-six">
                    <x-dashboard.field bag="agenda-new" name="title" label="Nama agenda" value="Sholat Jumat" required class="dash-span-2" />
                    <x-dashboard.field bag="agenda-new" name="date" label="Tanggal" type="date" :value="$suggestedFriday" required />
                    <x-dashboard.field bag="agenda-new" name="time" label="Waktu" value="Waktu Dzuhur" />
                    <x-dashboard.field bag="agenda-new" name="speaker" label="Khotib / pengisi" placeholder="Ust. ..." class="dash-span-2" />
                    <x-dashboard.field bag="agenda-new" name="location" label="Tempat" value="Masjid Nurul Haqq" class="dash-span-2" />
                    <x-dashboard.field bag="agenda-new" name="status" label="Status" type="select" :options="$statusOptions" value="published" required />
                    <x-dashboard.field bag="agenda-new" name="description" label="Keterangan" type="textarea" rows="1" class="dash-span-3" />
                    <x-dashboard.item-actions submit-label="Tambah agenda" class="dash-actions-row" />
                </div>
            </form>

            @if ($pastAgendas->isNotEmpty())
                <details class="dash-past" @if ($pastHasErrors) open @endif>
                    <summary>Agenda yang sudah lewat ({{ $pastAgendas->count() }})</summary>
                    <div class="dash-list">
                        @foreach ($pastAgendas as $agenda)
                            @include('dashboard.partials.agenda-item', ['agenda' => $agenda, 'statusOptions' => $statusOptions])
                        @endforeach
                    </div>
                </details>
            @endif
        </section>
    </div>
@endsection
