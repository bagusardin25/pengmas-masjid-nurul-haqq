@extends('layouts.app')

@section('title', 'Jadwal & Kegiatan')
@section('description', 'Jadwal sholat dan kegiatan rutin jamaah Masjid Jami Nurul Haqq.')

@section('content')
    <x-page-hero eyebrow="Jadwal & kegiatan" :title="$page?->hero_title ?? 'Jadwal & Kegiatan'" :description="$page?->hero_description" action-url="https://wa.me/6281239567390" action-label="Tanya jadwal ke pengurus" />

    <section class="section">
        <div class="site-container">
            <div class="prayer-banner">
                @if ($prayerSchedule)
                    <div>
                        <p class="eyebrow eyebrow-light">Jadwal sholat hari ini</p>
                        <h2>{{ $prayerSchedule['date'] }}</h2>
                        <p>{{ $prayerSchedule['location'] }} dan sekitarnya · WIB</p>
                    </div>
                    <ol class="prayer-grid" aria-label="Waktu sholat">
                        @foreach ($prayers as $prayer)
                            <li @class(['prayer-time', 'is-next' => $prayer['is_next']]) @if ($prayer['is_next']) aria-current="time" @endif>
                                <span>{{ $prayer['name'] }}</span><strong>{{ $prayer['time'] }}</strong>
                            </li>
                        @endforeach
                    </ol>
                @else
                    <div>
                        <p class="eyebrow eyebrow-light">Jadwal sholat hari ini</p>
                        <h2>Jadwal sholat tidak tersedia saat ini</h2>
                        <p>Silakan muat ulang halaman beberapa saat lagi.</p>
                    </div>
                @endif
            </div>
            <p class="data-note"><strong>Sumber: myQuran</strong> Waktu sholat untuk wilayah Sidoarjo, diperbarui setiap hari.</p>
        </div>
    </section>

    <section class="section section-muted">
        <div class="site-container">
            <x-section-heading eyebrow="Kegiatan jamaah" title="Bertemu dalam kebaikan" />
            <div class="schedule-columns">
                <article class="content-panel">
                    <h3>Kegiatan rutin</h3>
                    @forelse ($routines as $routine)
                        <div class="schedule-row"><span class="card-icon" aria-hidden="true">▤</span><span class="schedule-copy"><strong>{{ $routine->title }}</strong>@if ($routine->schedule)<small>{{ $routine->schedule }}</small>@endif</span>@if ($routine->badge)<span class="tag">{{ $routine->badge }}</span>@endif</div>
                    @empty
                        <p>Belum ada kegiatan rutin.</p>
                    @endforelse
                    @if (filled($page?->routine_note))
                        <p class="panel-note">{{ $page->routine_note }}</p>
                    @endif
                </article>
                <article class="content-panel content-panel-dark">
                    <p class="eyebrow eyebrow-light">Agenda terdekat</p>
                    @forelse ($agendas as $agenda)
                        @php($date = $agenda->date->locale('id'))
                        <div class="event-row">
                            <span class="date-card"><strong>{{ $date->day }}</strong><small>{{ $date->translatedFormat('M') }}</small></span>
                            <span>
                                <strong>{{ $agenda->title }}</strong>
                                @if ($agenda->speaker)<small>{{ $agenda->speaker }}</small>@endif
                                <small>{{ $date->translatedFormat('l, j F Y') }}{{ $agenda->time ? ' · '.$agenda->time : '' }}{{ $agenda->location ? ' · '.$agenda->location : '' }}</small>
                            </span>
                        </div>
                    @empty
                        <p>Belum ada agenda terjadwal. Agenda berikutnya akan diumumkan oleh takmir.</p>
                    @endforelse
                    <a class="button button-gold button-full" href="https://wa.me/6281239567390">Konfirmasi kehadiran</a>
                </article>
            </div>
            <article class="content-panel quran-schedule">
                <x-section-heading eyebrow="Ngaji kitab" title="Jadwal Ngaji Kitab" />
                @forelse ($kitabSchedules as $kitab)
                    <div class="schedule-row"><span class="card-icon" aria-hidden="true">▣</span><span class="schedule-copy"><strong>{{ $kitab->title }}</strong><small>{{ $kitab->speaker }} · {{ $kitab->day->label() }}, {{ $kitab->time }}</small>@if ($kitab->location)<small class="muted-text">{{ $kitab->location }}</small>@endif</span><span class="tag">{{ $kitab->day->label() }}</span></div>
                @empty
                    <p>Belum ada jadwal ngaji kitab.</p>
                @endforelse
                @if (filled($page?->kitab_note))
                    <p class="panel-note">{{ $page->kitab_note }}</p>
                @endif
            </article>
        </div>
    </section>

    <x-donation-cta />
@endsection
