@props(['prayerSchedule', 'prayers', 'nextPrayer', 'today'])

<div
    class="flex flex-wrap items-center [gap:24px_40px] [padding:28px_32px] rounded-[24px] bg-[#0B3B2C] text-[#FFFFFF] shadow-[0_24px_48px_rgba(11,59,44,0.18)]">
    <div class="[flex:1_1_260px]">
        <h2 id="judul-sholat" class="[margin:0] text-[12px] font-bold tracking-[0.14em] text-[#E2B84B]">
            JADWAL SHOLAT HARI INI</h2>
        <p class="[margin:6px_0_0] text-[21px] font-extrabold leading-[1.3]">
            {{ $prayerSchedule['date'] ?? $today->locale('id')->translatedFormat('l, d F Y') }}
        </p>
        <p class="[margin:2px_0_0] text-[14px] text-[#B9CCC3]">
            {{ $prayerSchedule['location'] ?? 'Sidoarjo' }} dan sekitarnya
        </p>
        @if ($nextPrayer)
            <p
                class="[margin:12px_0_0] inline-flex items-center [gap:8px] [padding:6px_12px] rounded-[999px] bg-[rgba(226,184,75,0.16)] text-[#F3D88C] text-[13px] font-bold">
                <span class="w-[8px] h-[8px] rounded-[999px] bg-[#E2B84B]"></span>
                Sholat berikutnya: {{ $nextPrayer['name'] }} pukul {{ $nextPrayer['time'] }}
            </p>
        @elseif ($prayerSchedule)
            <p
                class="[margin:12px_0_0] inline-flex items-center [gap:8px] [padding:6px_12px] rounded-[999px] bg-[rgba(226,184,75,0.16)] text-[#F3D88C] text-[13px] font-bold">
                Semua waktu sholat hari ini telah berlalu
            </p>
        @else
            <p class="[margin:12px_0_0] text-[13px] text-[#F3D88C]">
                Jadwal sholat belum dapat dimuat. Silakan coba beberapa saat lagi.
            </p>
        @endif
    </div>
    <ol
        class="[flex:3_1_560px] min-w-0 list-none [margin:0] [padding:0] grid [grid-template-columns:repeat(auto-fit,minmax(92px,1fr))] [gap:10px]"
        aria-label="Waktu sholat">
        @forelse ($prayers as $prayer)
            <li
                @if ($prayer['is_next']) aria-current="time" class="p-[14px_10px] rounded-2xl bg-[#E2B84B] text-[#0B3B2C] text-center" @else class="p-[14px_10px] rounded-2xl bg-[rgba(255,255,255,0.07)] text-center" @endif>
                <span
                    @if ($prayer['is_next']) class="block text-[13px] font-extrabold" @else class="block text-[13px] font-semibold text-[#B9CCC3]" @endif>
                    {{ $prayer['name'] }}
                </span>
                <span class="block mt-[2px] text-[23px] font-extrabold tracking-[-0.01em]">{{ $prayer['time'] }}</span>
            </li>
        @empty
            <li class="col-span-full [padding:14px_10px] text-[#B9CCC3] text-center">
                Jadwal sholat tidak tersedia saat ini.
            </li>
        @endforelse
    </ol>
</div>
