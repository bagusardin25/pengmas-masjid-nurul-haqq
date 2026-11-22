<?php

use App\Models\User;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    $this->user = User::factory()->create();

    // Symlink diuji di folder sementara agar public/storage project tidak tersentuh.
    $this->sandbox = sys_get_temp_dir().'/nurulhaqq-maintenance-'.uniqid();
    $this->link = $this->sandbox.'/public/storage';
    $this->target = $this->sandbox.'/storage/app/public';
    File::ensureDirectoryExists($this->sandbox.'/public');
    File::ensureDirectoryExists($this->target);

    config([
        'app.maintenance_token' => 'token-rahasia-uji',
        'filesystems.links' => [$this->link => $this->target],
    ]);
});

afterEach(function () {
    if (is_link($this->link)) {
        unlink($this->link);
    }

    File::deleteDirectory($this->sandbox);
});

test('the maintenance page is hidden without the right token', function () {
    $this->actingAs($this->user)->get('/dashboard/perawatan/salah')->assertNotFound();
    $this->actingAs($this->user)->post('/dashboard/perawatan/salah/storage-link')->assertNotFound();

    config(['app.maintenance_token' => null]);
    $this->actingAs($this->user)->get('/dashboard/perawatan/token-rahasia-uji')->assertNotFound();
});

test('guests cannot open the maintenance page even with the token', function () {
    $this->get('/dashboard/perawatan/token-rahasia-uji')->assertRedirect(route('login'));
    $this->post('/dashboard/perawatan/token-rahasia-uji/storage-link')->assertRedirect(route('login'));

    expect(is_link($this->link))->toBeFalse();
});

test('the maintenance page is not linked from the dashboard menu', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard'))
        ->assertDontSee('perawatan', false);
});

test('an admin can create, repair, and remove the storage link', function () {
    $url = '/dashboard/perawatan/token-rahasia-uji';

    $this->actingAs($this->user)->get($url)->assertOk()->assertSee('Belum dibuat');

    $this->actingAs($this->user)->post($url.'/storage-link')
        ->assertRedirect($url)
        ->assertSessionHas('status');

    expect(is_link($this->link))->toBeTrue()
        ->and(realpath($this->link))->toBe(realpath($this->target));

    $this->actingAs($this->user)->get($url)->assertOk()->assertSee('Terhubung');

    // Symlink rusak (mis. project dipindah) dibuat ulang ke folder yang benar.
    unlink($this->link);
    symlink($this->sandbox.'/folder-lama', $this->link);

    $this->actingAs($this->user)->get($url)->assertSee('Rusak');
    $this->actingAs($this->user)->post($url.'/storage-link')->assertSessionHas('status');

    expect(realpath($this->link))->toBe(realpath($this->target));

    $this->actingAs($this->user)->delete($url.'/storage-link')->assertSessionHas('status');

    expect(is_link($this->link))->toBeFalse()
        ->and(is_dir($this->target))->toBeTrue();
});

test('an existing real folder is never replaced', function () {
    File::ensureDirectoryExists($this->link);
    File::put($this->link.'/penting.jpg', 'isi');

    $this->actingAs($this->user)
        ->post('/dashboard/perawatan/token-rahasia-uji/storage-link')
        ->assertSessionHas('maintenance_error');

    expect(is_link($this->link))->toBeFalse()
        ->and(File::exists($this->link.'/penting.jpg'))->toBeTrue();

    $this->actingAs($this->user)->delete('/dashboard/perawatan/token-rahasia-uji/storage-link');

    expect(File::exists($this->link.'/penting.jpg'))->toBeTrue();
});

test('an admin can clear the application cache', function () {
    $this->actingAs($this->user)
        ->post('/dashboard/perawatan/token-rahasia-uji/bersihkan-cache')
        ->assertRedirect('/dashboard/perawatan/token-rahasia-uji')
        ->assertSessionHas('status', 'Cache konfigurasi, route, dan tampilan dibersihkan.');
});
