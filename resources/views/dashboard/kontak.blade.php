@extends('layouts.dashboard')

@section('title', 'Kontak')
@section('breadcrumb', 'Kontak')

@php
    $sections = [
        'info' => 'Alamat & media sosial',
        'telepon' => 'Nomor telepon',
    ];
@endphp

@section('content')
    <div class="dash-page">
        <header class="dash-page-header">
            <div>
                <p class="dash-eyebrow">Kelola situs</p>
                <h1>Kontak</h1>
                <p>Alamat, peta, email, media sosial, dan nomor pengurus. Data ini tampil di halaman Kontak, bagian Kontak di Beranda, footer, dan semua tombol “Hubungi pengurus”.</p>
            </div>
            <x-dashboard.page-link :href="route('kontak')" />
        </header>

        <x-dashboard.status-toast />

        <nav class="dash-section-nav" aria-label="Bagian halaman Kontak">
            @foreach ($sections as $anchor => $sectionLabel)
                <a href="#{{ $anchor }}">{{ $sectionLabel }}</a>
            @endforeach
        </nav>

        {{-- Alamat & media sosial --}}
        <section id="info" class="dash-panel" aria-labelledby="info-title">
            <header class="dash-panel-header">
                <h2 id="info-title">Alamat &amp; media sosial</h2>
                <p>Isian yang dikosongkan tidak ditampilkan di situs.</p>
            </header>

            <form method="POST" action="{{ route('dashboard.kontak.update') }}" class="dash-form">
                @csrf
                @method('PUT')

                <fieldset class="dash-fieldset">
                    <legend>Lokasi</legend>
                    <x-dashboard.field bag="contact" name="address" label="Alamat masjid" type="textarea" rows="2" :value="$contact?->address"
                        placeholder="Griya Taman Asri Blok FF 08-09 RT 32 RW 08, Tawangsari, Kec. Taman, Kabupaten Sidoarjo, Jawa Timur." />
                    <x-dashboard.field bag="contact" name="maps_url" label="Tautan Google Maps" type="url" :value="$contact?->maps_url"
                        placeholder="https://maps.app.goo.gl/..."
                        hint="Untuk tombol “Buka di Google Maps”. Di Google Maps pilih Bagikan → Salin link." />
                    <x-dashboard.field bag="contact" name="maps_embed_url" label="Sematan peta" type="textarea" rows="3" :value="$contact?->maps_embed_url"
                        placeholder='<iframe src="https://www.google.com/maps/embed?pb=..." ...></iframe>'
                        hint="Peta interaktif di halaman Kontak dan Beranda. Di Google Maps pilih Bagikan → Sematkan peta → Salin HTML, lalu tempel seluruh kodenya di sini." />
                </fieldset>

                <fieldset class="dash-fieldset">
                    <legend>Email &amp; media sosial</legend>
                    <div class="dash-field-row">
                        <x-dashboard.field bag="contact" name="email" label="Email" type="email" :value="$contact?->email" placeholder="takmirmnh22@gmail.com" />
                        <x-dashboard.field bag="contact" name="instagram" label="Instagram" :value="$contact?->instagram" placeholder="masjidnurulhaqq"
                            hint="Username saja, atau tempel tautan profilnya." />
                    </div>
                    <x-dashboard.field bag="contact" name="youtube_url" label="Kanal YouTube" type="url" :value="$contact?->youtube_url"
                        placeholder="https://www.youtube.com/@..." hint="Tampil di footer dan bagian Video kajian di halaman Galeri." />
                </fieldset>

                <fieldset class="dash-fieldset">
                    <legend>Layanan sekretariat</legend>
                    <x-dashboard.field bag="contact" name="service_hours" label="Jam layanan" :value="$contact?->service_hours"
                        placeholder="Senin - Jumat, 09.00 - 15.00 WIB" />
                </fieldset>

                <div class="dash-form-actions">
                    <button type="submit" class="dash-button dash-button-primary">Simpan informasi kontak</button>
                </div>
            </form>
        </section>

        {{-- Nomor telepon --}}
        <section id="telepon" class="dash-panel" aria-labelledby="telepon-title">
            <header class="dash-panel-header">
                <h2 id="telepon-title">Nomor telepon</h2>
                <p>Nomor yang ditandai WhatsApp dibuka lewat WhatsApp; selain itu lewat panggilan telepon. Nomor WhatsApp dengan urutan paling atas menjadi tujuan semua tombol “Hubungi pengurus” di situs.</p>
            </header>

            <div class="dash-list">
                @forelse ($phones as $phone)
                    @php $bag = 'phone-'.$phone->id; @endphp
                    <div class="dash-item">
                        <form method="POST" action="{{ route('dashboard.kontak.phones.update', $phone) }}" class="dash-item-form dash-grid-phone">
                            @csrf
                            @method('PUT')
                            <x-dashboard.field :bag="$bag" name="label" label="Keterangan" :value="$phone->label" required />
                            <x-dashboard.field :bag="$bag" name="number" label="Nomor" type="tel" :value="$phone->number" required />
                            <x-dashboard.field :bag="$bag" name="sort_order" label="Urutan" type="number" :value="$phone->sort_order" :show-optional="false" />
                            <x-dashboard.visibility :bag="$bag" name="is_whatsapp" label="Jenis nomor" on-text="WhatsApp" off-text="Telepon" :checked="$phone->is_whatsapp" />
                            <x-dashboard.item-actions :delete-form="'hapus-'.$bag" :label="'nomor '.$phone->label" />
                        </form>
                        <x-dashboard.delete-form :id="'hapus-'.$bag" :action="route('dashboard.kontak.phones.destroy', $phone)" :label="'nomor '.$phone->label.' ('.$phone->number.')'" />
                    </div>
                @empty
                    <p class="dash-empty">Belum ada nomor telepon. Tambahkan lewat form di bawah.</p>
                @endforelse
            </div>

            <form method="POST" action="{{ route('dashboard.kontak.phones.store') }}" class="dash-add">
                @csrf
                <h3>Tambah nomor</h3>
                <div class="dash-item-form dash-grid-phone">
                    <x-dashboard.field bag="phone-new" name="label" label="Keterangan" placeholder="Sekretaris Takmir (Pak Gatot)" required />
                    <x-dashboard.field bag="phone-new" name="number" label="Nomor" type="tel" placeholder="0812-3028-185" required />
                    <x-dashboard.field bag="phone-new" name="sort_order" label="Urutan" type="number" placeholder="Otomatis" :show-optional="false" />
                    <x-dashboard.visibility bag="phone-new" name="is_whatsapp" label="Jenis nomor" on-text="WhatsApp" off-text="Telepon" />
                    <x-dashboard.item-actions submit-label="Tambah" />
                </div>
            </form>
        </section>
    </div>
@endsection
