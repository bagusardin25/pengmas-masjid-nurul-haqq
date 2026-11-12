<?php

use App\Models\User;
use Database\Seeders\DatabaseSeeder;

test('the database seeder can run more than once', function () {
    $this->seed(DatabaseSeeder::class);
    $this->seed(DatabaseSeeder::class);

    expect(User::query()->where('email', 'admin@gmail.com')->count())->toBe(1);
});
