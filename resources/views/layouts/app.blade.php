<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('description', 'Masjid Jami Nurul Haqq, Griya Taman Asri, Sidoarjo.')">
    <title>@yield('title', 'Masjid Jami Nurul Haqq') — Masjid Jami Nurul Haqq</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body>
    <div class="site-shell">
        <x-site-header />
        <main id="main-content">
            @yield('content')
        </main>
        <x-site-footer />
    </div>
</body>
</html>
