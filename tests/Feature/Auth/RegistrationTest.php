<?php

use App\Models\User;

test('the public registration page is closed', function () {
    $this->get('/register')->assertNotFound();
});

test('nobody can register an account without logging in', function () {
    $this->post('/register', [
        'name' => 'Penyusup',
        'email' => 'penyusup@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertNotFound();

    $this->assertGuest();
    expect(User::query()->where('email', 'penyusup@example.com')->exists())->toBeFalse();
});

test('the login page does not link to registration', function () {
    $this->get('/login')->assertOk()->assertDontSee('/register', false);
});
