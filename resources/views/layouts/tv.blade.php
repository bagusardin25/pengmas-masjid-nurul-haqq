<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="300">
    <title>@yield('title', 'Informasi Masjid Nurul Haqq')</title>
    <meta name="theme-color" content="#0b3b2c">
    <link rel="icon" type="image/png" href="{{ asset('images/icons/icon-512.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="tv-body">
    @yield('content')
    <script>
        (() => {
            const clock = document.querySelector('[data-tv-clock]');
            if (!clock) return;

            const updateClock = () => {
                clock.textContent = new Intl.DateTimeFormat('id-ID', {
                    timeZone: 'Asia/Jakarta',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false,
                }).format(new Date());
            };

            updateClock();
            window.setInterval(updateClock, 1000);
        })();
    </script>
</body>
</html>
