@extends('layouts.app')

@section('title', 'Jadwal & Kegiatan')
@section('description', 'Jadwal sholat dan kegiatan rutin jamaah Masjid Nurul Haqq.')

@section('content')
    <x-page-hero eyebrow="Jadwal & kegiatan" :title="$page?->hero_title ?? 'Jadwal & Kegiatan'" :description="$page?->hero_description" :action-url="$siteContact->contactUrl()"
        action-label="Tanya jadwal ke pengurus" />

    <section class="section schedule-prayer" aria-labelledby="judul-sholat">
        <div class="site-container">
            <x-prayer-schedule-card :prayer-schedule="$prayerSchedule" :prayers="$prayers" :next-prayer="$nextPrayer" :today="$today" />
        </div>
    </section>

    <section class="section section-muted schedule-activities">
        <div class="site-container">
            <x-section-heading eyebrow="Kegiatan jamaah" title="Bertemu dalam kebaikan" />
            <div class="schedule-columns">
                <article class="content-panel">
                    <h3>Kegiatan rutin</h3>
                    @forelse ($routines as $routine)
                        <div class="schedule-row"><span class="card-icon" aria-hidden="true">▤</span><span
                                class="schedule-copy"><strong>{{ $routine->title }}</strong>
                                @if ($routine->schedule)
                                    <small>{{ $routine->schedule }}</small>
                                @endif
                            </span>
                            @if ($routine->badge)
                                <span class="tag">{{ $routine->badge }}</span>
                            @endif
                        </div>
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
                            <span
                                class="date-card"><strong>{{ $date->day }}</strong><small>{{ $date->translatedFormat('M') }}</small></span>
                            <span>
                                <strong>{{ $agenda->title }}</strong>
                                @if ($agenda->speaker)
                                    <small>{{ $agenda->speaker }}</small>
                                @endif
                                <small>{{ $date->translatedFormat('l, j F Y') }}{{ $agenda->time ? ' · ' . $agenda->time : '' }}{{ $agenda->location ? ' · ' . $agenda->location : '' }}</small>
                            </span>
                        </div>
                    @empty
                        <p>Belum ada agenda terjadwal. Agenda berikutnya akan diumumkan oleh takmir.</p>
                    @endforelse
                    <a class="button button-gold button-full" href="{{ $siteContact->contactUrl() }}">Konfirmasi
                        kehadiran</a>
                </article>
            </div>
            <article class="content-panel quran-schedule">
                <x-section-heading eyebrow="Ngaji kitab" title="Jadwal Ngaji Kitab" />
                @forelse ($kitabSchedules as $kitab)
                    <div class="schedule-row"><span class="card-icon" aria-hidden="true">▣</span><span
                            class="schedule-copy"><strong>{{ $kitab->title }}</strong><small>{{ $kitab->speaker }} ·
                                {{ $kitab->day->label() }}, {{ $kitab->time }}</small>
                            @if ($kitab->location)
                                <small class="muted-text">{{ $kitab->location }}</small>
                            @endif
                        </span><span class="tag">{{ $kitab->day->label() }}</span></div>
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
