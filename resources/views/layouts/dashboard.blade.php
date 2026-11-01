<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Masjid Jami Nurul Haqq</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body>
    <div
        class="dashboard-shell"
        x-data="{ sidebarOpen: false }"
        @keydown.escape.window="sidebarOpen = false"
    >
        <x-dashboard.sidebar />

        <div class="dashboard-main">
            <x-dashboard.topbar />
            <main id="dashboard-main-content" class="dashboard-content">
                @yield('content')
            </main>
        </div>

        <button
            class="dashboard-backdrop"
            type="button"
            aria-label="Tutup menu navigasi"
            x-show="sidebarOpen"
            x-cloak
            @click="sidebarOpen = false"
        ></button>
    </div>
</body>
</html>
