@extends('layouts.dashboard')

@section('title', 'Perawatan Server')
@section('breadcrumb', 'Perawatan Server')

@php
    $stateLabels = [
        'ok' => ['Terhubung', 'Foto dan berkas unggahan bisa dibuka di situs.'],
        'missing' => ['Belum dibuat', 'Foto yang diunggah belum bisa tampil di situs.'],
        'broken' => ['Rusak', 'Symlink mengarah ke folder yang tidak ada, biasanya karena project dipindah ke folder lain di hosting.'],
        'folder' => ['Bentrok', 'Sudah ada folder biasa dengan nama yang sama, jadi symlink tidak bisa dibuat.'],
    ];
    $allOk = collect($links)->every(fn ($link) => $link['state'] === 'ok');
@endphp

@section('content')
    <div class="dash-page">
        <header class="dash-page-header">
            <div>
                <p class="dash-eyebrow">Khusus deployment</p>
                <h1>Perawatan Server</h1>
                <p>Pengganti perintah terminal untuk shared hosting. Halaman ini tidak ada di menu; simpan alamatnya baik-baik dan jangan dibagikan.</p>
            </div>
        </header>

        <x-dashboard.status-toast />

        @if (session('maintenance_error'))
            <div class="dash-maintenance-error" role="alert">{{ session('maintenance_error') }}</div>
        @endif

        <section class="dash-panel" aria-labelledby="storage-title">
            <header class="dash-panel-header dash-panel-header-row">
                <h2 id="storage-title">Storage link</h2>
                <span @class(['dash-status', 'dash-status-draft' => ! $allOk])>{{ $allOk ? 'Siap' : 'Perlu dibuat' }}</span>
            </header>
            <p class="dash-maintenance-text">
                Foto galeri, QRIS, dan PDF laporan disimpan di <code>storage/app/public</code>. Agar bisa dibuka dari situs,
                folder itu perlu “dihubungkan” (symlink) ke <code>public/storage</code> — sama dengan perintah <code>php artisan storage:link</code>.
            </p>

            <ul class="dash-mini-list dash-maintenance-list">
                @foreach ($links as $link)
                    @php [$label, $description] = $stateLabels[$link['state']]; @endphp
                    <li>
                        <span @class(['dash-check-icon', 'is-ok' => $link['state'] === 'ok']) aria-hidden="true">{{ $link['state'] === 'ok' ? '✓' : '!' }}</span>
                        <span>
                            <strong>{{ $label }}</strong>
                            <small>{{ $description }}</small>
                            <small><code>{{ $link['link'] }}</code> → <code>{{ $link['target'] }}</code></small>
                        </span>
                    </li>
                @endforeach
            </ul>

            @unless ($symlinkAvailable)
                <p class="dash-maintenance-warning">Fungsi <code>symlink()</code> dimatikan oleh hosting ini. Minta penyedia hosting mengaktifkannya, atau buat symlink lewat File Manager/cPanel dengan alamat di atas.</p>
            @endunless

            <div class="dash-form-actions dash-maintenance-actions">
                @if (collect($links)->contains(fn ($link) => $link['state'] !== 'missing' && $link['state'] !== 'folder'))
                    <form method="POST" action="{{ route('dashboard.maintenance.unlink', $token) }}"
                        onsubmit="return confirm('Hapus storage link? Foto di situs tidak tampil sampai link dibuat lagi. Berkasnya tetap aman.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="dash-button dash-button-ghost">Hapus link</button>
                    </form>
                @endif
                <form method="POST" action="{{ route('dashboard.maintenance.link', $token) }}">
                    @csrf
                    <button type="submit" class="dash-button dash-button-primary" @disabled($allOk || ! $symlinkAvailable)>
                        {{ collect($links)->contains('state', 'broken') ? 'Perbaiki storage link' : 'Buat storage link' }}
                    </button>
                </form>
            </div>
        </section>

        <section class="dash-panel" aria-labelledby="cache-title">
            <header class="dash-panel-header">
                <h2 id="cache-title">Bersihkan cache</h2>
                <p>Jalankan setelah mengunggah versi baru project atau mengubah <code>.env</code>, bila perubahan belum terlihat. Sama dengan <code>php artisan optimize:clear</code>.</p>
            </header>
            <form method="POST" action="{{ route('dashboard.maintenance.cache', $token) }}" class="dash-form-actions dash-maintenance-actions">
                @csrf
                <button type="submit" class="dash-button dash-button-secondary">Bersihkan cache</button>
            </form>
        </section>

        <section class="dash-panel" aria-labelledby="info-title">
            <header class="dash-panel-header">
                <h2 id="info-title">Info folder</h2>
                <p>Di shared hosting, folder <code>public</code> biasanya harus menjadi <code>public_html</code>. Pastikan alamat di bawah sesuai dengan folder situs di hosting Anda.</p>
            </header>
            <dl class="dash-maintenance-info">
                <dt>Folder publik</dt>
                <dd><code>{{ $publicPath }}</code></dd>
                <dt>Folder unggahan</dt>
                <dd><code>{{ $storagePath }}</code></dd>
                <dt>PHP</dt>
                <dd><code>{{ PHP_VERSION }}</code></dd>
            </dl>
        </section>
    </div>
@endsection
