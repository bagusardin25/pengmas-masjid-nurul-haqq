<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->user = User::factory()->create(['name' => 'Admin Utama']);
});

test('guests cannot manage admins', function () {
    $this->post(route('dashboard.admins.store'), ['name' => 'x'])->assertRedirect(route('login'));
    $this->delete(route('dashboard.admins.destroy', $this->user))->assertRedirect(route('login'));
});

test('the account page lists admins and is linked from the sidebar', function () {
    User::factory()->create(['name' => 'Gatot Bambang', 'email' => 'gatot@example.com']);

    $this->actingAs($this->user)
        ->get(route('profile.edit'))
        ->assertOk()
        ->assertSee('Akun &amp; Admin', false)
        ->assertSee('href="'.route('profile.edit').'"', false)
        ->assertSee('Gatot Bambang')
        ->assertSee('gatot@example.com')
        ->assertSee('Ganti password');
});

test('an admin can add another admin who can then log in', function () {
    $this->actingAs($this->user)
        ->post(route('dashboard.admins.store'), [
            'name' => 'Gatot Bambang',
            'email' => 'gatot@example.com',
            'password' => 'rahasia-takmir',
            'password_confirmation' => 'rahasia-takmir',
        ])
        ->assertRedirect(route('profile.edit').'#admin');

    $admin = User::query()->where('email', 'gatot@example.com')->sole();

    expect(Hash::check('rahasia-takmir', $admin->password))->toBeTrue()
        ->and($admin->email_verified_at)->not->toBeNull();

    auth()->logout();

    $this->post('/login', ['email' => 'gatot@example.com', 'password' => 'rahasia-takmir'])
        ->assertRedirect(route('dashboard', absolute: false));
    $this->assertAuthenticatedAs($admin);
});

test('new admins are validated with indonesian messages', function () {
    User::factory()->create(['email' => 'ada@example.com']);

    $this->actingAs($this->user)
        ->from(route('profile.edit'))
        ->post(route('dashboard.admins.store'), [
            'name' => '',
            'email' => 'ada@example.com',
            'password' => 'pendek',
            'password_confirmation' => 'beda',
        ])
        ->assertRedirect(route('profile.edit'))
        ->assertSessionHasErrors([
            'name' => 'Nama wajib diisi.',
            'email' => 'Email ini sudah terdaftar sebagai admin.',
        ], null, 'admin')
        ->assertSessionHasErrors(['password'], null, 'admin');

    expect(User::query()->count())->toBe(2);
});

test('an admin can remove another admin but not themselves', function () {
    $other = User::factory()->create();

    $this->actingAs($this->user)
        ->delete(route('dashboard.admins.destroy', $this->user))
        ->assertRedirect(route('profile.edit').'#admin');

    expect($this->user->fresh())->not->toBeNull();

    $this->actingAs($this->user)
        ->delete(route('dashboard.admins.destroy', $other))
        ->assertRedirect(route('profile.edit').'#admin');

    expect($other->fresh())->toBeNull();
});

test('password changes show indonesian errors', function () {
    $this->actingAs($this->user)
        ->from(route('profile.edit'))
        ->put(route('password.update'), [
            'current_password' => 'salah',
            'password' => 'password-baru',
            'password_confirmation' => 'password-lain',
        ])
        ->assertSessionHasErrors([
            'current_password' => 'Password saat ini salah.',
            'password' => 'Konfirmasi password baru tidak sama.',
        ], null, 'updatePassword');
});
