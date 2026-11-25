@extends('layouts.tv')

@section('title', 'Layar Informasi Masjid Nurul Haqq')

@section('content')
    <main class="tv-screen">
        <header class="tv-header">
            <div class="tv-brand">
                <img src="{{ asset('images/icons/icon-512.png') }}" alt="Logo Masjid Nurul Haqq">
                <div>
                    <p>MASJID</p>
                    <h1>Nurul Haqq</h1>
                    <span>Yayasan Nurul Haqq</span>
                </div>
            </div>
            <div class="tv-clock-wrap">
                <time class="tv-clock" data-tv-clock aria-label="Waktu WIB"></time>
                <p>{{ $todayLabel }} <span>WIB</span></p>
                @if (filled($contact?->address))
                    <p class="tv-address">{{ $contact->address }}</p>
                @endif
            </div>
        </header>

        <section class="tv-prayer-panel" aria-labelledby="tv-prayer-heading">
            <div class="tv-section-heading">
                <div>
                    <p class="tv-eyebrow">Jadwal salat harian</p>
                    <h2 id="tv-prayer-heading">Jadwal salat</h2>
                </div>
                @if ($prayerSchedule)
                    <div class="tv-prayer-meta">
                        <strong>{{ $prayerSchedule['date'] }}</strong>
                        <span>{{ $prayerSchedule['location'] }} &middot; WIB</span>
                    </div>
                @endif
            </div>

            @if ($prayerSchedule)
                <ol class="tv-prayer-grid" aria-label="Waktu salat hari ini">
                    @foreach ($prayers as $prayer)
                        <li @class(['tv-prayer-time', 'is-next' => $prayer['is_next']]) @if ($prayer['is_next']) aria-current="time" @endif>
                            <span>{{ $prayer['name'] }}</span>
                            <strong>{{ $prayer['time'] }}</strong>
                        </li>
                    @endforeach
                </ol>
                <p class="tv-prayer-note">
                    @if ($nextPrayer)
                        Salat berikutnya: <strong>{{ $nextPrayer['name'] }} pukul {{ $nextPrayer['time'] }} WIB</strong>
                    @else
                        Jadwal salat hari ini
                    @endif
                    <span>Sumber: myQuran &middot; Sidoarjo</span>
                </p>
            @else
                <p class="tv-empty tv-prayer-unavailable">Jadwal salat belum tersedia. Silakan coba beberapa saat lagi.</p>
            @endif
        </section>

        <div class="tv-content-grid">
            <section class="tv-panel" aria-labelledby="tv-activities-heading">
                <div class="tv-section-heading tv-panel-heading">
                    <h2 id="tv-activities-heading">Kegiatan masjid</h2>
                    <span>Jadwal pengurus</span>
                </div>

                <div class="tv-activity-grid">
                    <div>
                        <h3>Kegiatan rutin</h3>
                        @forelse ($routines as $routine)
                            <div class="tv-list-row">
                                <span class="tv-list-mark" aria-hidden="true"></span>
                                <span>
                                    <strong>{{ $routine->title }}</strong>
                                    @if ($routine->schedule)
                                        <small>{{ $routine->schedule }}</small>
                                    @endif
                                </span>
                            </div>
                        @empty
                            <p class="tv-empty">Belum ada kegiatan rutin.</p>
                        @endforelse
                    </div>
                    <div>
                        <h3>Ngaji kitab</h3>
                        @forelse ($kitabSchedules as $kitab)
                            <div class="tv-list-row">
                                <span class="tv-list-mark" aria-hidden="true"></span>
                                <span>
                                    <strong>{{ $kitab->title }}</strong>
                                    <small>{{ $kitab->day->label() }} &middot; {{ $kitab->time }}</small>
                                </span>
                            </div>
                        @empty
                            <p class="tv-empty">Belum ada jadwal ngaji kitab.</p>
                        @endforelse
                    </div>
                </div>

            </section>

            <section class="tv-panel tv-ziswaf-panel" aria-labelledby="tv-ziswaf-heading">
                <div class="tv-section-heading tv-panel-heading">
                    <h2 id="tv-ziswaf-heading">Informasi ZISWAF</h2>
                    <span>Informasi resmi masjid</span>
                </div>

                <div class="tv-ziswaf-grid">
                    <div>
                        <h3>Jenis dana</h3>
                        @if ($ziswafFunds->isNotEmpty())
                            <ul class="tv-fund-list">
                                @foreach ($ziswafFunds as $fund)
                                    <li>{{ $fund->name }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="tv-empty">Informasi jenis dana belum tersedia.</p>
                        @endif

                        <div class="tv-reports">
                            <h3>Laporan keuangan terbaru</h3>
                            @forelse ($latestReports as $report)
                                <div class="tv-report-row">
                                    <span class="tv-report-icon" aria-hidden="true">PDF</span>
                                    <span>
                                        <strong>{{ $report->title }}</strong>
                                        <small>{{ $report->date_label }} &middot;
                                            {{ $report->category->shortLabel() }}</small>
                                    </span>
                                </div>
                            @empty
                                <p class="tv-empty">Belum ada laporan keuangan yang diterbitkan.</p>
                            @endforelse
                        </div>
                        <p class="tv-finance-note">Laporan dapat dilihat melalui laman ZISWAF masjid.</p>
                    </div>

                    <div class="tv-agenda tv-agenda-side">
                        <h3>Agenda mendatang</h3>
                        @forelse ($agendas as $agenda)
                            <div class="tv-agenda-row">
                                <time datetime="{{ $agenda->date->toDateString() }}">
                                    <strong>{{ $agenda->date->locale('id')->translatedFormat('j') }}</strong>
                                    <span>{{ $agenda->date->locale('id')->translatedFormat('M') }}</span>
                                </time>
                                <div>
                                    <strong>{{ $agenda->title }}</strong>
                                    <small>{{ $agenda->date->locale('id')->translatedFormat('l, j F Y') }}{{ $agenda->time ? ' · ' . $agenda->time : '' }}</small>
                                </div>
                            </div>
                        @empty
                            <p class="tv-empty">Belum ada agenda yang diterbitkan.</p>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>

        <footer class="tv-footer">
            <span>Informasi Masjid Nurul Haqq</span>
        </footer>
    </main>
@endsection
