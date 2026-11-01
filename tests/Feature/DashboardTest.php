<?php

use App\Models\User;

test('verified users can view the empty dashboard shell', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertOk()
        ->assertSee('dashboard-sidebar', false)
        ->assertSee('Dashboard')
        ->assertSee('Kelola Beranda')
        ->assertSee('Profil Masjid')
        ->assertSee('href="'.route('dashboard.beranda').'"', false)
        ->assertSee('id="dashboard-main-content" class="dashboard-content"', false)
        ->assertDontSee("You're logged in!");
});

test('verified users can view the empty home management page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('dashboard.beranda'));

    $response->assertOk()
        ->assertSee('Kelola Beranda')
        ->assertSee('aria-current="page"', false)
        ->assertSee('id="dashboard-main-content" class="dashboard-content"', false)
        ->assertDontSee("You're logged in!");
});
