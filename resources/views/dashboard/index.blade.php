@extends('layouts.dashboard')

@section('title', 'Dashboard')

@php
    $pending = collect($checklist)->reject(fn ($item) => $item['done']);
    $doneCount = count($checklist) - $pending->count();
@endphp

@section('content')
    <div class="dash-page">
        <header class="dash-page-header">
            <div>
                <p class="dash-eyebrow">{{ $today->locale('id')->translatedFormat('l, j F Y') }}</p>
                <h1>Assalamu’alaikum, {{ Auth::user()->name }}</h1>
                <p>Ringkasan isi situs Masjid Nurul Haqq dan hal yang perlu dilengkapi.</p>
            </div>
            <x-dashboard.page-link :href="route('home')" label="Lihat situs" />
        </header>

        <x-dashboard.status-toast />

        <ul class="dash-stats" aria-label="Ringkasan konten">
            @foreach ($stats as $stat)
                <li>
                    <a class="dash-stat" href="{{ $stat['url'] }}">
                        <span class="dash-stat-label">{{ $stat['label'] }}</span>
                        <strong class="dash-stat-value">{{ $stat['value'] }}</strong>
                        <span class="dash-stat-note">{{ $stat['note'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="dash-overview">
            {{-- Perlu dilengkapi --}}
            <section class="dash-panel" aria-labelledby="checklist-title">
                <header class="dash-panel-header dash-panel-header-row">
                    <h2 id="checklist-title">Kelengkapan situs</h2>
                    <span class="dash-progress-text">{{ $doneCount }} dari {{ count($checklist) }} lengkap</span>
                </header>
                <div class="dash-progress" role="progressbar" aria-label="Kelengkapan situs"
                    aria-valuemin="0" aria-valuemax="{{ count($checklist) }}" aria-valuenow="{{ $doneCount }}">
                    <span style="width: {{ round($doneCount / max(1, count($checklist)) * 100) }}%"></span>
                </div>
                <ul class="dash-checklist">
                    @foreach ($checklist as $item)
                        <li @class(['is-done' => $item['done']])>
                            <span class="dash-check-icon" aria-hidden="true">
                                @if ($item['done'])
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"></path></svg>
                                @else
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 7v6"></path><path d="M12 17h.01"></path></svg>
                                @endif
                            </span>
                            <span class="dash-checklist-copy">
                                <strong>{{ $item['label'] }}</strong>
                                <small>{{ $item['hint'] }}</small>
                            </span>
                            <span class="sr-only">{{ $item['done'] ? 'Sudah lengkap' : 'Perlu dilengkapi' }}</span>
                            @unless ($item['done'])
                                <a class="dash-text-button" href="{{ $item['url'] }}">Lengkapi<span class="sr-only"> {{ $item['label'] }}</span></a>
                            @endunless
                        </li>
                    @endforeach
                </ul>
            </section>

            <div class="dash-overview-side">
                {{-- Agenda mendatang --}}
                <section class="dash-panel" aria-labelledby="agenda-title">
                    <header class="dash-panel-header dash-panel-header-row">
                        <h2 id="agenda-title">Agenda mendatang</h2>
                        <a class="dash-text-button" href="{{ route('dashboard.jadwal') }}#agenda">Kelola</a>
                    </header>
                    @if ($upcomingAgendas->isEmpty())
                        <p class="dash-empty">Belum ada agenda terjadwal.</p>
                    @else
                        <ul class="dash-mini-list">
                            @foreach ($upcomingAgendas as $agenda)
                                <li>
                                    <span class="dash-date-chip">
                                        <strong>{{ $agenda->date->format('j') }}</strong>
                                        {{ $agenda->date->locale('id')->translatedFormat('M') }}
                                    </span>
                                    <span>
                                        <strong>{{ $agenda->title }}</strong>
                                        <small>{{ $agenda->date->locale('id')->translatedFormat('l') }}@if ($agenda->time) · {{ $agenda->time }}@endif @if ($agenda->speaker) · {{ $agenda->speaker }}@endif</small>
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>

                {{-- Laporan keuangan terbaru --}}
                <section class="dash-panel" aria-labelledby="laporan-title">
                    <header class="dash-panel-header dash-panel-header-row">
                        <h2 id="laporan-title">Laporan keuangan terbaru</h2>
                        <a class="dash-text-button" href="{{ route('dashboard.ziswaf') }}#laporan">Unggah</a>
                    </header>
                    @if ($latestReports->isEmpty())
                        <p class="dash-empty">Belum ada laporan yang terbit.</p>
                    @else
                        <ul class="dash-mini-list">
                            @foreach ($latestReports as $report)
                                <li>
                                    <span class="dash-file-chip" aria-hidden="true">PDF</span>
                                    <span>
                                        <strong>{{ $report->title }}</strong>
                                        <small>{{ $report->category->shortLabel() }} · {{ $report->date_label }}</small>
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>
            </div>
        </div>

        {{-- Halaman kelola --}}
        <section class="dash-panel" aria-labelledby="pages-title">
            <header class="dash-panel-header">
                <h2 id="pages-title">Kelola halaman</h2>
                <p>Pilih bagian situs yang ingin diubah.</p>
            </header>
            <ul class="dash-page-grid">
                @foreach ($pages as $page)
                    <li>
                        <a class="dash-page-card" href="{{ $page['url'] }}">
                            <strong>{{ $page['label'] }}</strong>
                            <span>{{ $page['description'] }}</span>
                            <small>
                                @if ($page['updated'])
                                    Diperbarui {{ $page['updated']->locale('id')->diffForHumans() }}
                                @else
                                    Belum pernah diisi
                                @endif
                            </small>
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>
    </div>
@endsection
