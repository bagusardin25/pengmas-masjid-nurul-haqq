@props([
    'prayers' => [],
    'nextPrayer' => null,
    'location' => null,
    'date' => null,
])

<div class="prayer-led-bar" aria-label="Jam sholat berjalan">
    <div class="prayer-led-bar-inner">
        <div class="prayer-led-clock-badge">
            <span class="prayer-live-dot" aria-hidden="true"></span>
            <span class="prayer-led-clock-text tabular-nums" data-live-clock>{{ now('Asia/Jakarta')->format('H:i:s') }} WIB</span>
        </div>

        <div class="prayer-led-viewport" data-led-prayer>
            <div class="prayer-led-track">
                @if ($date || $location)
                    <span class="prayer-led-chip">
                        @if ($date)
                            <span class="prayer-led-label">{{ $date }}</span>
                        @endif
                        <strong class="prayer-led-time">{{ $location ?: 'Sidoarjo' }} dan sekitarnya</strong>
                    </span>
                    <span class="prayer-led-sep" aria-hidden="true">|</span>
                @endif
                @forelse ($prayers as $prayer)
                    <span @class(['prayer-led-chip', 'is-next' => $prayer['is_next']]) @if ($prayer['is_next']) aria-current="time" @endif>
                        <span class="prayer-led-label">{{ $prayer['name'] }}</span>
                        <strong class="prayer-led-time tabular-nums">{{ $prayer['time'] }}</strong>
                        @if ($prayer['is_next'])
                            <span class="prayer-led-tag">Sholat berikutnya: {{ $prayer['name'] }} {{ $prayer['time'] }}</span>
                        @endif
                    </span>
                    <span class="prayer-led-sep" aria-hidden="true">|</span>
                @empty
                    <span class="prayer-led-chip">
                        <span class="prayer-led-label">Jadwal sholat</span>
                        <strong class="prayer-led-time">Masjid Jami Nurul Haqq</strong>
                    </span>
                    <span class="prayer-led-sep" aria-hidden="true">|</span>
                @endforelse
            </div>
        </div>
    </div>
</div>
