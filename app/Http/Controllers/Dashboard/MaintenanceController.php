<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;
use Throwable;

/**
 * Perawatan server untuk shared hosting tanpa terminal: membuat ulang storage link dan membersihkan cache.
 *
 * Halaman ini tidak ada di menu. Selain harus masuk sebagai admin, alamatnya memuat token rahasia
 * (MAINTENANCE_TOKEN di .env). Token kosong atau salah dijawab 404 agar keberadaannya tidak terlihat.
 */
class MaintenanceController extends Controller
{
    public function show(string $token): View
    {
        $this->authorizeToken($token);

        return view('dashboard.perawatan', [
            'token' => $token,
            'links' => $this->linkStatuses(),
            'symlinkAvailable' => $this->symlinkAvailable(),
            'publicPath' => public_path(),
            'storagePath' => storage_path('app/public'),
        ]);
    }

    public function link(string $token): RedirectResponse
    {
        $this->authorizeToken($token);

        if (! $this->symlinkAvailable()) {
            return $this->back($token, 'Fungsi symlink() dimatikan oleh hosting. Minta penyedia hosting mengaktifkannya, atau buat symlink lewat menu File Manager/cPanel.', false);
        }

        $problems = [];

        foreach ($this->linkStatuses() as $status) {
            if ($status['state'] === 'ok') {
                continue;
            }

            if ($status['state'] === 'folder') {
                $problems[] = $status['link'].' sudah berupa folder biasa. Pindahkan isinya ke '.$status['target'].' lalu hapus folder itu dulu.';

                continue;
            }

            // Symlink lama yang rusak (mis. karena pindah folder hosting) dihapus dulu, lalu dibuat ulang.
            if ($status['state'] === 'broken') {
                @unlink($status['link']);
            }

            try {
                if (! is_dir($status['target'])) {
                    mkdir($status['target'], 0755, true);
                }

                symlink($status['target'], $status['link']);
            } catch (Throwable $exception) {
                $problems[] = 'Gagal membuat '.$status['link'].': '.$exception->getMessage();
            }
        }

        clearstatcache();

        return $problems === []
            ? $this->back($token, 'Storage link siap. Foto dan berkas yang diunggah sekarang bisa dibuka di situs.')
            : $this->back($token, implode(' ', $problems), false);
    }

    public function unlink(string $token): RedirectResponse
    {
        $this->authorizeToken($token);

        foreach ($this->linkStatuses() as $status) {
            // Hanya symlink yang dihapus; folder berisi berkas tidak pernah disentuh.
            if (is_link($status['link'])) {
                @unlink($status['link']);
            }
        }

        clearstatcache();

        return $this->back($token, 'Storage link dihapus. Berkas di folder storage tetap aman.');
    }

    public function clearCache(string $token): RedirectResponse
    {
        $this->authorizeToken($token);

        try {
            Artisan::call('optimize:clear');
        } catch (Throwable $exception) {
            return $this->back($token, 'Gagal membersihkan cache: '.$exception->getMessage(), false);
        }

        return $this->back($token, 'Cache konfigurasi, route, dan tampilan dibersihkan.');
    }

    private function authorizeToken(string $token): void
    {
        $expected = (string) config('app.maintenance_token');

        abort_if($expected === '' || ! hash_equals($expected, $token), 404);
    }

    private function symlinkAvailable(): bool
    {
        $disabled = array_map('trim', explode(',', (string) ini_get('disable_functions')));

        return function_exists('symlink') && ! in_array('symlink', $disabled, true);
    }

    /**
     * Keadaan setiap link di config/filesystems.php ("links").
     *
     * @return list<array{link: string, target: string, state: string}>
     */
    private function linkStatuses(): array
    {
        $statuses = [];

        foreach (config('filesystems.links', []) as $link => $target) {
            $state = match (true) {
                is_link($link) && realpath($link) !== false && realpath($link) === realpath($target) => 'ok',
                is_link($link) => 'broken',
                file_exists($link) => 'folder',
                default => 'missing',
            };

            $statuses[] = ['link' => $link, 'target' => $target, 'state' => $state];
        }

        return $statuses;
    }

    private function back(string $token, string $message, bool $success = true): RedirectResponse
    {
        return to_route('dashboard.maintenance', $token)
            ->with($success ? 'status' : 'maintenance_error', $message);
    }
}
