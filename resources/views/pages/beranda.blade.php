<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masjid Nurul Haqq — Griya Taman Asri, Sidoarjo</title>
    <meta name="description"
        content="Masjid Jami Nurul Haqq: jadwal sholat, kegiatan, Program TPQ, dan layanan ZISWAF untuk jamaah Griya Taman Asri, Tawangsari, Sidoarjo.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;family=Amiri:wght@400;700&amp;display=swap">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body>
    <div
        class="home-page min-h-screen flex flex-col bg-[#F8F6EF] text-[#17231E] font-sans text-[16px] leading-[1.6] overflow-x-clip">

        <x-site-header />

        <main id="main-content" class="[flex:0_0_auto]">

            <section id="beranda" class="[padding:56px_0_136px] bg-[#FFFFFF]">
                <div
                    class="max-w-[1440px] mx-auto px-[clamp(20px,4.5vw,64px)] grid [grid-template-columns:repeat(auto-fit,minmax(min(100%,480px),1fr))] [gap:56px] items-center">
                    <div>
                        <p
                            class="[margin:0] inline-flex items-center [gap:8px] [padding:7px_14px] rounded-[999px] bg-[#EEF5F1] text-[#0B3B2C] text-[12px] font-bold tracking-[0.1em]">
                            {{ $hero?->hero_eyebrow }}
                        </p>
                        <h1
                            class="[margin:22px_0_0] text-[clamp(48px,6vw,84px)] leading-[1.02] font-extrabold tracking-[-0.025em] text-[#0B3B2C]">
                            {{ $hero?->hero_title }}
                        </h1>
                        <p class="[margin:22px_0_0] max-w-[540px] text-[18px] leading-[1.7] text-[#42524B]">
                            {{ $hero?->hero_description }}
                        </p>
                        <p
                            class="[margin:24px_0_0] max-w-[520px] flex [gap:10px] items-start text-[15px] leading-[1.6] text-[#42524B]">
                            <span class="shrink-0 mt-[1px] text-[#8A6A12]"><svg width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg></span><span>{{ $contact?->address }}</span>
                        </p>
                        <div class="mt-[32px] flex flex-wrap [gap:12px]">
                            <a href="{{ route('jadwal') }}"
                                class="w-full md:w-max inline-flex items-center [gap:10px] min-h-[52px] [padding:0_24px] rounded-[14px] bg-[#0B3B2C] text-[#FFFFFF] text-[16px] font-bold no-underline"><svg
                                    width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                                    <path d="M16 2v4M8 2v4M3 10h18"></path>
                                </svg>Jadwal Kegiatan</a>
                            <a href="{{ route('kontak') }}"
                                class="w-full md:w-max inline-flex items-center [gap:10px] min-h-[52px] [padding:0_24px] rounded-[14px] [border:1.5px_solid_#0B3B2C] text-[#0B3B2C] text-[16px] font-bold no-underline"><svg
                                    width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path
                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                    </path>
                                </svg>Hubungi Pengurus</a>
                        </div>
                    </div>
                    <div class="hero-media max-[640px]:pb-0 relative pb-[40px]">
                        <figure
                            class="[margin:0] aspect-[6/5] rounded-[320px_320px_28px_28px] overflow-hidden bg-[#E4ECE6]">
                            <img src="{{ asset('images/beranda/hero-masjid-pintu-1200.jpg') }}"
                                srcset="{{ asset('images/beranda/hero-masjid-pintu-720.jpg') }} 720w, {{ asset('images/beranda/hero-masjid-pintu-1200.jpg') }} 1200w"
                                sizes="(min-width: 1100px) 640px, calc(100vw - 40px)" width="1200" height="1000"
                                alt="Pintu masuk Masjid Nurul Haqq dengan papan nama dan fasad yang diterangi lampu pada malam hari"
                                fetchpriority="high" decoding="async" class="block w-full h-[100%] object-cover">
                        </figure>
                        <blockquote
                            class="hero-quote max-[640px]:[position:relative] max-[640px]:left-auto max-[640px]:bottom-auto max-[640px]:w-auto max-[640px]:[margin:-64px_16px_0] absolute left-[24px] bottom-[0] [margin:0] w-[min(400px,calc(100% - 48px))] [padding:22px_24px] rounded-[20px] bg-[#0B3B2C] text-[#FFFFFF] shadow-[0_20px_44px_rgba(11,59,44,0.28)]">
                            <p lang="ar" dir="rtl"
                                class="[margin:0] font-[Amiri,serif] text-[22px] leading-[1.8] text-[#E2B84B]">
                                {{ $hero?->hero_arabic_text }}
                            </p>
                            <p class="[margin:8px_0_0] text-[14px] leading-[1.6] text-[#E3ECE7]">
                                {{ $hero?->hero_translation }}
                            </p>
                            <cite
                                class="block mt-[10px] not-italic text-[12px] font-bold tracking-[0.12em] text-[#E2B84B]">
                                {{ $hero?->hero_citation }}
                            </cite>
                        </blockquote>
                    </div>
                </div>
            </section>

            <section aria-labelledby="judul-sholat" class="relative mt-[-84px]">
                <div class="max-w-[1440px] mx-auto px-[clamp(20px,4.5vw,64px)]">
                    <div
                        class="flex flex-wrap items-center [gap:24px_40px] [padding:28px_32px] rounded-[24px] bg-[#0B3B2C] text-[#FFFFFF] shadow-[0_24px_48px_rgba(11,59,44,0.18)]">
                        <div class="[flex:1_1_260px]">
                            <h2 id="judul-sholat"
                                class="[margin:0] text-[12px] font-bold tracking-[0.14em] text-[#E2B84B]">
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
                            class="[flex:3_1_560px] min-w-0 list-none [margin:0] [padding:0] grid [grid-template-columns:repeat(auto-fit,minmax(92px,1fr))] [gap:10px]">
                            @forelse ($prayers as $prayer)
                                <li
                                    @if ($prayer['is_next']) aria-current="time" class="p-[14px_10px] rounded-2xl bg-[#E2B84B] text-[#0B3B2C] text-center" @else class="p-[14px_10px] rounded-2xl bg-[rgba(255,255,255,0.07)] text-center" @endif>
                                    <span
                                        @if ($prayer['is_next']) class="block text-[13px] font-extrabold" @else class="block text-[13px] font-semibold text-[#B9CCC3]" @endif>
                                        {{ $prayer['name'] }}
                                    </span>
                                    <span
                                        class="block mt-[2px] text-[23px] font-extrabold tracking-[-0.01em]">{{ $prayer['time'] }}</span>
                                </li>
                            @empty
                                <li class="col-span-full [padding:14px_10px] text-[#B9CCC3] text-center">
                                    Jadwal sholat tidak tersedia saat ini.
                                </li>
                            @endforelse
                        </ol>
                    </div>
                </div>
            </section>

            <section id="profil" class="[padding:88px_0_96px]">
                <div class="max-w-[1440px] mx-auto px-[clamp(20px,4.5vw,64px)]">
                    <div
                        class="grid [grid-template-columns:repeat(auto-fit,minmax(min(100%,460px),1fr))] [gap:48px_64px] items-start">
                        <div>
                            <p
                                class="[margin:0] flex items-center [gap:10px] text-[12px] font-bold tracking-[0.14em] text-[#8A6A12]">
                                <span class="w-[24px] h-[2px] bg-[#C9971C]"></span>PROFIL MASJID
                            </p>
                            <h2
                                class="[margin:14px_0_0] text-[clamp(30px,3.4vw,44px)] leading-[1.15] font-extrabold tracking-[-0.02em] text-[#0B3B2C]">
                                {{ $about?->hero_title }}</h2>
                            <p class="[margin:20px_0_0] text-[17px] leading-[1.75] text-[#42524B]">
                                {{ $about?->history_content }}</p>
                            <a href="/profil"
                                class="mt-[20px] inline-flex items-center [gap:8px] min-h-[44px] text-[15px] font-bold text-[#0B3B2C] no-underline">Baca
                                profil lengkap<svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M5 12h14"></path>
                                    <path d="m12 5 7 7-7 7"></path>
                                </svg></a>
                        </div>
                        <div class="grid [grid-template-columns:repeat(2,minmax(0,1fr))] [gap:16px]">
                            <div class="[padding:24px] rounded-[20px] bg-[#FFFFFF] [border:1px_solid_#E6E2D6]">
                                <span
                                    class="w-[46px] h-[46px] rounded-[14px] bg-[#EEF5F1] text-[#0B3B2C] flex items-center justify-center"><svg
                                        width="22" height="22" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                        stroke-linejoin="round" aria-hidden="true">
                                        <path
                                            d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3">
                                        </path>
                                    </svg></span>
                                <p
                                    class="[margin:16px_0_0] text-[30px] font-extrabold leading-[1.1] tracking-[-0.01em] text-[#0B3B2C]">
                                    270 m²</p>
                                <p class="[margin:6px_0_0] text-[14px] text-[#4A5A53]">Luas tanah</p>
                            </div>
                            <div class="[padding:24px] rounded-[20px] bg-[#FFFFFF] [border:1px_solid_#E6E2D6]">
                                <span
                                    class="w-[46px] h-[46px] rounded-[14px] bg-[#EEF5F1] text-[#0B3B2C] flex items-center justify-center"><svg
                                        width="22" height="22" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                        stroke-linejoin="round" aria-hidden="true">
                                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                        <path d="M9 22V12h6v10"></path>
                                    </svg></span>
                                <p
                                    class="[margin:16px_0_0] text-[30px] font-extrabold leading-[1.1] tracking-[-0.01em] text-[#0B3B2C]">
                                    150 m²</p>
                                <p class="[margin:6px_0_0] text-[14px] text-[#4A5A53]">Luas bangunan</p>
                            </div>
                            <div class="[padding:24px] rounded-[20px] bg-[#FFFFFF] [border:1px_solid_#E6E2D6]">
                                <span
                                    class="w-[46px] h-[46px] rounded-[14px] bg-[#EEF5F1] text-[#0B3B2C] flex items-center justify-center"><svg
                                        width="22" height="22" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                        stroke-linejoin="round" aria-hidden="true">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg></span>
                                <p
                                    class="[margin:16px_0_0] text-[30px] font-extrabold leading-[1.1] tracking-[-0.01em] text-[#0B3B2C]">
                                    600</p>
                                <p class="[margin:6px_0_0] text-[14px] text-[#4A5A53]">Daya tampung jamaah</p>
                            </div>
                            <div class="[padding:24px] rounded-[20px] bg-[#FFFFFF] [border:1px_solid_#E6E2D6]">
                                <span
                                    class="w-[46px] h-[46px] rounded-[14px] bg-[#EEF5F1] text-[#0B3B2C] flex items-center justify-center"><svg
                                        width="22" height="22" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                        stroke-linejoin="round" aria-hidden="true">
                                        <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z">
                                        </path>
                                        <path d="M14 2v6h6"></path>
                                        <path d="m9 15 2 2 4-4"></path>
                                    </svg></span>
                                <p
                                    class="[margin:16px_0_0] text-[30px] font-extrabold leading-[1.1] tracking-[-0.01em] text-[#0B3B2C]">
                                    Wakaf</p>
                                <p class="[margin:6px_0_0] text-[14px] text-[#4A5A53]">Status tanah</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-[64px]">
                        <div class="flex flex-wrap items-baseline justify-between [gap:8px_24px]">
                            <h3 class="[margin:0] text-[22px] font-extrabold text-[#0B3B2C]">Fasilitas</h3>
                            <p class="[margin:0] text-[15px] text-[#4A5A53]">Sarana yang tersedia untuk kenyamanan
                                ibadah jamaah</p>
                        </div>
                        <ul
                            class="list-none [margin:20px_0_0] [padding:0] grid [grid-template-columns:repeat(auto-fit,minmax(180px,1fr))] [gap:12px]">
                            @foreach ($facilities as $facility)
                                <li
                                    class="flex items-center [gap:14px] [padding:16px_18px] rounded-[16px] bg-[#FFFFFF] [border:1px_solid_#E6E2D6] text-[15px] font-bold text-[#17231E]">
                                    <span class="facility-mark">✓</span>
                                    {{ $facility->name }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </section>

            <section id="jadwal" class="[padding:96px_0] bg-[#FFFFFF]">
                <div class="max-w-[1440px] mx-auto px-[clamp(20px,4.5vw,64px)]">
                    <div class="flex flex-wrap items-end justify-between [gap:16px_32px]">
                        <div>
                            <p
                                class="[margin:0] flex items-center [gap:10px] text-[12px] font-bold tracking-[0.14em] text-[#8A6A12]">
                                <span class="w-[24px] h-[2px] bg-[#C9971C]"></span>JADWAL &amp; KEGIATAN
                            </p>
                            <h2
                                class="[margin:14px_0_0] text-[clamp(30px,3.4vw,44px)] leading-[1.15] font-extrabold tracking-[-0.02em] text-[#0B3B2C]">
                                Ibadah dan kajian setiap pekan</h2>
                        </div>
                        <a href="#jadwal"
                            class="inline-flex items-center [gap:8px] min-h-[44px] text-[15px] font-bold text-[#0B3B2C] no-underline">Lihat
                            semua kegiatan<svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 12h14"></path>
                                <path d="m12 5 7 7-7 7"></path>
                            </svg></a>
                    </div>
                    <div class="mt-[40px] flex flex-wrap [gap:24px] items-stretch">
                        <div class="[flex:1.5_1_520px] min-w-0 [padding:8px_28px] rounded-[24px] bg-[#F8F6EF]">
                            <h3 class="[margin:18px_0_4px] text-[13px] font-bold tracking-[0.12em] text-[#8A6A12]">
                                KEGIATAN RUTIN</h3>
                            <ul class="list-none [margin:0] [padding:0]">
                                @foreach ($routines as $routine)
                                    <li
                                        class="flex flex-wrap items-center [gap:12px_16px] [padding:18px_0] [border-bottom:1px_solid_#E6E2D6] last:border-b-0">
                                        <span
                                            class="w-[48px] h-[48px] rounded-[14px] bg-[#FFFFFF] text-[#0B3B2C] flex items-center justify-center shrink-0"><svg
                                                width="22" height="22" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                                stroke-linejoin="round" aria-hidden="true">
                                                <path d="M3 20.5h18"></path>
                                                <path d="M5.5 20.5v-6.2a6.5 6.5 0 0 1 13 0v6.2"></path>
                                                <path d="M12 7.8V4.6"></path>
                                                <circle cx="12" cy="3.4" r="1"></circle>
                                                <path d="M10 20.5v-3a2 2 0 0 1 4 0v3"></path>
                                            </svg></span>
                                        <span class="[flex:1_1_180px] min-w-0"><span
                                                class="block text-[17px] font-bold text-[#17231E]">{{ $routine->title }}</span>
                                            @if ($routine->schedule)
                                                <span class="block mt-[2px] text-[14px] text-[#4A5A53]">{{ $routine->schedule }}</span>
                                            @endif
                                        </span>
                                        @if ($routine->badge)
                                            <span
                                                class="[padding:6px_14px] rounded-[999px] bg-[#FFFFFF] [border:1px_solid_#E6E2D6] text-[14px] font-semibold text-[#2F4038]">{{ $routine->badge }}</span>
                                        @endif
                                    </li>
                                @endforeach
                                @foreach ($kitabGroups as $sessions)
                                    @php $kitab = $sessions->first(); @endphp
                                    <li
                                        class="flex flex-wrap items-center [gap:12px_16px] [padding:18px_0] [border-bottom:1px_solid_#E6E2D6] last:border-b-0">
                                        <span
                                            class="w-[48px] h-[48px] rounded-[14px] bg-[#FFFFFF] text-[#0B3B2C] flex items-center justify-center shrink-0"><svg
                                                width="22" height="22" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                                stroke-linejoin="round" aria-hidden="true">
                                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                            </svg></span>
                                        <span class="[flex:1_1_180px] min-w-0"><span
                                                class="block text-[17px] font-bold text-[#17231E]">{{ $kitab->title }}</span><span
                                                class="block mt-[2px] text-[14px] text-[#4A5A53]">{{ $kitab->speaker }}</span></span>
                                        <span class="flex flex-wrap [gap:8px]">
                                            @foreach ($sessions as $session)
                                                <span
                                                    class="[padding:6px_14px] rounded-[999px] bg-[#FFFFFF] [border:1px_solid_#E6E2D6] text-[14px] font-semibold text-[#2F4038]">{{ $session->day->label() }}
                                                    · {{ $session->time }}</span>
                                            @endforeach
                                        </span>
                                    </li>
                                @endforeach
                                @if ($routines->isEmpty() && $kitabGroups->isEmpty())
                                    <li class="[padding:18px_0] text-[15px] text-[#4A5A53]">Belum ada kegiatan rutin.</li>
                                @endif
                            </ul>
                        </div>
                        @php
                            $nextAgenda = $upcomingAgendas->first();
                            $laterAgenda = $upcomingAgendas->get(1);
                            $nextAgendaDate = $nextAgenda?->date->locale('id');
                        @endphp
                        <article
                            class="[flex:1_1_360px] min-w-0 flex flex-col [padding:28px] rounded-[24px] bg-[#0B3B2C] text-[#FFFFFF]">
                            <h3 class="[margin:0] text-[13px] font-bold tracking-[0.12em] text-[#E2B84B]">
                                AGENDA TERDEKAT</h3>
                            @if ($nextAgenda)
                                <div class="mt-[20px] flex [gap:18px] items-start">
                                    <p
                                        class="[margin:0] shrink-0 w-[84px] [padding:12px_0] rounded-[16px] bg-[#E2B84B] text-[#0B3B2C] text-center leading-[1.1]">
                                        <span
                                            class="block text-[36px] font-extrabold">{{ $nextAgendaDate->day }}</span><span
                                            class="block mt-[4px] text-[13px] font-extrabold tracking-[0.12em]">{{ strtoupper($nextAgendaDate->translatedFormat('M')) }}</span>
                                    </p>
                                    <div class="min-w-0">
                                        <p class="[margin:0] text-[24px] font-extrabold leading-[1.25]">{{ $nextAgenda->title }}</p>
                                        <ul
                                            class="list-none [margin:12px_0_0] [padding:0] flex flex-col [gap:8px] text-[15px] text-[#D5E2DC]">
                                            @if ($nextAgenda->speaker)
                                                <li class="flex items-center [gap:10px]"><span
                                                        class="text-[#E2B84B] flex"><svg width="18" height="18"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="1.8" stroke-linecap="round"
                                                            stroke-linejoin="round" aria-hidden="true">
                                                            <circle cx="12" cy="8" r="4"></circle>
                                                            <path d="M20 21a8 8 0 0 0-16 0"></path>
                                                        </svg></span>{{ $nextAgenda->speaker }}</li>
                                            @endif
                                            <li class="flex items-center [gap:10px]"><span
                                                    class="text-[#E2B84B] flex"><svg width="18" height="18"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="1.8" stroke-linecap="round"
                                                        stroke-linejoin="round" aria-hidden="true">
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                        <path d="M12 6v6l4 2"></path>
                                                    </svg></span>{{ $nextAgendaDate->translatedFormat('l, j F Y') }}{{ $nextAgenda->time ? ' · '.$nextAgenda->time : '' }}</li>
                                            @if ($nextAgenda->location)
                                                <li class="flex items-center [gap:10px]"><span
                                                        class="text-[#E2B84B] flex"><svg width="18" height="18"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="1.8" stroke-linecap="round"
                                                            stroke-linejoin="round" aria-hidden="true">
                                                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                                            <circle cx="12" cy="10" r="3"></circle>
                                                        </svg></span>{{ $nextAgenda->location }}</li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            @else
                                <p class="[margin:20px_0_0] text-[24px] font-extrabold leading-[1.25]">Belum ada
                                    agenda terjadwal</p>
                                <p class="[margin:8px_0_0] text-[15px] text-[#D5E2DC]">Agenda berikutnya akan diumumkan
                                    oleh takmir.</p>
                            @endif
                            <div class="mt-[24px] mb-[24px] flex flex-wrap [gap:10px]">
                                <a href="{{ route('jadwal') }}"
                                    class="[flex:1_1_140px] inline-flex items-center justify-center [gap:8px] min-h-[48px] [padding:0_18px] rounded-[12px] bg-[#E2B84B] text-[#0B3B2C] text-[15px] font-bold no-underline">Detail
                                    agenda</a>
                                <a href="#jadwal"
                                    class="[flex:1_1_160px] inline-flex items-center justify-center [gap:8px] min-h-[48px] [padding:0_18px] rounded-[12px] [border:1.5px_solid_rgba(255,255,255,0.45)] text-[#FFFFFF] text-[15px] font-bold no-underline"><svg
                                        width="18" height="18" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                        stroke-linejoin="round" aria-hidden="true">
                                        <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                                        <path d="M16 2v4M8 2v4M3 10h18"></path>
                                    </svg>Simpan ke kalender</a>
                            </div>
                            @if ($nextAgenda)
                                <p
                                    class="[margin:auto_0_0] pt-[20px] [border-top:1px_solid_rgba(255,255,255,0.14)] text-[14px] text-[#B9CCC3]">
                                    @if ($laterAgenda)
                                        Berikutnya: {{ $laterAgenda->date->locale('id')->translatedFormat('j F Y') }} ·
                                        {{ $laterAgenda->title }}{{ $laterAgenda->speaker ? ' — '.$laterAgenda->speaker : '' }}
                                    @else
                                        Belum ada agenda lain yang terjadwal.
                                    @endif
                                </p>
                            @endif
                        </article>
                    </div>
                </div>
            </section>

            <section id="tpq" class="[padding:96px_0]">
                <div class="max-w-[1440px] mx-auto px-[clamp(20px,4.5vw,64px)]">
                    <div class="flex flex-wrap overflow-hidden rounded-[28px] bg-[#FFFFFF] [border:1px_solid_#E6E2D6]">
                        <div class="[flex:1.2_1_480px] min-w-0 [padding:clamp(28px,4vw,52px)]">
                            <p
                                class="[margin:0] inline-flex [padding:7px_14px] rounded-[999px] bg-[#F6EDD3] text-[#6B520E] text-[12px] font-extrabold tracking-[0.1em]">
                                PROGRAM UNGGULAN</p>
                            <h2
                                class="[margin:16px_0_0] text-[clamp(30px,3.4vw,44px)] leading-[1.15] font-extrabold tracking-[-0.02em] text-[#0B3B2C]">
                                Program TPQ</h2>
                            <p class="[margin:14px_0_0] max-w-[560px] text-[17px] leading-[1.75] text-[#42524B]">
                                TPQ Nurul Haqq membentuk generasi berkarakter Qur'ani melalui pembelajaran Al-Qur'an
                                metode Tartil, dari jenjang Tartil 1–6 hingga Marhalah Ula, Wustha, Akhir, dan Diniyah.
                            </p>
                            <ul
                                class="list-none [margin:24px_0_0] [padding:0] grid [grid-template-columns:repeat(auto-fit,minmax(220px,1fr))] [gap:12px_20px]">
                                <li class="flex items-center [gap:12px] text-[16px] font-semibold text-[#17231E]">
                                    <span
                                        class="w-[28px] h-[28px] rounded-[999px] bg-[#EEF5F1] text-[#0B3B2C] flex items-center justify-center shrink-0"><svg
                                            width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.6" stroke-linecap="round"
                                            stroke-linejoin="round" aria-hidden="true">
                                            <path d="M20 6 9 17l-5-5"></path>
                                        </svg></span>Membaca Al-Qur'an dengan tartil
                                </li>
                                <li class="flex items-center [gap:12px] text-[16px] font-semibold text-[#17231E]">
                                    <span
                                        class="w-[28px] h-[28px] rounded-[999px] bg-[#EEF5F1] text-[#0B3B2C] flex items-center justify-center shrink-0"><svg
                                            width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.6" stroke-linecap="round"
                                            stroke-linejoin="round" aria-hidden="true">
                                            <path d="M20 6 9 17l-5-5"></path>
                                        </svg></span>Menulis Al-Qur'an dengan benar
                                </li>
                                <li class="flex items-center [gap:12px] text-[16px] font-semibold text-[#17231E]">
                                    <span
                                        class="w-[28px] h-[28px] rounded-[999px] bg-[#EEF5F1] text-[#0B3B2C] flex items-center justify-center shrink-0"><svg
                                            width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.6" stroke-linecap="round"
                                            stroke-linejoin="round" aria-hidden="true">
                                            <path d="M20 6 9 17l-5-5"></path>
                                        </svg></span>Munaqosah setiap enam bulan
                                </li>
                                <li class="flex items-center [gap:12px] text-[16px] font-semibold text-[#17231E]">
                                    <span
                                        class="w-[28px] h-[28px] rounded-[999px] bg-[#EEF5F1] text-[#0B3B2C] flex items-center justify-center shrink-0"><svg
                                            width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.6" stroke-linecap="round"
                                            stroke-linejoin="round" aria-hidden="true">
                                            <path d="M20 6 9 17l-5-5"></path>
                                        </svg></span>Ekstrakurikuler jujitsu, banjari &amp; drum band
                                </li>
                            </ul>
                            <div
                                class="mt-[28px] grid [grid-template-columns:repeat(auto-fit,minmax(150px,1fr))] [gap:12px]">
                                <div class="[padding:14px_16px] rounded-[14px] bg-[#F8F6EF]">
                                    <p class="[margin:0] text-[12px] font-bold tracking-[0.1em] text-[#4A5A53]">
                                        USIA</p>
                                    <p class="[margin:4px_0_0] text-[15px] font-bold text-[#17231E]">[rentang
                                        usia]</p>
                                </div>
                                <div class="[padding:14px_16px] rounded-[14px] bg-[#F8F6EF]">
                                    <p class="[margin:0] text-[12px] font-bold tracking-[0.1em] text-[#4A5A53]">
                                        JADWAL</p>
                                    <p class="[margin:4px_0_0] text-[15px] font-bold text-[#17231E]">Senin–Jumat,
                                        15.30–17.00 WIB</p>
                                </div>
                                <div class="[padding:14px_16px] rounded-[14px] bg-[#F8F6EF]">
                                    <p class="[margin:0] text-[12px] font-bold tracking-[0.1em] text-[#4A5A53]">
                                        BIAYA</p>
                                    <p class="[margin:4px_0_0] text-[15px] font-bold text-[#17231E]">[biaya per
                                        bulan]</p>
                                </div>
                            </div>
                            <div class="mt-[28px] flex flex-wrap [gap:12px]">
                                <a href="{{ route('tpq') }}"
                                    class="inline-flex items-center [gap:10px] min-h-[52px] [padding:0_24px] rounded-[14px] bg-[#0B3B2C] text-[#FFFFFF] text-[16px] font-bold no-underline"><svg
                                        width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                        stroke-linejoin="round" aria-hidden="true">
                                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                                        <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                                    </svg>Daftar TPQ</a>
                                <a href="https://wa.me/6281239567390"
                                    class="inline-flex items-center [gap:10px] min-h-[52px] [padding:0_24px] rounded-[14px] [border:1.5px_solid_#0B3B2C] text-[#0B3B2C] text-[16px] font-bold no-underline"><svg
                                        width="18" height="18" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                        stroke-linejoin="round" aria-hidden="true">
                                        <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
                                    </svg>Tanya via WhatsApp</a>
                            </div>
                        </div>
                        <figure
                            class="tpq-media max-[640px]:min-h-0 max-[640px]:aspect-[3025/2363] relative [flex:1_1_400px] min-h-[440px] [margin:0] bg-[#142A25]">
                            <img src="{{ asset('images/beranda/tpq-papan-visi-misi-1200.jpg') }}"
                                srcset="{{ asset('images/beranda/tpq-papan-visi-misi-720.jpg') }} 720w, {{ asset('images/beranda/tpq-papan-visi-misi-1200.jpg') }} 1200w"
                                sizes="(min-width: 1100px) 600px, calc(100vw - 40px)" width="1200" height="937"
                                alt="Papan nama serta visi dan misi Taman Pendidikan Al-Qur'an (TPQ) Nurul Haqq"
                                loading="lazy" decoding="async"
                                class="absolute inset-0 block w-full h-[100%] object-contain">
                        </figure>
                    </div>
                </div>
            </section>

            <section id="ziswaf" class="relative overflow-hidden [padding:96px_0] bg-[#0B3B2C] text-[#FFFFFF]">
                <svg aria-hidden="true" viewBox="0 0 200 200" fill="none" stroke="#E2B84B" stroke-width="1.2"
                    class="absolute top-[-140px] right-[-140px] w-[540px] h-[540px] opacity-[0.12]">
                    <rect x="45" y="45" width="110" height="110"></rect>
                    <rect x="45" y="45" width="110" height="110" transform="rotate(45 100 100)"></rect>
                    <rect x="70" y="70" width="60" height="60"></rect>
                    <rect x="70" y="70" width="60" height="60" transform="rotate(45 100 100)"></rect>
                    <circle cx="100" cy="100" r="16"></circle>
                    <circle cx="100" cy="100" r="92"></circle>
                </svg>
                <div class="relative max-w-[1440px] mx-auto px-[clamp(20px,4.5vw,64px)]">
                    <p
                        class="[margin:0] flex items-center [gap:10px] text-[12px] font-bold tracking-[0.14em] text-[#E2B84B]">
                        <span class="w-[24px] h-[2px] bg-[#E2B84B]"></span>ZISWAF
                    </p>
                    <h2
                        class="[margin:14px_0_0] max-w-[760px] text-[clamp(30px,3.4vw,44px)] leading-[1.15] font-extrabold tracking-[-0.02em] text-[#FFFFFF]">
                        Salurkan zakat, infaq, sedekah, dan wakaf</h2>
                    <p class="[margin:16px_0_0] max-w-[640px] text-[17px] leading-[1.7] text-[#C9D8D1]">Zakat,
                        infaq, dan shodaqoh dihimpun dan disalurkan oleh Lembaga Amil Zakat, Infaq, dan Shadaqoh NU
                        (LAZISNU) Masjid Nurul Haqq Tawangsari melalui seksi pengumpulan, pengemasan, dan pembagian.</p>
                    <div class="mt-[44px] flex flex-wrap [gap:32px] items-start">
                        <div class="[flex:1.15_1_520px] min-w-0">
                            <h3 class="[margin:0] flex items-center [gap:12px] text-[17px] font-bold">
                                <span
                                    class="w-[28px] h-[28px] rounded-[999px] bg-[#E2B84B] text-[#0B3B2C] flex items-center justify-center text-[14px] font-extrabold">1</span>Pilih
                                jenis dana
                            </h3>
                            <div
                                class="mt-[16px] grid [grid-template-columns:repeat(auto-fit,minmax(118px,1fr))] [gap:12px]">
                                <button type="button" aria-pressed="true"
                                    class="flex flex-col items-start [gap:10px] [padding:18px] rounded-[16px] [border:1.5px_solid_#E2B84B] bg-[#E2B84B] text-[#0B3B2C] [font-family:inherit] text-left cursor-pointer"><svg
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                        stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="8" cy="8" r="6"></circle>
                                        <path d="M18.09 10.37A6 6 0 1 1 10.34 18"></path>
                                        <path d="M7 6h1v4"></path>
                                        <path d="m16.71 13.88.7.71-2.82 2.82"></path>
                                    </svg><span class="text-[17px] font-extrabold">Zakat</span><span
                                        class="text-[13px] leading-[1.4] font-semibold">Mal &amp;
                                        fitrah</span></button>
                                <button type="button" aria-pressed="false"
                                    class="flex flex-col items-start [gap:10px] [padding:18px] rounded-[16px] [border:1.5px_solid_rgba(255,255,255,0.18)] bg-[rgba(255,255,255,0.06)] text-[#FFFFFF] [font-family:inherit] text-left cursor-pointer"><span
                                        class="text-[#E2B84B] flex"><svg width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                                            aria-hidden="true">
                                            <path d="M11 14h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 16"></path>
                                            <path
                                                d="m7 20 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9">
                                            </path>
                                            <path d="m2 15 6 6"></path>
                                            <path
                                                d="M19.5 8.5c.7-.7 1.5-1.6 1.5-2.7A2.73 2.73 0 0 0 16 4a2.78 2.78 0 0 0-5 1.8c0 1.2.8 2 1.5 2.8L16 12Z">
                                            </path>
                                        </svg></span><span class="text-[17px] font-extrabold">Infaq</span><span
                                        class="text-[13px] leading-[1.4] font-semibold text-[#B9CCC3]">Kas &amp;
                                        operasional</span></button>
                                <button type="button" aria-pressed="false"
                                    class="flex flex-col items-start [gap:10px] [padding:18px] rounded-[16px] [border:1.5px_solid_rgba(255,255,255,0.18)] bg-[rgba(255,255,255,0.06)] text-[#FFFFFF] [font-family:inherit] text-left cursor-pointer"><span
                                        class="text-[#E2B84B] flex"><svg width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                                            aria-hidden="true">
                                            <path
                                                d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                            </path>
                                        </svg></span><span class="text-[17px] font-extrabold">Sedekah</span><span
                                        class="text-[13px] leading-[1.4] font-semibold text-[#B9CCC3]">Kepedulian
                                        sosial</span></button>
                                <button type="button" aria-pressed="false"
                                    class="flex flex-col items-start [gap:10px] [padding:18px] rounded-[16px] [border:1.5px_solid_rgba(255,255,255,0.18)] bg-[rgba(255,255,255,0.06)] text-[#FFFFFF] [font-family:inherit] text-left cursor-pointer"><span
                                        class="text-[#E2B84B] flex"><svg width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                                            aria-hidden="true">
                                            <path d="M3 22h18"></path>
                                            <path d="M6 18v-7"></path>
                                            <path d="M10 18v-7"></path>
                                            <path d="M14 18v-7"></path>
                                            <path d="M18 18v-7"></path>
                                            <path d="M12 2l8 5H4z"></path>
                                        </svg></span><span class="text-[17px] font-extrabold">Wakaf</span><span
                                        class="text-[13px] leading-[1.4] font-semibold text-[#B9CCC3]">Aset
                                        produktif umat</span></button>
                            </div>

                            <h3 class="[margin:32px_0_0] flex items-center [gap:12px] text-[17px] font-bold">
                                <span
                                    class="w-[28px] h-[28px] rounded-[999px] bg-[#E2B84B] text-[#0B3B2C] flex items-center justify-center text-[14px] font-extrabold">2</span>Transfer
                                atau scan QRIS
                            </h3>
                            <div
                                class="mt-[16px] flex flex-wrap items-center [gap:20px] [padding:20px] rounded-[20px] bg-[#FFFFFF] text-[#17231E]">
                                <figure
                                    class="[margin:0] w-[132px] h-[132px] shrink-0 flex flex-col items-center justify-center [gap:6px] [border:1.5px_dashed_#9FB3AA] rounded-[14px] bg-[#F4F7F5] text-[#2F5C4A]">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                                        stroke-linejoin="round" aria-hidden="true">
                                        <rect x="3" y="3" width="5" height="5" rx="1"></rect>
                                        <rect x="16" y="3" width="5" height="5" rx="1"></rect>
                                        <rect x="3" y="16" width="5" height="5" rx="1"></rect>
                                        <path d="M21 16h-3a2 2 0 0 0-2 2v3"></path>
                                        <path d="M21 21v.01"></path>
                                        <path d="M12 7v3a2 2 0 0 1-2 2H7"></path>
                                        <path d="M3 12h.01"></path>
                                        <path d="M12 3h.01"></path>
                                        <path d="M12 16v.01"></path>
                                        <path d="M16 12h1"></path>
                                        <path d="M21 12v.01"></path>
                                        <path d="M12 21v-1"></path>
                                    </svg>
                                    <figcaption class="text-[12px] font-bold">Kode QRIS</figcaption>
                                </figure>
                                <div class="[flex:1_1_220px] min-w-0">
                                    <p class="[margin:0] text-[12px] font-bold tracking-[0.12em] text-[#4A5A53]">
                                        TRANSFER BANK</p>
                                    <p class="[margin:6px_0_0] text-[16px] font-bold">Bank Syariah Mandiri</p>
                                    <p
                                        class="[margin:2px_0_0] text-[24px] font-extrabold tracking-[0.02em] text-[#0B3B2C]">
                                        7005342184</p>
                                    <p class="[margin:2px_0_0] text-[14px] text-[#4A5A53]">a.n. Yayasan Nurul Haqq</p>
                                    <button type="button"
                                        class="mt-[12px] inline-flex items-center [gap:8px] min-h-[44px] [padding:0_16px] rounded-[12px] [border:1.5px_solid_#0B3B2C] bg-[#FFFFFF] text-[#0B3B2C] [font-family:inherit] text-[14px] font-bold cursor-pointer"><svg
                                            width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                            stroke-linejoin="round" aria-hidden="true">
                                            <rect x="8" y="8" width="14" height="14" rx="2"></rect>
                                            <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"></path>
                                        </svg>Salin nomor</button>
                                </div>
                            </div>

                            <h3 class="[margin:32px_0_0] flex items-center [gap:12px] text-[17px] font-bold">
                                <span
                                    class="w-[28px] h-[28px] rounded-[999px] bg-[#E2B84B] text-[#0B3B2C] flex items-center justify-center text-[14px] font-extrabold">3</span>Konfirmasi
                                donasi
                            </h3>
                            <div class="mt-[16px] flex flex-wrap items-center [gap:12px_20px]">
                                <a href="https://wa.me/6281239567390"
                                    class="inline-flex items-center [gap:10px] min-h-[52px] [padding:0_24px] rounded-[14px] bg-[#E2B84B] text-[#0B3B2C] text-[16px] font-extrabold no-underline"><svg
                                        width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                        stroke-linejoin="round" aria-hidden="true">
                                        <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
                                    </svg>Konfirmasi via WhatsApp</a>
                                <p class="[margin:0] [flex:1_1_220px] text-[14px] leading-[1.6] text-[#B9CCC3]">
                                    Konfirmasi membantu pengurus mencatat dana sesuai niat Anda.</p>
                            </div>
                        </div>

                        <article
                            class="[flex:1_1_400px] min-w-0 [padding:28px] rounded-[24px] bg-[#FFFFFF] text-[#17231E]">
                            <div class="flex flex-wrap justify-between items-start [gap:12px]">
                                <div>
                                    <h3 class="[margin:0] text-[12px] font-bold tracking-[0.12em] text-[#4A5A53]">
                                        LAPORAN DANA</h3>
                                    <p class="[margin:4px_0_0] text-[16px] font-bold">Periode Mei 2026</p>
                                </div>
                                <a href="#ziswaf"
                                    class="inline-flex items-center [gap:6px] min-h-[44px] [padding:0_14px] rounded-[12px] bg-[#EEF5F1] text-[#0B3B2C] text-[14px] font-bold no-underline"><svg
                                        width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                        stroke-linejoin="round" aria-hidden="true">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <path d="M7 10l5 5 5-5"></path>
                                        <path d="M12 15V3"></path>
                                    </svg>Laporan lengkap</a>
                            </div>
                            <p class="[margin:24px_0_0] text-[14px] text-[#4A5A53]">Total terkumpul</p>
                            <p
                                class="[margin:2px_0_0] text-[clamp(32px,3vw,40px)] leading-[1.15] font-extrabold tracking-[-0.02em] text-[#0B3B2C]">
                                Rp 23.122.000</p>
                            <div class="mt-[16px] grid [grid-template-columns:repeat(2,minmax(0,1fr))] [gap:12px]">
                                <div class="[padding:12px_14px] rounded-[14px] [border:1.5px_dashed_#CFC8B4]">
                                    <p class="[margin:0] text-[13px] text-[#4A5A53]">Tersalurkan</p>
                                    <p class="[margin:2px_0_0] text-[17px] font-extrabold text-[#17231E]">[Rp —]</p>
                                </div>
                                <div class="[padding:12px_14px] rounded-[14px] [border:1.5px_dashed_#CFC8B4]">
                                    <p class="[margin:0] text-[13px] text-[#4A5A53]">Saldo</p>
                                    <p class="[margin:2px_0_0] text-[17px] font-extrabold text-[#17231E]">[Rp —]</p>
                                </div>
                            </div>
                            <h4 class="[margin:24px_0_0] text-[12px] font-bold tracking-[0.12em] text-[#4A5A53]">
                                RINCIAN PER JENIS</h4>
                            <ul class="list-none [margin:12px_0_0] [padding:0] flex flex-col [gap:14px]">
                                <li>
                                    <div class="flex justify-between [gap:12px] text-[15px]">
                                        <span class="font-bold">Zakat</span><span class="text-[#4A5A53]"><strong
                                                class="text-[#17231E]">Rp 4.875.000</strong> · 21%</span>
                                    </div>
                                    <div class="mt-[6px] h-[10px] rounded-[999px] bg-[#EDEBE3]">
                                        <div class="w-[56.1%] h-[100%] rounded-[999px] bg-[#135C43]">
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="flex justify-between [gap:12px] text-[15px]">
                                        <span class="font-bold">Infaq</span><span class="text-[#4A5A53]"><strong
                                                class="text-[#17231E]">Rp 6.342.000</strong> · 27%</span>
                                    </div>
                                    <div class="mt-[6px] h-[10px] rounded-[999px] bg-[#EDEBE3]">
                                        <div class="w-[73%] h-[100%] rounded-[999px] bg-[#135C43]">
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="flex justify-between [gap:12px] text-[15px]">
                                        <span class="font-bold">Sedekah</span><span class="text-[#4A5A53]"><strong
                                                class="text-[#17231E]">Rp 3.215.000</strong>
                                            · 14%</span>
                                    </div>
                                    <div class="mt-[6px] h-[10px] rounded-[999px] bg-[#EDEBE3]">
                                        <div class="w-[37%] h-[100%] rounded-[999px] bg-[#135C43]">
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="flex justify-between [gap:12px] text-[15px]">
                                        <span class="font-bold">Wakaf</span><span class="text-[#4A5A53]"><strong
                                                class="text-[#17231E]">Rp 8.690.000</strong> · 38%</span>
                                    </div>
                                    <div class="mt-[6px] h-[10px] rounded-[999px] bg-[#EDEBE3]">
                                        <div class="w-full h-[100%] rounded-[999px] bg-[#135C43]">
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </article>
                    </div>
                </div>
            </section>

            <section id="galeri" class="[padding:96px_0]">
                <div class="max-w-[1440px] mx-auto px-[clamp(20px,4.5vw,64px)]">
                    <div class="flex flex-wrap items-end justify-between [gap:16px_32px]">
                        <div>
                            <p
                                class="[margin:0] flex items-center [gap:10px] text-[12px] font-bold tracking-[0.14em] text-[#8A6A12]">
                                <span class="w-[24px] h-[2px] bg-[#C9971C]"></span>GALERI
                            </p>
                            <h2
                                class="[margin:14px_0_0] text-[clamp(30px,3.4vw,44px)] leading-[1.15] font-extrabold tracking-[-0.02em] text-[#0B3B2C]">
                                Momen kegiatan jamaah</h2>
                        </div>
                        <a href="#galeri"
                            class="inline-flex items-center [gap:8px] min-h-[44px] text-[15px] font-bold text-[#0B3B2C] no-underline">Lihat
                            semua galeri<svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 12h14"></path>
                                <path d="m12 5 7 7-7 7"></path>
                            </svg></a>
                    </div>
                    <div class="mt-[40px] flex flex-wrap [gap:14px]">
                        <figure
                            class="relative [flex:1_1_520px] min-w-0 h-[400px] [margin:0] overflow-hidden rounded-[24px] bg-[#E4ECE6]">
                            <img src="{{ asset('images/beranda/galeri-silaturahmi-pengurus-1600.jpg') }}"
                                srcset="{{ asset('images/beranda/galeri-silaturahmi-pengurus-960.jpg') }} 960w, {{ asset('images/beranda/galeri-silaturahmi-pengurus-1600.jpg') }} 1600w"
                                sizes="(min-width: 1100px) 640px, calc(100vw - 40px)" width="1600" height="713"
                                alt="Pengurus Yayasan Nurul Haqq dan Takmir Masjid Nurul Haqq berfoto bersama di dalam masjid"
                                loading="lazy" decoding="async" class="block w-full h-[100%] object-cover">
                            <figcaption
                                class="absolute left-[0] right-[0] bottom-[0] [padding:56px_24px_20px] [background:linear-gradient(to_top,rgba(8,45,34,0.88),rgba(8,45,34,0))] text-[#FFFFFF] text-[15px] font-bold leading-[1.4]">
                                Silaturahmi pengurus Yayasan Nurul Haqq dengan Takmir Masjid Nurul Haqq</figcaption>
                        </figure>
                        <div
                            class="[flex:1_1_520px] min-w-0 grid [grid-template-columns:repeat(2,minmax(0,1fr))] [grid-template-rows:repeat(2,193px)] [gap:14px]">
                            <figure class="relative [margin:0] overflow-hidden rounded-[20px] bg-[#E4ECE6]">
                                <img src="{{ asset('images/beranda/galeri-safari-dakwah-isra-miraj.jpg') }}"
                                    width="720" height="542"
                                    alt="Warga berfoto di depan spanduk Safari Dakwah Peringatan Isra Mi'raj 1446 H"
                                    loading="lazy" decoding="async"
                                    class="block w-full h-[100%] object-cover [object-position:50%_100%]">
                                <figcaption
                                    class="absolute left-[0] right-[0] bottom-[0] [padding:32px_14px_12px] [background:linear-gradient(to_top,rgba(8,45,34,0.88),rgba(8,45,34,0))] text-[#FFFFFF] text-[13px] font-bold leading-[1.35]">
                                    Safari Dakwah Isra Mi'raj 1446 H</figcaption>
                            </figure>
                            <figure class="relative [margin:0] overflow-hidden rounded-[20px] bg-[#E4ECE6]">
                                <img src="{{ asset('images/beranda/galeri-prasasti-peresmian.jpg') }}" width="720"
                                    height="538"
                                    alt="Prasasti Masjid Nurul Haqq: dibangun 13 Juni 2010 dan diresmikan 15 April 2018"
                                    loading="lazy" decoding="async" class="block w-full h-[100%] object-cover">
                                <figcaption
                                    class="absolute left-[0] right-[0] bottom-[0] [padding:32px_14px_12px] [background:linear-gradient(to_top,rgba(8,45,34,0.88),rgba(8,45,34,0))] text-[#FFFFFF] text-[13px] font-bold leading-[1.35]">
                                    Prasasti peresmian masjid, 2018</figcaption>
                            </figure>
                            <figure class="relative [margin:0] overflow-hidden rounded-[20px] bg-[#E4ECE6]">
                                <img src="{{ asset('images/beranda/galeri-spanduk-isra-miraj.jpg') }}" width="720"
                                    height="860"
                                    alt="Warga di depan spanduk Peringatan Isra Mi'raj dan menyambut Ramadhan 1446 H di Masjid Nurul Haqq"
                                    loading="lazy" decoding="async"
                                    class="block w-full h-[100%] object-cover [object-position:50%_45%]">
                                <figcaption
                                    class="absolute left-[0] right-[0] bottom-[0] [padding:32px_14px_12px] [background:linear-gradient(to_top,rgba(8,45,34,0.88),rgba(8,45,34,0))] text-[#FFFFFF] text-[13px] font-bold leading-[1.35]">
                                    Menyambut Ramadhan 1446 H</figcaption>
                            </figure>
                            <a href="/galeri"
                                class="rounded-[20px] bg-[#0B3B2C] text-[#FFFFFF] flex flex-col items-center justify-center [gap:2px] no-underline"><span
                                    class="text-[34px] font-extrabold leading-[1.1]">+4</span><span
                                    class="text-[14px] font-semibold text-[#C9D8D1]">foto lainnya</span></a>
                        </div>
                    </div>
                </div>
            </section>

            <section id="kontak" class="[padding:96px_0] bg-[#FFFFFF]">
                <div class="max-w-[1440px] mx-auto px-[clamp(20px,4.5vw,64px)]">
                    <div class="flex flex-wrap [gap:48px_64px]">
                        <div class="[flex:1_1_460px] min-w-0">
                            <p
                                class="[margin:0] flex items-center [gap:10px] text-[12px] font-bold tracking-[0.14em] text-[#8A6A12]">
                                <span class="w-[24px] h-[2px] bg-[#C9971C]"></span>KONTAK &amp; LOKASI
                            </p>
                            <h2
                                class="[margin:14px_0_0] text-[clamp(30px,3.4vw,44px)] leading-[1.15] font-extrabold tracking-[-0.02em] text-[#0B3B2C]">
                                Hubungi pengurus masjid</h2>
                            <p class="[margin:14px_0_0] text-[17px] leading-[1.7] text-[#42524B]">Untuk layanan
                                ZISWAF, pendaftaran TPQ, dan kebutuhan ibadah lainnya.</p>
                            <ul class="list-none [margin:28px_0_0] [padding:0] flex flex-col [gap:10px]">
                                @foreach ($phones as $phone)
                                    <li><a href="{{ $phone->is_whatsapp == true ? 'https://wa.me/' . $phone->number : 'tel:' . $phone->number }}"
                                            class="flex items-center [gap:16px] [padding:14px_18px] rounded-[16px] bg-[#F8F6EF] text-[#17231E] no-underline">
                                            @if ($phone->is_whatsapp == true)
                                                <span
                                                    class="w-[44px] h-[44px] rounded-[12px] bg-[#FFFFFF] text-[#0B3B2C] flex items-center justify-center shrink-0"><svg
                                                        width="20" height="20" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="1.8"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        aria-hidden="true">
                                                        <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
                                                    </svg></span>
                                            @else
                                                <span
                                                    class="w-[44px] h-[44px] rounded-[12px] bg-[#FFFFFF] text-[#0B3B2C] flex items-center justify-center shrink-0"><svg
                                                        width="20" height="20" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="1.8"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        aria-hidden="true">
                                                        <path
                                                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                                        </path>
                                                    </svg></span>
                                            @endif
                                            <span class="[flex:1] min-w-0"><span
                                                    class="block text-[13px] text-[#4A5A53]">{{ $phone->label }}
                                                </span><span
                                                    class="block text-[17px] font-bold">{{ $phone->number }}</span></span><span
                                                class="text-[#6E7F77] flex"><svg width="18" height="18"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    aria-hidden="true">
                                                    <path d="M5 12h14"></path>
                                                    <path d="m12 5 7 7-7 7"></path>
                                                </svg></span>
                                        </a></li>
                                @endforeach
                                <li><a href="mailto:takmirmnh22@gmail.com"
                                        class="flex items-center [gap:16px] [padding:14px_18px] rounded-[16px] bg-[#F8F6EF] text-[#17231E] no-underline"><span
                                            class="w-[44px] h-[44px] rounded-[12px] bg-[#FFFFFF] text-[#0B3B2C] flex items-center justify-center shrink-0"><svg
                                                width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                                stroke-linejoin="round" aria-hidden="true">
                                                <rect x="2" y="4" width="20" height="16" rx="2">
                                                </rect>
                                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                            </svg></span><span class="[flex:1] min-w-0"><span
                                                class="block text-[13px] text-[#4A5A53]">Email
                                                takmir</span><span
                                                class="block text-[clamp(14px,3.9vw,17px)] font-bold [overflow-wrap:anywhere]">{{ $contact?->email }}</span></span><span
                                            class="text-[#6E7F77] flex"><svg width="18" height="18"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                aria-hidden="true">
                                                <path d="M5 12h14"></path>
                                                <path d="m12 5 7 7-7 7"></path>
                                            </svg></span></a></li>
                                <li><a href="https://instagram.com/masjidnurulhaqq"
                                        class="flex items-center [gap:16px] [padding:14px_18px] rounded-[16px] bg-[#F8F6EF] text-[#17231E] no-underline"><span
                                            class="w-[44px] h-[44px] rounded-[12px] bg-[#FFFFFF] text-[#0B3B2C] flex items-center justify-center shrink-0"><svg
                                                width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                                stroke-linejoin="round" aria-hidden="true">
                                                <rect x="2" y="2" width="20" height="20" rx="5">
                                                </rect>
                                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                                <path d="M17.5 6.5h.01"></path>
                                            </svg></span><span class="[flex:1] min-w-0"><span
                                                class="block text-[13px] text-[#4A5A53]">Instagram</span><span
                                                class="block text-[17px] font-bold">{{ $contact?->instagram }}</span></span><span
                                            class="text-[#6E7F77] flex"><svg width="18" height="18"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                aria-hidden="true">
                                                <path d="M5 12h14"></path>
                                                <path d="m12 5 7 7-7 7"></path>
                                            </svg></span></a></li>
                            </ul>
                            <p
                                class="[margin:18px_0_0] flex flex-wrap items-center [gap:8px] text-[15px] text-[#4A5A53]">
                                <span class="text-[#8A6A12] flex"><svg width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path d="M12 6v6l4 2"></path>
                                    </svg></span>Layanan sekretariat: <strong
                                    class="text-[#17231E]">{{ $contact?->service_hours }}</strong>
                            </p>
                        </div>
                        <div class="[flex:1_1_460px] min-w-0">
                            <div
                                class="h-[340px] overflow-hidden rounded-[24px] bg-[#E9EEE8] [border:1px_solid_#E6E2D6]">
                                <iframe src="{{ $contact?->maps_embed_url }}"
                                    title="Peta lokasi Masjid Nurul Haqq di Google Maps" width="600"
                                    height="340" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"
                                    allowfullscreen class="block w-full h-[100%] [border:0]"></iframe>
                            </div>
                            <div class="mt-[16px] flex flex-wrap items-center justify-between [gap:12px_20px]">
                                <p
                                    class="[margin:0] [flex:1_1_260px] flex [gap:10px] items-start text-[15px] leading-[1.6] text-[#42524B]">
                                    <span class="shrink-0 mt-[1px] text-[#8A6A12] flex"><svg width="20"
                                            height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                                            aria-hidden="true">
                                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg></span>{{ $contact?->address }}
                                </p>
                                <a href="{{ $contact?->maps_url }}" target="_blank" rel="noopener noreferrer"
                                    class="inline-flex items-center [gap:8px] min-h-[48px] [padding:0_20px] rounded-[14px] [border:1.5px_solid_#0B3B2C] text-[#0B3B2C] text-[15px] font-bold no-underline"><svg
                                        width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                        stroke-linejoin="round" aria-hidden="true">
                                        <path d="M15 3h6v6"></path>
                                        <path d="M10 14 21 3"></path>
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                    </svg>Buka di Google Maps</a>
                            </div>
                        </div>
                    </div>

                    <div
                        class="relative overflow-hidden mt-[72px] flex flex-wrap items-center justify-between [gap:24px_40px] [padding:clamp(28px,4vw,48px)] rounded-[28px] bg-[#0B3B2C] text-[#FFFFFF]">
                        <svg aria-hidden="true" viewBox="0 0 200 200" fill="none" stroke="#E2B84B"
                            stroke-width="1.4"
                            class="absolute left-[-90px] bottom-[-110px] w-[300px] h-[300px] opacity-[0.14]">
                            <rect x="45" y="45" width="110" height="110"></rect>
                            <rect x="45" y="45" width="110" height="110" transform="rotate(45 100 100)">
                            </rect>
                            <circle cx="100" cy="100" r="16"></circle>
                        </svg>
                        <div class="relative [flex:1_1_420px] min-w-0">
                            <h2
                                class="[margin:0] text-[clamp(26px,2.6vw,34px)] leading-[1.2] font-extrabold tracking-[-0.01em]">
                                Mari bersama memakmurkan masjid</h2>
                            <p class="[margin:8px_0_0] text-[17px] text-[#C9D8D1]">Untuk kebaikan dunia dan akhirat.
                            </p>
                        </div>
                        <div class="relative flex flex-wrap [gap:12px]">
                            <a href="/ziswaf"
                                class="inline-flex items-center [gap:10px] min-h-[52px] [padding:0_24px] rounded-[14px] bg-[#E2B84B] text-[#0B3B2C] text-[16px] font-extrabold no-underline"><svg
                                    width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path
                                        d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                    </path>
                                </svg>Donasi ZISWAF</a>
                            <a href="/jadwal-kegiatan"
                                class="inline-flex items-center [gap:10px] min-h-[52px] [padding:0_24px] rounded-[14px] [border:1.5px_solid_rgba(255,255,255,0.5)] text-[#FFFFFF] text-[16px] font-bold no-underline">Lihat
                                jadwal</a>
                        </div>
                    </div>
                </div>
            </section>

        </main>

        <x-site-footer />

    </div>
</body>

</html>
