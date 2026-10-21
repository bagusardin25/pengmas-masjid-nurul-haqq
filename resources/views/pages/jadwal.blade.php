@extends('layouts.app')

@section('title', 'Jadwal & Kegiatan')
@section('description', 'Jadwal sholat dan kegiatan rutin jamaah Masjid Jami Nurul Haqq.')

@section('content')
    <x-page-hero eyebrow="Jadwal & kegiatan" title="Ibadah dan kajian setiap pekan" description="Temukan jadwal sholat, kegiatan rutin jamaah, dan informasi agenda Masjid Jami Nurul Haqq." action-url="https://wa.me/6281239567390" action-label="Tanya jadwal ke pengurus" />

    <section class="section">
        <div class="container">
            <div class="prayer-banner">
                <div><p class="eyebrow eyebrow-light">Jadwal sholat · simulasi</p><h2>Kamis, 15 Oktober 2026</h2><p>Sidoarjo dan sekitarnya · WIB</p></div>
                <div class="prayer-grid">
                    @foreach (['Subuh' => '03:56', 'Terbit' => '05:10', 'Dzuhur' => '11:20', 'Ashar' => '14:25', 'Maghrib' => '17:26', 'Isya' => '18:35'] as $name => $time)
                        <div class="prayer-time"><span>{{ $name }}</span><strong>{{ $time }}</strong></div>
                    @endforeach
                </div>
            </div>
            <p class="data-note"><strong>Data contoh · jadwal simulasi</strong> Waktu di atas hanya simulasi untuk Kamis, 15 Oktober 2026 — bukan jadwal resmi atau live.</p>
        </div>
    </section>

    <section class="section section-muted">
        <div class="container">
            <x-section-heading eyebrow="Kegiatan jamaah" title="Bertemu dalam kebaikan" />
            <div class="schedule-columns">
                <article class="content-panel">
                    <h3>Kegiatan rutin</h3>
                    @foreach ([['Sholat fardhu berjamaah', '5 waktu sehari', 'Setiap hari'], ['Sholat Jumat', 'Setiap Jumat', 'Jumat'], ['Pengajian rutin', 'Ahad · 19.30 WIB', 'Ahad'], ['Kajian Islam', 'Rabu · 19.30 WIB', 'Rabu']] as [$title, $detail, $tag])
                        <div class="schedule-row"><span class="card-icon">▤</span><span class="schedule-copy"><strong>{{ $title }}</strong><small>{{ $detail }}</small></span><span class="tag">{{ $tag }}</span></div>
                    @endforeach
                    <p class="panel-note">Untuk perubahan kegiatan rutin, silakan hubungi pengurus masjid.</p>
                </article>
                <article class="content-panel content-panel-dark">
                    <p class="eyebrow eyebrow-light">Agenda yang tercantum</p>
                    @foreach ([['18', 'Okt', 'Pengajian Akbar', 'Ust. Abdullah', '19.30 WIB – selesai'], ['25', 'Okt', 'Peringatan Maulid Nabi', 'Panitia Maulid 1448 H', '08.00 WIB – selesai']] as [$day, $month, $title, $host, $time])
                        <div class="event-row"><span class="date-card"><strong>{{ $day }}</strong><small>{{ $month }}</small></span><span><strong>{{ $title }}</strong><small>{{ $host }}</small><small>{{ $time }} · Halaman masjid</small></span></div>
                    @endforeach
                    <p class="demo-caption">Data contoh · jadwal simulasi</p>
                    <a class="button button-gold button-full" href="https://wa.me/6281239567390">Konfirmasi kehadiran</a>
                </article>
            </div>
            <article class="content-panel quran-schedule">
                <x-section-heading eyebrow="Ngaji kitab" title="Jadwal Ngaji Kitab" />
                @foreach ([['Safinatun Najah', 'Ust. Ahmad Fauzi', 'Sabtu, 18.00 WIB (ba’da Maghrib)', 'Sabtu'], ['Riyadhus Shalihin', 'Ust. Muhammad Ridwan', 'Selasa, 19.30 WIB', 'Selasa']] as [$title, $teacher, $time, $day])
                    <div class="schedule-row"><span class="card-icon">▣</span><span class="schedule-copy"><strong>{{ $title }}</strong><small>{{ $teacher }} · {{ $time }}</small><small class="muted-text">Ruang utama masjid</small></span><span class="tag">{{ $day }}</span></div>
                @endforeach
                <p class="panel-note">Hubungi pengurus untuk konfirmasi jadwal ngaji kitab terkini.</p>
            </article>
        </div>
    </section>

    <x-donation-cta />
@endsection
