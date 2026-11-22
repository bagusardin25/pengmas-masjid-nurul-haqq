{{--
    Meta SEO halaman publik. Halaman bisa menimpa nilai bawaan lewat section:
    title, description, og_type (website/article), og_image (URL gambar 1200×630 bila ada).
--}}
@php
    $siteName = 'Masjid Jami Nurul Haqq';
    // Isi @section('judul', 'teks') sudah di-escape Blade; dikembalikan dulu agar tidak ter-escape dua kali (&amp;amp;).
    $plain = fn (string $section, string $default = '') => html_entity_decode(trim($__env->yieldContent($section, $default)), ENT_QUOTES | ENT_HTML5);
    $pageTitle = $plain('title', $siteName);
    $fullTitle = $pageTitle === $siteName ? $siteName.' — Griya Taman Asri, Sidoarjo' : $pageTitle.' — '.$siteName;
    $description = $plain('description', 'Masjid Jami Nurul Haqq di Griya Taman Asri, Tawangsari, Taman, Sidoarjo: jadwal sholat dan kajian, program TPQ, ZISWAF, dan laporan keuangan.');
    $ogImage = $plain('og_image') ?: asset('images/og-default.jpg');
    $canonical = url()->current();
@endphp

<title>{{ $fullTitle }}</title>
<meta name="description" content="{{ $description }}">
<meta name="robots" content="index, follow">
<link rel="canonical" href="{{ $canonical }}">

<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:locale" content="id_ID">
<meta property="og:type" content="{{ trim($__env->yieldContent('og_type', 'website')) }}">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:image" content="{{ $ogImage }}">
@unless (trim($__env->yieldContent('og_image')))
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
@endunless
<meta property="og:image:alt" content="Masjid Jami Nurul Haqq">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $ogImage }}">

@include('partials.favicons')

@if (request()->routeIs('home'))
    @php
        $setting = $siteContact->setting();
        $phone = $siteContact->phones()->first();
        $mosque = array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Mosque',
            'name' => $siteName,
            'url' => route('home'),
            'logo' => asset('images/icons/icon-512.png'),
            'image' => asset('images/og-default.jpg'),
            'description' => $description,
            'email' => $setting?->email,
            'telephone' => $phone?->number,
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => 'Griya Taman Asri Blok FF 08-09 RT 32 RW 08',
                'addressLocality' => 'Tawangsari, Taman',
                'addressRegion' => 'Jawa Timur',
                'addressCountry' => 'ID',
            ],
            'hasMap' => $setting?->maps_url,
            'sameAs' => array_values(array_filter([$setting?->instagramUrl(), $setting?->youtube_url])) ?: null,
        ]);
    @endphp
    <script type="application/ld+json">{!! json_encode($mosque, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
@endif
