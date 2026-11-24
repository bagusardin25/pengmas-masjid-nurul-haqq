<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @section('description', 'Masjid Jami Nurul Haqq: jadwal sholat, kegiatan, Program TPQ, dan layanan ZISWAF untuk jamaah Griya Taman Asri, Tawangsari, Sidoarjo.')
    @include('partials.seo-head')
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

        <div class="sticky-top-bar">
            <x-site-header />

            <x-prayer-led-bar
                :prayers="$prayers"
                :next-prayer="$nextPrayer"
                :location="$prayerSchedule['location'] ?? null"
                :date="$prayerSchedule['date'] ?? $today->locale('id')->translatedFormat('l, d/m/Y')" />
        </div>

        <main id="main-content" class="[flex:0_0_auto]">

            <section id="beranda" class="[padding:56px_0_88px] bg-[#FFFFFF]">
                <div
                    class="max-w-[1440px] mx-auto px-[clamp(20px,4.5vw,64px)] grid [grid-template-columns:repeat(auto-fit,minmax(min(100%,480px),1fr))] [gap:56px] items-center">
                    <div>
                        <p
                            style="--d: 0ms"
                            class="hero-stagger [margin:0] inline-flex items-center [gap:8px] [padding:7px_14px] rounded-[999px] bg-[#EEF5F1] text-[#0B3B2C] text-[12px] font-bold tracking-[0.1em]">
                            {{ $hero?->hero_eyebrow }}
                        </p>
                        <h1
                            style="--d: 80ms"
                            class="hero-stagger [margin:22px_0_0] text-[clamp(48px,6vw,84px)] leading-[1.02] font-extrabold tracking-[-0.025em] text-[#0B3B2C]">
                            {!! nl2br(e($hero?->hero_title)) !!}
                        </h1>
                        <p style="--d: 160ms" class="hero-stagger [margin:22px_0_0] max-w-[540px] text-[18px] leading-[1.7] text-[#42524B]">
                            {{ $hero?->hero_description }}
                        </p>
                        <p
                            style="--d: 220ms"
                            class="hero-stagger [margin:24px_0_0] max-w-[520px] flex [gap:10px] items-start text-[15px] leading-[1.6] text-[#42524B]">
                            <span class="shrink-0 mt-[1px] text-[#8A6A12]"><svg width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg></span><span>{{ $contact?->address }}</span>
                        </p>
                        <div style="--d: 280ms" class="hero-stagger mt-[32px] flex flex-wrap [gap:12px]">
                            <a href="{{ route('jadwal') }}"
                                class="home-cta w-full md:w-max inline-flex items-center [gap:10px] min-h-[52px] [padding:0_24px] rounded-[14px] bg-[#0B3B2C] text-[#FFFFFF] text-[16px] font-bold no-underline"><svg
                                    width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                                    <path d="M16 2v4M8 2v4M3 10h18"></path>
                                </svg>Jadwal Kegiatan</a>
                            <a href="{{ route('kontak') }}"
                                class="home-cta w-full md:w-max inline-flex items-center [gap:10px] min-h-[52px] [padding:0_24px] rounded-[14px] [border:1.5px_solid_#0B3B2C] text-[#0B3B2C] text-[16px] font-bold no-underline"><svg
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
                            style="--d: 120ms"
                            class="hero-media-enter [margin:0] aspect-[6/5] rounded-[320px_320px_28px_28px] overflow-hidden bg-[#E4ECE6]">
                            @if ($hero?->heroImage)
                                <img src="{{ $hero->heroImage->url() }}"
                                    @if ($hero->heroImage->width) width="{{ $hero->heroImage->width }}" height="{{ $hero->heroImage->height }}" @endif
                                    alt="{{ $hero->heroImage->alt ?: 'Foto Masjid Nurul Haqq' }}"
                                    fetchpriority="high" decoding="async" class="block w-full h-[100%] object-cover">
                            @else
                                <img src="{{ asset(\App\Models\HomePage::DEFAULT_HERO_IMAGE) }}"
                                    srcset="{{ asset('images/beranda/hero-masjid-pintu-720.jpg') }} 720w, {{ asset(\App\Models\HomePage::DEFAULT_HERO_IMAGE) }} 1200w"
                                    sizes="(min-width: 1100px) 640px, calc(100vw - 40px)" width="1200" height="1000"
                                    alt="{{ \App\Models\HomePage::DEFAULT_HERO_ALT }}"
                                    fetchpriority="high" decoding="async" class="block w-full h-[100%] object-cover">
                            @endif
                        </figure>
                        @php
                            $extraQuotes = collect([
                                [
                                    'ar' => 'وَأَنَّ الْمَسَاجِدَ لِلَّهِ فَلَا تَدْعُوا مَعَ اللَّهِ أَحَدًا',
                                    'id' => '“Dan sesungguhnya masjid-masjid itu adalah milik Allah, maka janganlah kamu menyembah seorang pun di dalamnya selain Allah.”',
                                    'cite' => 'QS. AL-JINN: 18',
                                ],
                                [
                                    'ar' => 'فِي بُيُوتٍ أَذِنَ اللَّهُ أَنْ تُرْفَعَ وَيُذْكَرَ فِيهَا اسْمُهُ',
                                    'id' => '“Di rumah-rumah ibadah (masjid) yang Allah perintahkan untuk dimuliakan dan disebut nama-Nya di dalamnya…”',
                                    'cite' => 'QS. AN-NUR: 36',
                                ],
                                [
                                    'ar' => 'أَلَا بِذِكْرِ اللَّهِ تَطْمَئِنُّ الْقُلُوبُ',
                                    'id' => '“Ingatlah, hanya dengan mengingat Allah hati menjadi tenteram.”',
                                    'cite' => "QS. AR-RA'D: 28",
                                ],
                                [
                                    'ar' => 'مَثَلُ الَّذِينَ يُنْفِقُونَ أَمْوَالَهُمْ فِي سَبِيلِ اللَّهِ كَمَثَلِ حَبَّةٍ أَنْبَتَتْ سَبْعَ سَنَابِلَ',
                                    'id' => '“Perumpamaan orang yang menafkahkan hartanya di jalan Allah seperti sebutir biji yang menumbuhkan tujuh tangkai…”',
                                    'cite' => 'QS. AL-BAQARAH: 261',
                                ],
                            ])->reject(fn ($q) => strcasecmp(trim((string) $hero?->hero_citation), $q['cite']) === 0)->values()->all();
                        @endphp
                        <blockquote
                            style="--d: 320ms"
                            data-quote-carousel
                            data-quote-slides='@json($extraQuotes)'
                            class="hero-quote hero-quote-enter max-[640px]:[position:relative] max-[640px]:left-auto max-[640px]:bottom-auto max-[640px]:w-auto max-[640px]:[margin:-64px_16px_0] absolute left-[24px] bottom-[0] [margin:0] w-[min(430px,calc(100% - 48px))] [padding:22px_24px] rounded-[20px] bg-[#0B3B2C] text-[#FFFFFF] shadow-[0_20px_44px_rgba(11,59,44,0.28)]">
                            <p lang="ar" dir="rtl" data-quote-words="ar"
                                class="[margin:0] font-[Amiri,serif] text-[22px] leading-[1.8] text-[#E2B84B]">
                                {{ $hero?->hero_arabic_text }}
                            </p>
                            <p data-quote-words="id" class="[margin:8px_0_0] text-[14px] leading-[1.6] text-[#E3ECE7]">
                                {{ $hero?->hero_translation }}
                            </p>
                            <cite
                                data-quote-cite
                                class="block mt-[10px] not-italic text-[12px] font-bold tracking-[0.12em] text-[#E2B84B]">
                                {{ $hero?->hero_citation }}
                            </cite>
                        </blockquote>
                    </div>
                </div>
            </section>

            <section id="profil" class="[padding:88px_0_96px]">
                <div class="max-w-[1440px] mx-auto px-[clamp(20px,4.5vw,64px)]">
                    <div
                        class="grid [grid-template-columns:repeat(auto-fit,minmax(min(100%,460px),1fr))] [gap:48px_64px] items-start">
                        <div data-reveal>
                            <p
                                class="[margin:0] text-[12px] font-bold tracking-[0.14em] text-[#8A6A12]">
                                PROFIL MASJID
                            </p>
                            <h2
                                class="[margin:14px_0_0] text-[clamp(30px,3.4vw,44px)] leading-[1.15] font-extrabold tracking-[-0.02em] text-[#0B3B2C]">
                                {{ $about?->hero_title }}</h2>
                            @php
                                $homeHistoryExcerpt = collect(preg_split('/\R{2,}/', trim((string) $about?->history_content)))
                                    ->filter()
                                    ->take(2)
                                    ->implode("\n\n");
                            @endphp
                            <p class="[margin:20px_0_0] text-[17px] leading-[1.75] text-[#42524B]">
                                {!! nl2br(e($homeHistoryExcerpt)) !!}</p>
                            <a href="/profil"
                                class="link-arrow mt-[20px] inline-flex items-center [gap:8px] min-h-[44px] text-[15px] font-bold text-[#0B3B2C] no-underline">Baca
                                profil lengkap<svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M5 12h14"></path>
                                    <path d="m12 5 7 7-7 7"></path>
                                </svg></a>
                        </div>
                        @if ($aboutStats->isNotEmpty())
                            <div data-reveal-group class="grid [grid-template-columns:repeat(2,minmax(0,1fr))] [gap:16px]">
                                @foreach ($aboutStats as $stat)
                                    <div data-reveal class="surface-card flex flex-col items-center justify-center text-center min-h-[126px] [padding:24px] rounded-[20px] bg-[#FFFFFF]">
                                        <p
                                            class="tabular-nums [margin:0] text-[30px] font-extrabold leading-[1.1] tracking-[-0.01em] text-[#0B3B2C]">
                                            {{ $stat->value }}</p>
                                        <p class="[margin:6px_0_0] text-[14px] text-[#4A5A53]">{{ $stat->label }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="mt-[64px]">
                        <div data-reveal class="flex flex-wrap items-baseline justify-between [gap:8px_24px]">
                            <h3 class="[margin:0] text-[22px] font-extrabold text-[#0B3B2C]">Fasilitas</h3>
                            <p class="[margin:0] text-[15px] text-[#4A5A53]">Sarana yang tersedia untuk kenyamanan
                                ibadah jamaah</p>
                        </div>
                        <ul
                            data-reveal-group
                            class="list-none [margin:20px_0_0] [padding:0] grid [grid-template-columns:repeat(auto-fit,minmax(180px,1fr))] [gap:12px]">
                            @foreach ($facilities as $facility)
                                <li
                                    data-reveal
                                    class="surface-card flex items-center [gap:14px] [padding:16px_18px] rounded-[16px] bg-[#FFFFFF] text-[15px] font-bold text-[#17231E]">
                                    <span class="facility-mark"><x-facility-icon :name="$facility->name" /></span>
                                    {{ $facility->name }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </section>

            <section id="jadwal" class="[padding:96px_0] bg-[#FFFFFF]">
                <div class="max-w-[1440px] mx-auto px-[clamp(20px,4.5vw,64px)]">
                    <div data-reveal class="flex flex-wrap items-end justify-between [gap:16px_32px]">
                        <div>
                            <p
                                class="[margin:0] text-[12px] font-bold tracking-[0.14em] text-[#8A6A12]">
                                JADWAL &amp; KEGIATAN
                            </p>
                            <h2
                                class="[margin:14px_0_0] text-[clamp(30px,3.4vw,44px)] leading-[1.15] font-extrabold tracking-[-0.02em] text-[#0B3B2C]">
                                Ibadah dan kajian setiap pekan</h2>
                        </div>
                        <a href="{{ route('jadwal') }}"
                            class="link-arrow inline-flex items-center [gap:8px] min-h-[44px] text-[15px] font-bold text-[#0B3B2C] no-underline">Lihat
                            semua kegiatan<svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 12h14"></path>
                                <path d="m12 5 7 7-7 7"></path>
                            </svg></a>
                    </div>
                    <div class="mt-[40px] flex flex-wrap [gap:24px] items-stretch">
                        <div data-reveal class="[flex:1.45_1_520px] min-w-0 [padding:28px] rounded-[24px] bg-[#F8F6EF] flex flex-col [gap:24px]">
                            @if ($routines->isNotEmpty())
                                <div>
                                    <h3 class="[margin:0] text-[12px] font-bold tracking-[0.12em] text-[#8A6A12]">
                                        IBADAH RUTIN</h3>
                                    <ul data-reveal-group class="list-none [margin:12px_0_0] [padding:0] grid [grid-template-columns:repeat(auto-fit,minmax(220px,1fr))] [gap:12px]">
                                        @foreach ($routines as $routine)
                                            <li
                                                data-reveal
                                                class="surface-card [padding:16px_18px] rounded-[14px] bg-[#FFFFFF] flex items-center justify-between [gap:12px]">
                                                <div class="min-w-0">
                                                    <span class="block text-[15px] font-bold text-[#17231E] leading-[1.35]">{{ $routine->title }}</span>
                                                    @if ($routine->schedule)
                                                        <span class="block mt-[2px] text-[13px] text-[#4A5A53]">{{ $routine->schedule }}</span>
                                                    @endif
                                                </div>
                                                @if ($routine->badge)
                                                    <span class="shrink-0 text-[12px] font-bold text-[#0B3B2C] text-right">{{ $routine->badge }}</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div>
                                <h3 class="[margin:0] text-[12px] font-bold tracking-[0.12em] text-[#8A6A12]">
                                    KAJIAN &amp; NGAJI KITAB</h3>
                                @if ($kitabGroups->isNotEmpty())
                                    <ul data-reveal-group class="list-none [margin:10px_0_0] [padding:0]">
                                        @foreach ($kitabGroups as $sessions)
                                            @php $kitab = $sessions->first(); @endphp
                                            <li
                                                data-reveal
                                                class="grid grid-cols-1 sm:grid-cols-[minmax(0,1fr)_264px] items-center [gap:10px_20px] [padding:13px_0] [border-bottom:1px_solid_#E6E2D6] last:border-b-0">
                                                <div class="min-w-0">
                                                    <span class="block text-[15px] font-bold text-[#17231E] leading-[1.35]">{{ $kitab->title }}</span>
                                                    <span class="block mt-[2px] text-[13px] text-[#4A5A53]">{{ $kitab->speaker }}</span>
                                                </div>
                                                <div class="flex flex-col [gap:5px] w-full">
                                                    @foreach ($sessions as $session)
                                                        <span
                                                            class="tabular-nums block w-full text-left sm:text-right [padding:6px_12px] rounded-[8px] bg-[#FFFFFF] [border:1px_solid_#E6E2D6] text-[12px] font-semibold text-[#0B3B2C] whitespace-nowrap">{{ $session->day->label() }}
                                                            · {{ $session->time }}</span>
                                                    @endforeach
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @elseif ($routines->isEmpty())
                                    <p class="[margin:12px_0_0] text-[15px] text-[#4A5A53]">Belum ada kegiatan rutin.</p>
                                @endif
                            </div>
                        </div>
                        @php
                            $nextAgenda = $upcomingAgendas->first();
                            $laterAgenda = $upcomingAgendas->get(1);
                            $moreAgendas = $upcomingAgendas->slice(2);
                            $nextAgendaDate = $nextAgenda?->date->locale('id');
                        @endphp
                        <article
                            data-reveal
                            class="relative overflow-hidden [flex:1_1_360px] min-w-0 flex flex-col justify-between [padding:28px] rounded-[24px] bg-[#0B3B2C] text-[#FFFFFF]">
                            <svg aria-hidden="true" viewBox="0 0 200 200" fill="none" stroke="#E2B84B" stroke-width="1.2"
                                class="pointer-events-none absolute top-[-90px] right-[-90px] w-[240px] h-[240px] opacity-[0.07]">
                                <rect x="45" y="45" width="110" height="110"></rect>
                                <rect x="45" y="45" width="110" height="110" transform="rotate(45 100 100)"></rect>
                                <circle cx="100" cy="100" r="88"></circle>
                            </svg>
                            <div class="relative">
                                <h3 class="[margin:0] text-[12px] font-bold tracking-[0.12em] text-[#E2B84B]">
                                    AGENDA TERDEKAT</h3>
                                @if ($nextAgenda)
                                    <div class="mt-[18px] flex [gap:18px] items-start">
                                        <p
                                            class="tabular-nums [margin:0] shrink-0 w-[78px] [padding:12px_0] rounded-[16px] bg-[#E2B84B] text-[#0B3B2C] text-center leading-[1.1]">
                                            <span
                                                class="block text-[32px] font-extrabold">{{ $nextAgendaDate->day }}</span><span
                                                class="block mt-[4px] text-[12px] font-extrabold tracking-[0.12em]">{{ strtoupper($nextAgendaDate->translatedFormat('M')) }}</span>
                                        </p>
                                        <div class="min-w-0">
                                            <p class="[margin:0] text-[22px] font-extrabold leading-[1.25]">{{ $nextAgenda->title }}</p>
                                            <ul
                                                class="list-none [margin:12px_0_0] [padding:0] flex flex-col [gap:8px] text-[14.5px] text-[#D5E2DC]">
                                                @if ($nextAgenda->speaker)
                                                    <li class="flex items-center [gap:10px]"><span
                                                            class="text-[#E2B84B] flex"><svg width="17" height="17"
                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="1.8" stroke-linecap="round"
                                                                stroke-linejoin="round" aria-hidden="true">
                                                                <circle cx="12" cy="8" r="4"></circle>
                                                                <path d="M20 21a8 8 0 0 0-16 0"></path>
                                                            </svg></span>{{ $nextAgenda->speaker }}</li>
                                                @endif
                                                <li class="flex items-center [gap:10px]"><span
                                                        class="text-[#E2B84B] flex"><svg width="17" height="17"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="1.8" stroke-linecap="round"
                                                            stroke-linejoin="round" aria-hidden="true">
                                                            <circle cx="12" cy="12" r="10"></circle>
                                                            <path d="M12 6v6l4 2"></path>
                                                        </svg></span>{{ $nextAgendaDate->translatedFormat('l, j F Y') }}{{ $nextAgenda->time ? ' · '.$nextAgenda->time : '' }}</li>
                                                @if ($nextAgenda->location)
                                                    <li class="flex items-center [gap:10px]"><span
                                                            class="text-[#E2B84B] flex"><svg width="17" height="17"
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
                                    <p class="[margin:20px_0_0] text-[22px] font-extrabold leading-[1.25]">Belum ada
                                        agenda terjadwal</p>
                                    <p class="[margin:8px_0_0] text-[14.5px] text-[#D5E2DC]">Agenda berikutnya akan diumumkan
                                        oleh takmir.</p>
                                @endif
                                <div class="mt-[22px] flex flex-wrap [gap:10px]">
                                    <a href="{{ route('jadwal') }}"
                                        class="home-cta [flex:1_1_140px] inline-flex items-center justify-center [gap:8px] min-h-[46px] [padding:0_18px] rounded-[12px] bg-[#E2B84B] text-[#0B3B2C] text-[14.5px] font-bold no-underline">Detail
                                        agenda</a>
                                    @if ($nextAgenda)
                                    <a href="{{ route('agenda.calendar', $nextAgenda) }}" download
                                        class="home-cta [flex:1_1_160px] inline-flex items-center justify-center [gap:8px] min-h-[46px] [padding:0_18px] rounded-[12px] [border:1.5px_solid_rgba(255,255,255,0.45)] text-[#FFFFFF] text-[14.5px] font-bold no-underline"><svg
                                            width="17" height="17" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                            stroke-linejoin="round" aria-hidden="true">
                                            <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                                            <path d="M16 2v4M8 2v4M3 10h18"></path>
                                        </svg>Simpan ke kalender</a>
                                    @endif
                                </div>
                            </div>
                            @if ($nextAgenda)
                                <div class="relative mt-[24px] pt-[18px] [border-top:1px_solid_rgba(255,255,255,0.14)] flex flex-col [gap:10px]">
                                    @if ($laterAgenda)
                                        <div class="[padding:12px_14px] rounded-[12px] bg-[rgba(255,255,255,0.06)] text-[13.5px] leading-[1.45] text-[#D5E2DC]">
                                            <span class="block text-[11px] font-bold tracking-[0.1em] text-[#E2B84B] mb-[2px]">AGENDA BERIKUTNYA</span>
                                            Berikutnya: {{ $laterAgenda->date->locale('id')->translatedFormat('j F Y') }} ·
                                            {{ $laterAgenda->title }}{{ $laterAgenda->speaker ? ' — '.$laterAgenda->speaker : '' }}
                                        </div>
                                        @foreach ($moreAgendas as $extraAgenda)
                                            <div class="[padding:10px_14px] rounded-[12px] bg-[rgba(255,255,255,0.04)] text-[13px] leading-[1.45] text-[#B9CCC3]">
                                                {{ $extraAgenda->date->locale('id')->translatedFormat('j F Y') }} ·
                                                {{ $extraAgenda->title }}{{ $extraAgenda->speaker ? ' — '.$extraAgenda->speaker : '' }}
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="[margin:0] text-[13.5px] text-[#B9CCC3]">
                                            Belum ada agenda lain yang terjadwal.
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </article>
                    </div>
                </div>
            </section>

            <section id="tpq" class="[padding:96px_0]">
                <div class="max-w-[1440px] mx-auto px-[clamp(20px,4.5vw,64px)]">
                    <div data-reveal class="surface-card flex flex-wrap overflow-hidden rounded-[28px] bg-[#FFFFFF]">
                        <div class="[flex:1.2_1_480px] min-w-0 [padding:clamp(28px,4vw,52px)]">
                            <p
                                class="[margin:0] inline-flex [padding:7px_14px] rounded-[999px] bg-[#F6EDD3] text-[#6B520E] text-[12px] font-extrabold tracking-[0.1em]">
                                PROGRAM UNGGULAN</p>
                            <h2
                                class="[margin:16px_0_0] text-[clamp(30px,3.4vw,44px)] leading-[1.15] font-extrabold tracking-[-0.02em] text-[#0B3B2C]">
                                Program TPQ</h2>
                            @if ($tpq?->summary)
                                <p class="[margin:14px_0_0] max-w-[560px] text-[17px] leading-[1.75] text-[#42524B]">
                                    {{ $tpq->summary }}
                                </p>
                            @endif
                            @if ($tpqPillars->isNotEmpty())
                                <ul
                                    data-reveal-group
                                    class="list-none [margin:24px_0_0] [padding:0] grid [grid-template-columns:repeat(auto-fit,minmax(220px,1fr))] [gap:12px_20px]">
                                    @foreach ($tpqPillars as $pillar)
                                        <li data-reveal class="flex items-center [gap:12px] text-[16px] font-semibold text-[#17231E]">
                                            <span
                                                class="w-[28px] h-[28px] rounded-[999px] bg-[#EEF5F1] text-[#0B3B2C] flex items-center justify-center shrink-0"><svg
                                                    width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2.6" stroke-linecap="round"
                                                    stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M20 6 9 17l-5-5"></path>
                                                </svg></span>{{ $pillar->title }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            @php
                                $tpqDetails = array_filter([
                                    'USIA' => $tpq?->info_age_range,
                                    'JADWAL' => $tpq?->info_schedule,
                                    'BIAYA' => $tpq?->info_fee,
                                ]);
                            @endphp
                            @if ($tpqDetails)
                                <div
                                    data-reveal-group
                                    class="mt-[28px] grid [grid-template-columns:repeat(auto-fit,minmax(150px,1fr))] [gap:12px]">
                                    @foreach ($tpqDetails as $term => $value)
                                        <div data-reveal class="[padding:14px_16px] rounded-[14px] bg-[#F8F6EF]">
                                            <p class="[margin:0] text-[12px] font-bold tracking-[0.1em] text-[#4A5A53]">
                                                {{ $term }}</p>
                                            <p class="[margin:4px_0_0] text-[15px] font-bold text-[#17231E]">{{ $value }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <div class="mt-[28px] flex flex-wrap [gap:12px]">
                                <a href="{{ route('tpq') }}"
                                    class="home-cta inline-flex items-center [gap:10px] min-h-[52px] [padding:0_24px] rounded-[14px] bg-[#0B3B2C] text-[#FFFFFF] text-[16px] font-bold no-underline"><svg
                                        width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                        stroke-linejoin="round" aria-hidden="true">
                                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                                        <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                                    </svg>Daftar TPQ</a>
                                <a href="{{ $tpq?->whatsappUrl() ?? $siteContact->contactUrl() }}"
                                    class="home-cta inline-flex items-center [gap:10px] min-h-[52px] [padding:0_24px] rounded-[14px] [border:1.5px_solid_#0B3B2C] text-[#0B3B2C] text-[16px] font-bold no-underline"><svg
                                        width="18" height="18" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                        stroke-linejoin="round" aria-hidden="true">
                                        <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
                                    </svg>Tanya via WhatsApp</a>
                            </div>
                        </div>
                        <figure
                            class="tpq-media photo-zoom max-[640px]:min-h-0 max-[640px]:aspect-[3025/2363] relative [flex:1_1_400px] min-h-[440px] [margin:0] bg-[#142A25] overflow-hidden">
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
                    class="islamic-ornament absolute top-[-140px] right-[-140px] w-[540px] h-[540px] opacity-[0.12]">
                    <rect x="45" y="45" width="110" height="110"></rect>
                    <rect x="45" y="45" width="110" height="110" transform="rotate(45 100 100)"></rect>
                    <rect x="70" y="70" width="60" height="60"></rect>
                    <rect x="70" y="70" width="60" height="60" transform="rotate(45 100 100)"></rect>
                    <circle cx="100" cy="100" r="16"></circle>
                    <circle cx="100" cy="100" r="92"></circle>
                </svg>
                @php
                    $ziswafStep = 0;
                    $hasBankAccount = (bool) $ziswaf?->hasBankAccount();
                @endphp
                <div class="relative max-w-[1440px] mx-auto px-[clamp(20px,4.5vw,64px)]">
                    <div data-reveal class="flex flex-wrap items-end justify-between [gap:16px_32px]">
                        <div>
                            <p
                                class="[margin:0] text-[12px] font-bold tracking-[0.14em] text-[#E2B84B]">
                                ZISWAF
                            </p>
                            <h2
                                class="[margin:12px_0_0] max-w-[760px] text-[clamp(30px,3.4vw,44px)] leading-[1.15] font-extrabold tracking-[-0.02em] text-[#FFFFFF]">
                                {{ $ziswaf?->hero_title ?: 'Salurkan kebaikan untuk masjid' }}</h2>
                            @if ($ziswaf?->summary)
                                <p class="[margin:14px_0_0] max-w-[640px] text-[16.5px] leading-[1.7] text-[#C9D8D1]">
                                    {{ $ziswaf->summary }}</p>
                            @endif
                        </div>
                        <a href="{{ route('ziswaf') }}"
                            class="link-arrow inline-flex items-center [gap:8px] min-h-[44px] text-[15px] font-bold text-[#E2B84B] no-underline shrink-0">Lihat
                            program sedekah &amp; cara menyalurkan<svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 12h14"></path>
                                <path d="m12 5 7 7-7 7"></path>
                            </svg></a>
                    </div>

                    @if ($ziswafFunds->isNotEmpty())
                        <div class="mt-[36px]">
                            <h3 data-reveal class="[margin:0] flex items-center [gap:10px] text-[15px] font-bold text-[#FFFFFF]">
                                <span
                                    class="w-[26px] h-[26px] rounded-[999px] bg-[#E2B84B] text-[#0B3B2C] flex items-center justify-center text-[13px] font-extrabold">{{ ++$ziswafStep }}</span>Pilih
                                jenis dana
                            </h3>
                            <ul
                                data-reveal-group
                                class="list-none [margin:14px_0_0] [padding:0] grid grid-cols-1 md:grid-cols-3 [gap:14px]">
                                @foreach ($ziswafFunds as $fund)
                                    <li
                                        data-reveal
                                        class="flex items-start [gap:14px] [padding:20px] rounded-[18px] [border:1px_solid_rgba(255,255,255,0.16)] bg-[rgba(255,255,255,0.06)] transition-transform duration-200 hover:-translate-y-0.5">
                                        <span class="w-[40px] h-[40px] rounded-[12px] bg-[rgba(226,184,75,0.14)] text-[#E2B84B] flex items-center justify-center shrink-0"><x-stat-icon :icon="$fund->icon ?? 'heart'" :size="20" /></span>
                                        <div class="min-w-0">
                                            <span class="block text-[16px] font-extrabold text-[#FFFFFF] leading-[1.3]">{{ $fund->name }}</span>
                                            @if ($fund->description)
                                                <span class="block mt-[4px] text-[13px] leading-[1.5] text-[#B9CCC3]">{{ $fund->description }}</span>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mt-[28px] grid grid-cols-1 lg:grid-cols-12 [gap:24px] items-stretch">
                        <div class="lg:col-span-7 flex flex-col [gap:20px] min-w-0">
                            @if ($hasBankAccount)
                                <div data-reveal class="[padding:24px] rounded-[20px] bg-[#FFFFFF] text-[#17231E]">
                                    <h3 class="[margin:0_0_16px] flex items-center [gap:10px] text-[15px] font-bold text-[#0B3B2C]">
                                        <span
                                            class="w-[26px] h-[26px] rounded-[999px] bg-[#E2B84B] text-[#0B3B2C] flex items-center justify-center text-[13px] font-extrabold">{{ ++$ziswafStep }}</span>{{ $ziswaf->qrisImage ? 'Transfer atau scan QRIS' : 'Transfer ke rekening masjid' }}
                                    </h3>
                                    <div class="flex flex-wrap items-center [gap:20px_28px]">
                                        @if ($ziswaf->qrisImage)
                                            <figure class="photo-framed [margin:0] w-[124px] shrink-0">
                                                <img src="{{ $ziswaf->qrisImage->url() }}" alt="Kode QRIS {{ $ziswaf->account_holder ? 'a.n. '.$ziswaf->account_holder : 'Masjid Nurul Haqq' }}"
                                                    class="block w-full aspect-square object-contain rounded-[14px] [border:1px_solid_#E6E2D6] bg-[#FFFFFF]" loading="lazy">
                                            </figure>
                                        @endif
                                        <div class="[flex:1_1_220px] min-w-0 flex flex-wrap items-center justify-between [gap:16px]" x-data="{ copied: false }">
                                            <div>
                                                <p class="[margin:0] text-[11px] font-bold tracking-[0.12em] text-[#68766F]">
                                                    TRANSFER BANK</p>
                                                @if ($ziswaf->bank_name)
                                                    <p class="[margin:4px_0_0] text-[15px] font-bold text-[#17231E]">{{ $ziswaf->bank_name }}</p>
                                                @endif
                                                <p
                                                    class="tabular-nums [margin:2px_0_0] text-[26px] font-extrabold tracking-[0.02em] text-[#0B3B2C] leading-[1.2]">
                                                    {{ $ziswaf->account_number }}</p>
                                                @if ($ziswaf->account_holder)
                                                    <p class="[margin:2px_0_0] text-[13.5px] text-[#4A5A53]">a.n. {{ $ziswaf->account_holder }}</p>
                                                @endif
                                            </div>
                                            <button type="button" x-cloak
                                                :class="{ 'is-copied': copied }"
                                                @click="navigator.clipboard.writeText(@js($ziswaf->account_number)).then(() => { copied = true; setTimeout(() => copied = false, 2000) })"
                                                class="copy-button inline-flex items-center [gap:8px] min-h-[44px] [padding:0_16px] rounded-[12px] [border:1.5px_solid_#0B3B2C] bg-[#FFFFFF] text-[#0B3B2C] [font-family:inherit] text-[13.5px] font-bold cursor-pointer shrink-0">
                                                <span class="copy-icon-stack" :class="{ 'is-copied': copied }" aria-hidden="true">
                                                    <svg class="icon-copy" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <rect x="8" y="8" width="14" height="14" rx="2"></rect>
                                                        <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"></path>
                                                    </svg>
                                                    <svg class="icon-check" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2.4" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M20 6 9 17l-5-5"></path>
                                                    </svg>
                                                </span>
                                                <span x-text="copied ? 'Nomor disalin' : 'Salin nomor'">Salin nomor</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($ziswafContacts->isNotEmpty())
                                <div data-reveal class="[padding:20px_24px] rounded-[20px] [border:1px_solid_rgba(255,255,255,0.14)] bg-[rgba(255,255,255,0.05)]">
                                    <div class="flex flex-wrap items-baseline justify-between [gap:8px_16px]">
                                        <h3 class="[margin:0] flex items-center [gap:10px] text-[15px] font-bold text-[#FFFFFF]">
                                            <span
                                                class="w-[26px] h-[26px] rounded-[999px] bg-[#E2B84B] text-[#0B3B2C] flex items-center justify-center text-[13px] font-extrabold">{{ ++$ziswafStep }}</span>Konfirmasi
                                            donasi
                                        </h3>
                                        <p class="[margin:0] text-[13px] text-[#B9CCC3]">
                                            Membantu pengurus mencatat dana sesuai niat Anda.
                                        </p>
                                    </div>
                                    <ul data-reveal-group class="list-none [margin:14px_0_0] [padding:0] grid grid-cols-1 sm:grid-cols-2 [gap:10px]">
                                        @foreach ($ziswafContacts as $contact)
                                            <li data-reveal>
                                                <a href="{{ $contact->whatsappUrl() }}" target="_blank" rel="noopener"
                                                    aria-label="Konfirmasi lewat WhatsApp ke {{ $contact->name }}, {{ $contact->phone }}"
                                                    class="home-cta w-full inline-flex items-center [gap:10px] min-h-[48px] [padding:8px_16px] rounded-[12px] bg-[#E2B84B] text-[#0B3B2C] no-underline"><svg
                                                        width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                                        stroke-linejoin="round" aria-hidden="true" class="shrink-0">
                                                        <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
                                                    </svg><span class="flex flex-col leading-[1.25] min-w-0"><span class="text-[14px] font-extrabold truncate">{{ $contact->name }}</span><span class="tabular-nums text-[12.5px] font-semibold">{{ $contact->phone }}</span></span></a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <article
                            data-reveal
                            class="lg:col-span-5 min-w-0 flex flex-col justify-between [padding:24px] rounded-[20px] bg-[#FFFFFF] text-[#17231E]">
                            <div>
                                <div class="flex flex-wrap justify-between items-start [gap:12px]">
                                    <div>
                                        <h3 class="[margin:0] text-[11px] font-bold tracking-[0.12em] text-[#68766F]">
                                            LAPORAN KEUANGAN</h3>
                                        <p class="[margin:4px_0_0] text-[17px] font-extrabold text-[#0B3B2C]">Laporan terbaru</p>
                                    </div>
                                    <a href="{{ route('ziswaf.laporan') }}"
                                        class="home-cta link-arrow inline-flex items-center [gap:6px] min-h-[40px] [padding:0_14px] rounded-[10px] bg-[#EEF5F1] text-[#0B3B2C] text-[13px] font-bold no-underline">Laporan
                                        lengkap<svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" aria-hidden="true">
                                            <path d="M5 12h14"></path>
                                            <path d="m12 5 7 7-7 7"></path>
                                        </svg></a>
                                </div>
                                @if ($latestReports->isEmpty())
                                    <div
                                        class="[margin:20px_0_0] [padding:18px] rounded-[14px] [border:1.5px_dashed_#CFC8B4] bg-[#F8F6EF] text-[13.5px] leading-[1.6] text-[#4A5A53]">
                                        Belum ada laporan yang diterbitkan. Laporan rutin mingguan, laporan kegiatan, dan
                                        laporan pembangunan masjid akan tampil di sini setelah diunggah pengurus.
                                    </div>
                                @else
                                    <ul class="report-list mt-[18px]">
                                        @foreach ($latestReports as $report)
                                            <x-report-item :report="$report" :show-category="true" />
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                            <div class="mt-[20px] pt-[16px] [border-top:1px_solid_#E6E2D6] flex flex-wrap items-center justify-between [gap:8px_16px] text-[13px] text-[#4A5A53]">
                                <span>{{ $ziswaf?->infaq_box_description ?: 'Transparansi kas rutin, kegiatan, dan pembangunan masjid.' }}</span>
                            </div>
                        </article>
                    </div>
                </div>
            </section>

            <section id="galeri" class="[padding:96px_0]">
                <div class="max-w-[1440px] mx-auto px-[clamp(20px,4.5vw,64px)]">
                    <div data-reveal class="flex flex-wrap items-end justify-between [gap:16px_32px]">
                        <div>
                            <p
                                class="[margin:0] text-[12px] font-bold tracking-[0.14em] text-[#8A6A12]">
                                GALERI
                            </p>
                            <h2
                                class="[margin:14px_0_0] text-[clamp(30px,3.4vw,44px)] leading-[1.15] font-extrabold tracking-[-0.02em] text-[#0B3B2C]">
                                Momen kegiatan jamaah</h2>
                        </div>
                        <a href="{{ route('galeri') }}"
                            class="link-arrow inline-flex items-center [gap:8px] min-h-[44px] text-[15px] font-bold text-[#0B3B2C] no-underline">Lihat
                            semua galeri<svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 12h14"></path>
                                <path d="m12 5 7 7-7 7"></path>
                            </svg></a>
                    </div>
                    @php
                        // Album terbaru dari dashboard Galeri; foto bawaan dipakai sampai ada minimal 4 album berfoto.
                        $highlights = $galleryAlbums->count() >= 4
                            ? $galleryAlbums->map(fn ($album) => [
                                'src' => $album->cover()->url(),
                                'srcset' => null,
                                'width' => $album->cover()->width,
                                'height' => $album->cover()->height,
                                'alt' => $album->cover()->alt ?: $album->title,
                                'caption' => $album->title,
                                'href' => route('galeri.album', $album),
                                'position' => null,
                            ])->all()
                            : [
                                [
                                    'src' => asset('images/beranda/galeri-silaturahmi-pengurus-1600.jpg'),
                                    'srcset' => asset('images/beranda/galeri-silaturahmi-pengurus-960.jpg').' 960w, '.asset('images/beranda/galeri-silaturahmi-pengurus-1600.jpg').' 1600w',
                                    'width' => 1600, 'height' => 713,
                                    'alt' => 'Pengurus Yayasan Nurul Haqq dan Takmir Masjid Nurul Haqq berfoto bersama di dalam masjid',
                                    'caption' => 'Silaturahmi pengurus Yayasan Nurul Haqq dengan Takmir Masjid Nurul Haqq',
                                    'href' => null, 'position' => null,
                                ],
                                [
                                    'src' => asset('images/beranda/galeri-safari-dakwah-isra-miraj.jpg'), 'srcset' => null,
                                    'width' => 720, 'height' => 542,
                                    'alt' => "Warga berfoto di depan spanduk Safari Dakwah Peringatan Isra Mi'raj 1446 H",
                                    'caption' => "Safari Dakwah Isra Mi'raj 1446 H",
                                    'href' => null, 'position' => '50% 100%',
                                ],
                                [
                                    'src' => asset('images/beranda/galeri-prasasti-peresmian.jpg'), 'srcset' => null,
                                    'width' => 720, 'height' => 538,
                                    'alt' => 'Prasasti Masjid Nurul Haqq: dibangun 13 Juni 2010 dan diresmikan 15 April 2018',
                                    'caption' => 'Prasasti peresmian masjid, 2018',
                                    'href' => null, 'position' => null,
                                ],
                                [
                                    'src' => asset('images/beranda/galeri-spanduk-isra-miraj.jpg'), 'srcset' => null,
                                    'width' => 720, 'height' => 860,
                                    'alt' => "Warga di depan spanduk Peringatan Isra Mi'raj dan menyambut Ramadhan 1446 H di Masjid Nurul Haqq",
                                    'caption' => 'Menyambut Ramadhan 1446 H',
                                    'href' => null, 'position' => '50% 45%',
                                ],
                            ];
                        $featured = array_shift($highlights);
                        $morePhotos = max(0, $galleryPhotoTotal - 4);
                    @endphp
                    <div class="mt-[40px] flex flex-wrap [gap:14px]">
                        <figure
                            data-reveal
                            class="photo-zoom photo-framed relative [flex:1_1_520px] min-w-0 h-[400px] [margin:0] overflow-hidden rounded-[24px] bg-[#E4ECE6]">
                            <img src="{{ $featured['src'] }}"
                                @if ($featured['srcset']) srcset="{{ $featured['srcset'] }}" sizes="(min-width: 1100px) 640px, calc(100vw - 40px)" @endif
                                @if ($featured['width']) width="{{ $featured['width'] }}" height="{{ $featured['height'] }}" @endif
                                alt="{{ $featured['alt'] }}"
                                loading="lazy" decoding="async" class="block w-full h-[100%] object-cover">
                            <figcaption
                                class="absolute left-[0] right-[0] bottom-[0] [padding:56px_24px_20px] [background:linear-gradient(to_top,rgba(8,45,34,0.88),rgba(8,45,34,0))] text-[#FFFFFF] text-[15px] font-bold leading-[1.4]">
                                @if ($featured['href'])
                                    <a href="{{ $featured['href'] }}" class="text-[#FFFFFF] no-underline">{{ $featured['caption'] }}</a>
                                @else
                                    {{ $featured['caption'] }}
                                @endif
                            </figcaption>
                        </figure>
                        <div
                            data-reveal-group
                            class="[flex:1_1_520px] min-w-0 grid [grid-template-columns:repeat(2,minmax(0,1fr))] [grid-template-rows:repeat(2,193px)] [gap:14px]">
                            @foreach ($highlights as $highlight)
                                <figure data-reveal class="photo-zoom photo-framed relative [margin:0] overflow-hidden rounded-[20px] bg-[#E4ECE6]">
                                    <img src="{{ $highlight['src'] }}"
                                        @if ($highlight['width']) width="{{ $highlight['width'] }}" height="{{ $highlight['height'] }}" @endif
                                        alt="{{ $highlight['alt'] }}"
                                        loading="lazy" decoding="async"
                                        class="block w-full h-[100%] object-cover"
                                        @if ($highlight['position']) style="object-position: {{ $highlight['position'] }}" @endif>
                                    <figcaption
                                        class="absolute left-[0] right-[0] bottom-[0] [padding:32px_14px_12px] [background:linear-gradient(to_top,rgba(8,45,34,0.88),rgba(8,45,34,0))] text-[#FFFFFF] text-[13px] font-bold leading-[1.35]">
                                        @if ($highlight['href'])
                                            <a href="{{ $highlight['href'] }}" class="text-[#FFFFFF] no-underline">{{ $highlight['caption'] }}</a>
                                        @else
                                            {{ $highlight['caption'] }}
                                        @endif
                                    </figcaption>
                                </figure>
                            @endforeach
                            <a href="{{ route('galeri') }}"
                                data-reveal
                                class="home-cta tabular-nums rounded-[20px] bg-[#0B3B2C] text-[#FFFFFF] flex flex-col items-center justify-center [gap:2px] no-underline">
                                @if ($morePhotos > 0)
                                    <span class="text-[34px] font-extrabold leading-[1.1]">+{{ $morePhotos }}</span><span
                                        class="text-[14px] font-semibold text-[#C9D8D1]">foto lainnya</span>
                                @else
                                    <span class="text-[20px] font-extrabold leading-[1.1]">Lihat galeri</span><span
                                        class="text-[14px] font-semibold text-[#C9D8D1]">album kegiatan</span>
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            @php $siteSetting = $siteContact->setting(); @endphp
            <section id="kontak" class="[padding:96px_0] bg-[#FFFFFF]">
                <div class="max-w-[1440px] mx-auto px-[clamp(20px,4.5vw,64px)]">
                    <div class="flex flex-wrap [gap:48px_64px]">
                        <div data-reveal class="[flex:1_1_460px] min-w-0">
                            <p
                                class="[margin:0] text-[12px] font-bold tracking-[0.14em] text-[#8A6A12]">
                                KONTAK &amp; LOKASI
                            </p>
                            <h2
                                class="[margin:14px_0_0] text-[clamp(30px,3.4vw,44px)] leading-[1.15] font-extrabold tracking-[-0.02em] text-[#0B3B2C]">
                                Hubungi pengurus masjid</h2>
                            <p class="[margin:14px_0_0] text-[17px] leading-[1.7] text-[#42524B]">Untuk layanan
                                ZISWAF, pendaftaran TPQ, dan kebutuhan ibadah lainnya.</p>
                            <ul data-reveal-group class="list-none [margin:28px_0_0] [padding:0] flex flex-col [gap:10px]">
                                @foreach ($phones as $phone)
                                    <li data-reveal><a href="{{ $phone->url() }}"
                                            class="surface-card link-arrow flex items-center [gap:16px] [padding:14px_18px] rounded-[16px] bg-[#F8F6EF] text-[#17231E] no-underline">
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
                                @if (filled($siteSetting?->email))
                                <li data-reveal><a href="mailto:{{ $siteSetting->email }}"
                                        class="surface-card link-arrow flex items-center [gap:16px] [padding:14px_18px] rounded-[16px] bg-[#F8F6EF] text-[#17231E] no-underline"><span
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
                                                class="block text-[clamp(14px,3.9vw,17px)] font-bold [overflow-wrap:anywhere]">{{ $siteSetting?->email }}</span></span><span
                                            class="text-[#6E7F77] flex"><svg width="18" height="18"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                aria-hidden="true">
                                                <path d="M5 12h14"></path>
                                                <path d="m12 5 7 7-7 7"></path>
                                            </svg></span></a></li>
                                @endif
                                @if ($siteSetting?->instagramUrl())
                                <li data-reveal><a href="{{ $siteSetting->instagramUrl() }}" target="_blank" rel="noopener noreferrer"
                                        class="surface-card link-arrow flex items-center [gap:16px] [padding:14px_18px] rounded-[16px] bg-[#F8F6EF] text-[#17231E] no-underline"><span
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
                                                class="block text-[17px] font-bold">{{ '@'.$siteSetting->instagram }}</span></span><span
                                            class="text-[#6E7F77] flex"><svg width="18" height="18"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                aria-hidden="true">
                                                <path d="M5 12h14"></path>
                                                <path d="m12 5 7 7-7 7"></path>
                                            </svg></span></a></li>
                                @endif
                            </ul>
                            @if (filled($siteSetting?->service_hours))
                            <p
                                class="[margin:18px_0_0] flex flex-wrap items-center [gap:8px] text-[15px] text-[#4A5A53]">
                                <span class="text-[#8A6A12] flex"><svg width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path d="M12 6v6l4 2"></path>
                                    </svg></span>Layanan sekretariat: <strong
                                    class="text-[#17231E]">{{ $siteSetting->service_hours }}</strong>
                            </p>
                            @endif
                        </div>
                        <div data-reveal class="[flex:1_1_460px] min-w-0">
                            @if (filled($siteSetting?->maps_embed_url))
                            <div
                                class="h-[340px] overflow-hidden rounded-[24px] bg-[#E9EEE8] [border:1px_solid_#E6E2D6]">
                                <iframe src="{{ $siteSetting->maps_embed_url }}"
                                    title="Peta lokasi Masjid Nurul Haqq di Google Maps" width="600"
                                    height="340" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"
                                    allowfullscreen class="block w-full h-[100%] [border:0]"></iframe>
                            </div>
                            @endif
                            <div class="mt-[16px] flex flex-wrap items-center justify-between [gap:12px_20px]">
                                <p
                                    class="[margin:0] [flex:1_1_260px] flex [gap:10px] items-start text-[15px] leading-[1.6] text-[#42524B]">
                                    <span class="shrink-0 mt-[1px] text-[#8A6A12] flex"><svg width="20"
                                            height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                                            aria-hidden="true">
                                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg></span>{{ $siteSetting?->address }}
                                </p>
                                <a href="{{ $siteSetting?->mapsLink() ?? route('kontak') }}" target="_blank" rel="noopener noreferrer"
                                    class="home-cta inline-flex items-center [gap:8px] min-h-[48px] [padding:0_20px] rounded-[14px] [border:1.5px_solid_#0B3B2C] text-[#0B3B2C] text-[15px] font-bold no-underline"><svg
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
                        data-reveal
                        class="relative overflow-hidden mt-[72px] flex flex-wrap items-center justify-between [gap:24px_40px] [padding:clamp(28px,4vw,48px)] rounded-[28px] bg-[#0B3B2C] text-[#FFFFFF]">
                        <svg aria-hidden="true" viewBox="0 0 200 200" fill="none" stroke="#E2B84B"
                            stroke-width="1.4"
                            class="islamic-ornament absolute left-[-90px] bottom-[-110px] w-[300px] h-[300px] opacity-[0.14]">
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
                                class="home-cta inline-flex items-center [gap:10px] min-h-[52px] [padding:0_24px] rounded-[14px] bg-[#E2B84B] text-[#0B3B2C] text-[16px] font-extrabold no-underline"><svg
                                    width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path
                                        d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                                    </path>
                                </svg>Donasi ZISWAF</a>
                            <a href="/jadwal-kegiatan"
                                class="home-cta inline-flex items-center [gap:10px] min-h-[52px] [padding:0_24px] rounded-[14px] [border:1.5px_solid_rgba(255,255,255,0.5)] text-[#FFFFFF] text-[16px] font-bold no-underline">Lihat
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
