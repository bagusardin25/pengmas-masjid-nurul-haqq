<?php

use App\Models\User;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});

test('the login page uses the masjid design in indonesian', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSee('Masuk ke dashboard')
        ->assertSee('Nurul Haqq')
        ->assertSee('images/beranda/hero-masjid-pintu-1200.jpg', false)
        ->assertDontSee('Forgot your password?')
        ->assertDontSee('Remember me');
});

test('failed logins show an indonesian message', function () {
    $user = User::factory()->create();

    $this->from('/login')
        ->post('/login', ['email' => $user->email, 'password' => 'salah'])
        ->assertRedirect('/login')
        ->assertSessionHasErrors(['email' => 'Email atau password salah. Periksa kembali lalu coba lagi.']);

    $this->from('/login')
        ->post('/login', ['email' => '', 'password' => ''])
        ->assertSessionHasErrors(['email' => 'Email wajib diisi.', 'password' => 'Password wajib diisi.']);
});
