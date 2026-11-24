@extends('layouts.app')

@section('title', 'Jadwal & Kegiatan')
@section('description', 'Jadwal sholat dan kegiatan rutin jamaah Masjid Jami Nurul Haqq.')

@section('after-header')
    <x-prayer-led-bar
        :prayers="$prayers"
        :next-prayer="collect($prayers)->firstWhere('is_next', true)"
        :location="$prayerSchedule['location'] ?? null" />
@endsection

@section('content')
    <x-page-hero eyebrow="Jadwal & kegiatan" :title="$page?->hero_title ?? 'Jadwal & Kegiatan'" :description="$page?->hero_description" :action-url="$siteContact->contactUrl()" action-label="Tanya jadwal ke pengurus" />

    <section class="section">
        <div class="site-container">
            <div class="prayer-banner hero-stagger" style="--d: 260ms">
                @if ($prayerSchedule)
                    <div>
                        <p class="eyebrow eyebrow-light">Jadwal sholat hari ini</p>
                        <h2>{{ $prayerSchedule['date'] }}</h2>
                        <p>{{ $prayerSchedule['location'] }} dan sekitarnya · WIB</p>
                    </div>
                    <ol class="prayer-grid" aria-label="Waktu sholat">
                        @foreach ($prayers as $prayer)
                            <li style="--d: {{ 300 + ($loop->index * 45) }}ms" @class(['prayer-tile', 'hero-stagger', 'prayer-time', 'is-next' => $prayer['is_next']]) @if ($prayer['is_next']) aria-current="time" @endif>
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
            <p class="data-note hero-stagger" style="--d: 380ms"><strong>Sumber: myQuran</strong> Waktu sholat untuk wilayah Sidoarjo, diperbarui setiap hari.</p>
        </div>
    </section>

    <section class="section section-muted">
        <div class="site-container">
            <div data-reveal><x-section-heading eyebrow="Kegiatan jamaah" title="Bertemu dalam kebaikan" /></div>
            <div class="schedule-columns" data-reveal-group>
                <div class="schedule-left-stack">
                    <article data-reveal class="content-panel">
                        <h3>Kegiatan rutin</h3>
                        @forelse ($routines as $routine)
                            <div class="schedule-row"><span class="card-icon" aria-hidden="true"><i class="fa-solid fa-mosque" aria-hidden="true"></i></span><span class="schedule-copy"><strong>{{ $routine->title }}</strong>@if ($routine->schedule)<small>{{ $routine->schedule }}</small>@endif</span>@if ($routine->badge)<span class="tag">{{ $routine->badge }}</span>@endif</div>
                        @empty
                            <p>Belum ada kegiatan rutin.</p>
                        @endforelse
                        @if (filled($page?->routine_note))
                            <p class="panel-note">{{ $page->routine_note }}</p>
                        @endif
                    </article>
                    <article data-reveal class="content-panel quran-schedule">
                        <x-section-heading eyebrow="Ngaji kitab" title="Jadwal Ngaji Kitab" />
                        @forelse ($kitabSchedules as $kitab)
                            <div class="schedule-row"><span class="card-icon" aria-hidden="true"><i class="fa-solid fa-book-open" aria-hidden="true"></i></span><span class="schedule-copy"><strong>{{ $kitab->title }}</strong><small>{{ $kitab->speaker }} · {{ $kitab->day->label() }}, {{ $kitab->time }}</small>@if ($kitab->location)<small class="muted-text">{{ $kitab->location }}</small>@endif</span><span class="tag">{{ $kitab->day->label() }}</span></div>
                        @empty
                            <p>Belum ada jadwal ngaji kitab.</p>
                        @endforelse
                        @if (filled($page?->kitab_note))
                            <p class="panel-note">{{ $page->kitab_note }}</p>
                        @endif
                    </article>
                </div>
                <article data-reveal class="content-panel content-panel-dark schedule-agenda-panel">
                    <div>
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
                    </div>
                    <a class="button button-gold button-full" href="{{ $siteContact->contactUrl() }}">Konfirmasi kehadiran</a>
                </article>
            </div>
        </div>
    </section>

    <x-donation-cta />
@endsection
