@extends('layouts.dashboard')

@section('title', 'ZISWAF')
@section('breadcrumb', 'ZISWAF')

@php
    $iconOptions = ['' => 'Tanpa ikon'] + collect($icons)
        ->mapWithKeys(fn ($icon) => [$icon->value => $icon->glyph().'  '.$icon->label()])
        ->all();
    $categoryOptions = collect($categories)->mapWithKeys(fn ($category) => [$category->value => $category->label()])->all();
    $statusOptions = collect($statuses)->mapWithKeys(fn ($status) => [$status->value => $status->label()])->all();

    $errorBag = $errors->getBag('ziswaf');
    $removeQrisChecked = $errorBag->any() && old('remove_qris_image');

    $sections = [
        'teks' => 'Teks & rekening',
        'jenis-dana' => 'Jenis dana',
        'program' => 'Program sedekah',
        'kontak' => 'Kontak konfirmasi',
        'laporan' => 'Laporan keuangan',
    ];
@endphp

@section('content')
    <div class="dash-page">
        <header class="dash-page-header">
            <div>
                <p class="dash-eyebrow">Kelola situs</p>
                <h1>ZISWAF</h1>
                <p>Atur rekening, jenis dana, program sedekah, kontak konfirmasi, dan laporan keuangan di halaman ZISWAF dan Beranda.</p>
            </div>
            <a class="dash-button dash-button-ghost" href="{{ route('ziswaf') }}" target="_blank" rel="noopener">
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

        <nav class="dash-section-nav" aria-label="Bagian halaman ZISWAF">
            @foreach ($sections as $anchor => $sectionLabel)
                <a href="#{{ $anchor }}">{{ $sectionLabel }}</a>
            @endforeach
        </nav>

        {{-- Teks & rekening --}}
        <section id="teks" class="dash-panel" aria-labelledby="teks-title">
            <header class="dash-panel-header">
                <h2 id="teks-title">Teks &amp; rekening</h2>
                <p>Isian yang dikosongkan tidak ditampilkan di situs. Rekening baru tampil setelah nomor rekening diisi.</p>
            </header>

            <form method="POST" action="{{ route('dashboard.ziswaf.update') }}" enctype="multipart/form-data" class="dash-form" x-data="photoUpload()">
                @csrf
                @method('PUT')

                <fieldset class="dash-fieldset">
                    <legend>Bagian pembuka</legend>
                    <x-dashboard.field bag="ziswaf" name="hero_title" label="Judul halaman" :value="$ziswaf?->hero_title" required
                        hint="Juga dipakai sebagai judul bagian ZISWAF di Beranda." />
                    <x-dashboard.field bag="ziswaf" name="hero_description" label="Deskripsi singkat" type="textarea" rows="3" :value="$ziswaf?->hero_description" />
                    <x-dashboard.field bag="ziswaf" name="summary" label="Ringkasan di Beranda" type="textarea" rows="2" :value="$ziswaf?->summary" />
                </fieldset>

                <fieldset class="dash-fieldset">
                    <legend>Rekening</legend>
                    <div class="dash-field-row">
                        <x-dashboard.field bag="ziswaf" name="bank_name" label="Nama bank" :value="$ziswaf?->bank_name" placeholder="Bank Syariah Indonesia (BSI)" />
                        <x-dashboard.field bag="ziswaf" name="account_number" label="Nomor rekening" :value="$ziswaf?->account_number" placeholder="7211074177" />
                        <x-dashboard.field bag="ziswaf" name="account_holder" label="Atas nama" :value="$ziswaf?->account_holder" placeholder="Budiyono" />
                    </div>
                    <x-dashboard.field bag="ziswaf" name="account_note" label="Keterangan rekening" :value="$ziswaf?->account_note"
                        placeholder="Rekening Bendahara Takmir Masjid Nurul Haqq." />
                </fieldset>

                <fieldset class="dash-fieldset">
                    <legend>QRIS</legend>
                    <div class="dash-qris">
                        <figure class="dash-qris-current">
                            @if ($ziswaf?->qrisImage)
                                <img src="{{ $ziswaf->qrisImage->url() }}" alt="Kode QRIS saat ini">
                                <figcaption>QRIS saat ini</figcaption>
                            @else
                                <span class="dash-qris-empty">Belum ada QRIS</span>
                            @endif
                        </figure>
                        <div class="dash-hero-photo-fields">
                            <x-dashboard.photo-field bag="ziswaf" name="qris_image" :label="$ziswaf?->qrisImage ? 'Ganti QRIS' : 'Unggah QRIS'"
                                hint="Gunakan gambar QRIS resmi dari bank. JPG, PNG, atau WebP." />
                            <x-dashboard.field bag="ziswaf" name="qris_note" label="Keterangan QRIS" :value="$ziswaf?->qris_note"
                                placeholder="Bisa dibayar dengan semua aplikasi bank dan dompet digital" />
                            @if ($ziswaf?->qrisImage)
                                <label class="dash-inline-check">
                                    <input type="hidden" name="remove_qris_image" value="0">
                                    <input type="checkbox" name="remove_qris_image" value="1" @checked($removeQrisChecked)>
                                    Hapus QRIS dari situs
                                </label>
                            @endif
                        </div>
                    </div>
                </fieldset>

                <fieldset class="dash-fieldset">
                    <legend>Kotak infak &amp; laporan</legend>
                    <x-dashboard.field bag="ziswaf" name="infaq_box_description" label="Keterangan kotak infak" type="textarea" rows="3" :value="$ziswaf?->infaq_box_description"
                        hint="Mis. jadwal pembukaan kotak infak dan cara pelaporannya." />
                    <x-dashboard.field bag="ziswaf" name="report_description" label="Pengantar laporan keuangan" type="textarea" rows="2" :value="$ziswaf?->report_description" />
                </fieldset>

                <div class="dash-form-actions">
                    <button type="submit" class="dash-button dash-button-primary" :disabled="busy">Simpan informasi ZISWAF</button>
                </div>
            </form>
        </section>

        {{-- Jenis dana --}}
        <section id="jenis-dana" class="dash-panel" aria-labelledby="jenis-dana-title">
            <header class="dash-panel-header">
                <h2 id="jenis-dana-title">Jenis dana</h2>
                <p>Kartu “Pilih jenis dana” di halaman ZISWAF dan Beranda. Jenis dana yang disembunyikan tidak tampil di situs.</p>
            </header>

            <div class="dash-list">
                @forelse ($funds as $fund)
                    @php $bag = 'fund-'.$fund->id; @endphp
                    <div @class(['dash-item', 'is-hidden' => ! $fund->is_active])>
                        <form method="POST" action="{{ route('dashboard.ziswaf.funds.update', $fund) }}" class="dash-item-form dash-grid-fund">
                            @csrf
                            @method('PUT')
                            <x-dashboard.field :bag="$bag" name="name" label="Nama jenis dana" :value="$fund->name" required />
                            <x-dashboard.field :bag="$bag" name="icon" label="Ikon" type="select" :options="$iconOptions" :value="$fund->icon?->value" />
                            <x-dashboard.field :bag="$bag" name="sort_order" label="Urutan" type="number" :value="$fund->sort_order" :show-optional="false" />
                            <x-dashboard.visibility :bag="$bag" :checked="$fund->is_active" />
                            <x-dashboard.field :bag="$bag" name="description" label="Keterangan singkat" :value="$fund->description" class="dash-span-full" />
                            <x-dashboard.item-actions :delete-form="'hapus-'.$bag" :label="'jenis dana “'.$fund->name.'”'" />
                        </form>
                        <x-dashboard.delete-form :id="'hapus-'.$bag" :action="route('dashboard.ziswaf.funds.destroy', $fund)" :label="'jenis dana “'.$fund->name.'”'" />
                    </div>
                @empty
                    <p class="dash-empty">Belum ada jenis dana. Tambahkan lewat form di bawah.</p>
                @endforelse
            </div>

            <form method="POST" action="{{ route('dashboard.ziswaf.funds.store') }}" class="dash-add">
                @csrf
                <h3>Tambah jenis dana</h3>
                <div class="dash-item-form dash-grid-fund">
                    <x-dashboard.field bag="fund-new" name="name" label="Nama jenis dana" placeholder="Wakaf" required />
                    <x-dashboard.field bag="fund-new" name="icon" label="Ikon" type="select" :options="$iconOptions" />
                    <x-dashboard.field bag="fund-new" name="sort_order" label="Urutan" type="number" placeholder="Otomatis" :show-optional="false" />
                    <x-dashboard.visibility bag="fund-new" />
                    <x-dashboard.field bag="fund-new" name="description" label="Keterangan singkat" class="dash-span-full" />
                    <x-dashboard.item-actions submit-label="Tambah" />
                </div>
            </form>
        </section>

        {{-- Program sedekah --}}
        <section id="program" class="dash-panel" aria-labelledby="program-title">
            <header class="dash-panel-header">
                <h2 id="program-title">Program sedekah</h2>
                <p>Kategori penyaluran sedekah, mis. Peduli Palestina, Anak Yatim, dan Kotak Ambulans.</p>
            </header>

            <div class="dash-list">
                @forelse ($programs as $program)
                    @php $bag = 'program-'.$program->id; @endphp
                    <div @class(['dash-item', 'is-hidden' => ! $program->is_active])>
                        <form method="POST" action="{{ route('dashboard.ziswaf.programs.update', $program) }}" class="dash-item-form dash-grid-program">
                            @csrf
                            @method('PUT')
                            <x-dashboard.field :bag="$bag" name="title" label="Nama program" :value="$program->title" required />
                            <x-dashboard.field :bag="$bag" name="sort_order" label="Urutan" type="number" :value="$program->sort_order" :show-optional="false" />
                            <x-dashboard.visibility :bag="$bag" :checked="$program->is_active" />
                            <x-dashboard.field :bag="$bag" name="description" label="Keterangan" type="textarea" rows="2" :value="$program->description" class="dash-span-full" />
                            <x-dashboard.item-actions :delete-form="'hapus-'.$bag" :label="'program “'.$program->title.'”'" />
                        </form>
                        <x-dashboard.delete-form :id="'hapus-'.$bag" :action="route('dashboard.ziswaf.programs.destroy', $program)" :label="'program “'.$program->title.'”'" />
                    </div>
                @empty
                    <p class="dash-empty">Belum ada program sedekah. Tambahkan lewat form di bawah.</p>
                @endforelse
            </div>

            <form method="POST" action="{{ route('dashboard.ziswaf.programs.store') }}" class="dash-add">
                @csrf
                <h3>Tambah program</h3>
                <div class="dash-item-form dash-grid-program">
                    <x-dashboard.field bag="program-new" name="title" label="Nama program" placeholder="Anak Yatim" required />
                    <x-dashboard.field bag="program-new" name="sort_order" label="Urutan" type="number" placeholder="Otomatis" :show-optional="false" />
                    <x-dashboard.visibility bag="program-new" />
                    <x-dashboard.field bag="program-new" name="description" label="Keterangan" type="textarea" rows="2" class="dash-span-full" />
                    <x-dashboard.item-actions submit-label="Tambah" />
                </div>
            </form>
        </section>

        {{-- Kontak konfirmasi --}}
        <section id="kontak" class="dash-panel" aria-labelledby="kontak-title">
            <header class="dash-panel-header">
                <h2 id="kontak-title">Kontak konfirmasi</h2>
                <p>Pengurus yang menerima konfirmasi donasi. Nomornya menjadi tombol WhatsApp di halaman ZISWAF dan Beranda.</p>
            </header>

            <div class="dash-list">
                @forelse ($contacts as $contact)
                    @php $bag = 'contact-'.$contact->id; @endphp
                    <div class="dash-item">
                        <form method="POST" action="{{ route('dashboard.ziswaf.contacts.update', $contact) }}" class="dash-item-form dash-grid-contact">
                            @csrf
                            @method('PUT')
                            <x-dashboard.field :bag="$bag" name="name" label="Nama" :value="$contact->name" required />
                            <x-dashboard.field :bag="$bag" name="role" label="Jabatan" :value="$contact->role" />
                            <x-dashboard.field :bag="$bag" name="phone" label="Nomor WhatsApp" type="tel" :value="$contact->phone" required />
                            <x-dashboard.field :bag="$bag" name="sort_order" label="Urutan" type="number" :value="$contact->sort_order" :show-optional="false" />
                            <x-dashboard.item-actions :delete-form="'hapus-'.$bag" :label="'kontak '.$contact->name" />
                        </form>
                        <x-dashboard.delete-form :id="'hapus-'.$bag" :action="route('dashboard.ziswaf.contacts.destroy', $contact)" :label="'kontak '.$contact->name" />
                    </div>
                @empty
                    <p class="dash-empty">Belum ada kontak konfirmasi. Tambahkan lewat form di bawah.</p>
                @endforelse
            </div>

            <form method="POST" action="{{ route('dashboard.ziswaf.contacts.store') }}" class="dash-add">
                @csrf
                <h3>Tambah kontak</h3>
                <div class="dash-item-form dash-grid-contact">
                    <x-dashboard.field bag="contact-new" name="name" label="Nama" placeholder="Gatot Bambang" required />
                    <x-dashboard.field bag="contact-new" name="role" label="Jabatan" placeholder="Sekretaris Takmir" />
                    <x-dashboard.field bag="contact-new" name="phone" label="Nomor WhatsApp" type="tel" placeholder="0812-3028-185" required />
                    <x-dashboard.field bag="contact-new" name="sort_order" label="Urutan" type="number" placeholder="Otomatis" :show-optional="false" />
                    <x-dashboard.item-actions submit-label="Tambah" />
                </div>
            </form>
        </section>

        {{-- Laporan keuangan --}}
        <section id="laporan" class="dash-panel" aria-labelledby="laporan-title">
            <header class="dash-panel-header">
                <h2 id="laporan-title">Laporan keuangan</h2>
                <p>Unggah laporan dalam bentuk PDF. Hanya laporan berstatus Terbit yang tampil dan bisa diunduh jamaah; laporan terbaru tampil paling atas.</p>
            </header>

            <div class="dash-list">
                @forelse ($reports as $report)
                    @include('dashboard.partials.report-item', ['report' => $report, 'categoryOptions' => $categoryOptions, 'statusOptions' => $statusOptions])
                @empty
                    <p class="dash-empty">Belum ada laporan keuangan. Unggah lewat form di bawah.</p>
                @endforelse
            </div>

            <form method="POST" action="{{ route('dashboard.ziswaf.reports.store') }}" enctype="multipart/form-data" class="dash-add">
                @csrf
                <h3>Unggah laporan</h3>
                <div class="dash-item-form dash-grid-six">
                    <x-dashboard.field bag="report-new" name="title" label="Judul laporan" placeholder="Laporan kas pekan ke-4 November 2026" required class="dash-span-3" />
                    <x-dashboard.field bag="report-new" name="category" label="Kategori" type="select" :options="$categoryOptions" required />
                    <x-dashboard.field bag="report-new" name="report_date" label="Tanggal laporan" type="date" :value="now('Asia/Jakarta')->toDateString()" required />
                    <x-dashboard.field bag="report-new" name="status" label="Status" type="select" :options="$statusOptions" value="published" required />
                    <x-dashboard.file-field bag="report-new" required class="dash-span-3" />
                    <x-dashboard.field bag="report-new" name="description" label="Keterangan" type="textarea" rows="1" class="dash-span-3" />
                    <x-dashboard.item-actions submit-label="Unggah laporan" class="dash-actions-row" />
                </div>
            </form>
        </section>
    </div>
@endsection
