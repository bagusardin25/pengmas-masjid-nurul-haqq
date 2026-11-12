@extends('layouts.dashboard')

@section('title', 'Program TPQ')
@section('breadcrumb', 'Program TPQ')

@php
    $iconOptions = ['' => 'Tanpa ikon'] + collect($icons)
        ->mapWithKeys(fn ($icon) => [$icon->value => $icon->glyph().'  '.$icon->label()])
        ->all();

    $sections = [
        'teks' => 'Teks & informasi',
        'program' => 'Program unggulan',
        'pertanyaan' => 'Pertanyaan umum',
        'dokumentasi' => 'Dokumentasi foto',
    ];
@endphp

@section('content')
    <div class="dash-page">
        <header class="dash-page-header">
            <div>
                <p class="dash-eyebrow">Kelola situs</p>
                <h1>Program TPQ</h1>
                <p>Atur informasi TPQ Nurul Haqq di halaman Program TPQ dan bagian TPQ di Beranda.</p>
            </div>
            <a class="dash-button dash-button-ghost" href="{{ route('tpq') }}" target="_blank" rel="noopener">
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

        <nav class="dash-section-nav" aria-label="Bagian halaman TPQ">
            @foreach ($sections as $anchor => $sectionLabel)
                <a href="#{{ $anchor }}">{{ $sectionLabel }}</a>
            @endforeach
        </nav>

        {{-- Teks & informasi --}}
        <section id="teks" class="dash-panel" aria-labelledby="teks-title">
            <header class="dash-panel-header">
                <h2 id="teks-title">Teks &amp; informasi</h2>
                <p>Isian yang dikosongkan tidak ditampilkan di situs, jadi tidak perlu diisi dengan perkiraan.</p>
            </header>

            <form method="POST" action="{{ route('dashboard.tpq.update') }}" class="dash-form">
                @csrf
                @method('PUT')

                <fieldset class="dash-fieldset">
                    <legend>Bagian pembuka</legend>
                    <x-dashboard.field bag="tpq" name="hero_title" label="Judul halaman" :value="$tpq?->hero_title" required />
                    <x-dashboard.field bag="tpq" name="hero_description" label="Deskripsi singkat" type="textarea" rows="3" :value="$tpq?->hero_description" />
                    <x-dashboard.field bag="tpq" name="summary" label="Ringkasan program" type="textarea" rows="2" :value="$tpq?->summary"
                        hint="Tampil di bagian Program TPQ di Beranda." />
                </fieldset>

                <fieldset class="dash-fieldset">
                    <legend>Informasi program</legend>
                    <x-dashboard.field bag="tpq" name="info_description" label="Penjelasan program" type="textarea" rows="3" :value="$tpq?->info_description" />
                    <div class="dash-field-row">
                        <x-dashboard.field bag="tpq" name="info_schedule" label="Jadwal" :value="$tpq?->info_schedule" placeholder="Senin–Jumat · 15.30–17.00 WIB" />
                        <x-dashboard.field bag="tpq" name="info_age_range" label="Usia santri" :value="$tpq?->info_age_range" placeholder="Belum diisi" />
                        <x-dashboard.field bag="tpq" name="info_fee" label="Biaya (SPP)" :value="$tpq?->info_fee" placeholder="Belum diisi" />
                    </div>
                </fieldset>

                <fieldset class="dash-fieldset">
                    <legend>Pendaftaran</legend>
                    <x-dashboard.field bag="tpq" name="registration_title" label="Judul kotak pendaftaran" :value="$tpq?->registration_title" />
                    <x-dashboard.field bag="tpq" name="registration_description" label="Syarat & keterangan" type="textarea" rows="3" :value="$tpq?->registration_description" />
                    <div class="dash-field-row">
                        <x-dashboard.field bag="tpq" name="registration_phone" label="Nomor WhatsApp" type="tel" :value="$tpq?->registration_phone" placeholder="0812-3956-7390"
                            hint="Dipakai untuk tombol Tanya via WhatsApp." />
                        <x-dashboard.field bag="tpq" name="registration_form_url" label="Tautan formulir online" type="url" :value="$tpq?->registration_form_url" placeholder="https://..."
                            hint="Bila kosong, tombol Daftar TPQ membuka WhatsApp." />
                    </div>
                </fieldset>

                <div class="dash-form-actions">
                    <button type="submit" class="dash-button dash-button-primary">Simpan informasi TPQ</button>
                </div>
            </form>
        </section>

        {{-- Program unggulan --}}
        <section id="program" class="dash-panel" aria-labelledby="program-title">
            <header class="dash-panel-header">
                <h2 id="program-title">Program unggulan</h2>
                <p>Kartu di halaman Program TPQ. Judulnya juga tampil sebagai daftar centang di Beranda.</p>
            </header>

            <div class="dash-list">
                @forelse ($pillars as $pillar)
                    @php $bag = 'pillar-'.$pillar->id; @endphp
                    <div class="dash-item">
                        <form method="POST" action="{{ route('dashboard.tpq.pillars.update', $pillar) }}" class="dash-item-form dash-grid-role">
                            @csrf
                            @method('PUT')
                            <x-dashboard.field :bag="$bag" name="title" label="Nama program" :value="$pillar->title" required />
                            <x-dashboard.field :bag="$bag" name="icon" label="Ikon" type="select" :options="$iconOptions" :value="$pillar->icon?->value" />
                            <x-dashboard.field :bag="$bag" name="sort_order" label="Urutan" type="number" :value="$pillar->sort_order" :show-optional="false" />
                            <x-dashboard.field :bag="$bag" name="description" label="Deskripsi" type="textarea" rows="2" :value="$pillar->description" class="dash-span-full" />
                            <x-dashboard.item-actions :delete-form="'hapus-'.$bag" :label="'program “'.$pillar->title.'”'" />
                        </form>
                        <x-dashboard.delete-form :id="'hapus-'.$bag" :action="route('dashboard.tpq.pillars.destroy', $pillar)" :label="'program “'.$pillar->title.'”'" />
                    </div>
                @empty
                    <p class="dash-empty">Belum ada program unggulan. Tambahkan lewat form di bawah.</p>
                @endforelse
            </div>

            <form method="POST" action="{{ route('dashboard.tpq.pillars.store') }}" class="dash-add">
                @csrf
                <h3>Tambah program</h3>
                <div class="dash-item-form dash-grid-role">
                    <x-dashboard.field bag="pillar-new" name="title" label="Nama program" placeholder="Membaca Al-Qur'an dengan tartil" required />
                    <x-dashboard.field bag="pillar-new" name="icon" label="Ikon" type="select" :options="$iconOptions" />
                    <x-dashboard.field bag="pillar-new" name="sort_order" label="Urutan" type="number" placeholder="Otomatis" :show-optional="false" />
                    <x-dashboard.field bag="pillar-new" name="description" label="Deskripsi" type="textarea" rows="2" class="dash-span-full" />
                    <x-dashboard.item-actions submit-label="Tambah" />
                </div>
            </form>
        </section>

        {{-- Pertanyaan umum --}}
        <section id="pertanyaan" class="dash-panel" aria-labelledby="pertanyaan-title">
            <header class="dash-panel-header">
                <h2 id="pertanyaan-title">Pertanyaan umum</h2>
                <p>Pertanyaan yang sering diajukan orang tua sebelum mendaftarkan anak.</p>
            </header>

            <div class="dash-list">
                @forelse ($faqs as $faq)
                    @php $bag = 'faq-'.$faq->id; @endphp
                    <div class="dash-item">
                        <form method="POST" action="{{ route('dashboard.tpq.faqs.update', $faq) }}" class="dash-item-form dash-grid-faq">
                            @csrf
                            @method('PUT')
                            <x-dashboard.field :bag="$bag" name="question" label="Pertanyaan" :value="$faq->question" required />
                            <x-dashboard.field :bag="$bag" name="sort_order" label="Urutan" type="number" :value="$faq->sort_order" :show-optional="false" />
                            <x-dashboard.field :bag="$bag" name="answer" label="Jawaban" type="textarea" rows="2" :value="$faq->answer" required class="dash-span-full" />
                            <x-dashboard.item-actions :delete-form="'hapus-'.$bag" :label="'pertanyaan “'.$faq->question.'”'" />
                        </form>
                        <x-dashboard.delete-form :id="'hapus-'.$bag" :action="route('dashboard.tpq.faqs.destroy', $faq)" :label="'pertanyaan “'.$faq->question.'”'" />
                    </div>
                @empty
                    <p class="dash-empty">Belum ada pertanyaan. Tambahkan lewat form di bawah.</p>
                @endforelse
            </div>

            <form method="POST" action="{{ route('dashboard.tpq.faqs.store') }}" class="dash-add">
                @csrf
                <h3>Tambah pertanyaan</h3>
                <div class="dash-item-form dash-grid-faq">
                    <x-dashboard.field bag="faq-new" name="question" label="Pertanyaan" placeholder="Apa syarat pendaftaran?" required />
                    <x-dashboard.field bag="faq-new" name="sort_order" label="Urutan" type="number" placeholder="Otomatis" :show-optional="false" />
                    <x-dashboard.field bag="faq-new" name="answer" label="Jawaban" type="textarea" rows="2" required class="dash-span-full" />
                    <x-dashboard.item-actions submit-label="Tambah" />
                </div>
            </form>
        </section>

        {{-- Dokumentasi foto --}}
        <section id="dokumentasi" class="dash-panel" aria-labelledby="dokumentasi-title">
            <header class="dash-panel-header">
                <h2 id="dokumentasi-title">Dokumentasi foto</h2>
                <p>Foto kegiatan belajar yang tampil di halaman Program TPQ. Pastikan orang tua santri sudah mengizinkan foto anak dipublikasikan.</p>
            </header>

            <div class="dash-list">
                @forelse ($documentations as $documentation)
                    @php $bag = 'documentation-'.$documentation->id; @endphp
                    <div class="dash-item">
                        <form method="POST" action="{{ route('dashboard.tpq.documentations.update', $documentation) }}" class="dash-item-form dash-grid-photo">
                            @csrf
                            @method('PUT')
                            <a class="dash-photo-thumb" href="{{ $documentation->image->url() }}" target="_blank" rel="noopener">
                                <img src="{{ $documentation->image->url() }}" alt="{{ $documentation->caption ?: 'Foto dokumentasi TPQ' }}" loading="lazy">
                            </a>
                            <x-dashboard.field :bag="$bag" name="caption" label="Keterangan foto" :value="$documentation->caption"
                                :hint="$documentation->image->width ? $documentation->image->width.' × '.$documentation->image->height.' px' : null" />
                            <x-dashboard.field :bag="$bag" name="sort_order" label="Urutan" type="number" :value="$documentation->sort_order" :show-optional="false" />
                            <x-dashboard.item-actions :delete-form="'hapus-'.$bag" :label="'foto '.($documentation->caption ?: 'dokumentasi')" />
                        </form>
                        <x-dashboard.delete-form :id="'hapus-'.$bag" :action="route('dashboard.tpq.documentations.destroy', $documentation)" :label="'foto '.($documentation->caption ? '“'.$documentation->caption.'”' : 'dokumentasi ini')" />
                    </div>
                @empty
                    <p class="dash-empty">Belum ada foto dokumentasi. Unggah lewat form di bawah.</p>
                @endforelse
            </div>

            <form method="POST" action="{{ route('dashboard.tpq.documentations.store') }}" enctype="multipart/form-data" class="dash-add" x-data="photoUpload()">
                @csrf
                <h3>Unggah foto</h3>
                <div class="dash-item-form dash-grid-upload">
                    <x-dashboard.photo-field bag="documentation-new" required />
                    <x-dashboard.field bag="documentation-new" name="caption" label="Keterangan foto" placeholder="Santri belajar membaca Al-Qur'an" />
                    <x-dashboard.field bag="documentation-new" name="sort_order" label="Urutan" type="number" placeholder="Otomatis" :show-optional="false" />
                    <div class="dash-item-actions">
                        <button type="submit" class="dash-button dash-button-secondary" :disabled="busy">Unggah foto</button>
                    </div>
                </div>
            </form>
        </section>
    </div>
@endsection
