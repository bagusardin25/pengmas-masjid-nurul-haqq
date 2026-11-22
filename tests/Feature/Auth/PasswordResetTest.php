<?php

use App\Models\User;

// Lupa password ditutup: email belum disetel, jadi tautan reset tidak akan pernah sampai.
// Password diganti admin lain lewat halaman Akun & Admin.

test('the forgot password pages are closed', function () {
    $this->get('/forgot-password')->assertNotFound();
    $this->get('/reset-password/token-apa-saja')->assertNotFound();
});

test('password reset requests are not accepted', function () {
    $user = User::factory()->create();

    $this->post('/forgot-password', ['email' => $user->email])->assertNotFound();
    $this->post('/reset-password', [
        'token' => 'token',
        'email' => $user->email,
        'password' => 'password-baru',
        'password_confirmation' => 'password-baru',
    ])->assertNotFound();
});
