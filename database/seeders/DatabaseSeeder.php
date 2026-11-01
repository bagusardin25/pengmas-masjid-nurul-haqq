<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        if (! User::query()->where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('password'),
            ]);
        }

        $this->call([
            GalleryCategorySeeder::class,
            HomePageSeeder::class,
            AboutPageSeeder::class,
            ActivityPageSeeder::class,
            TpqPageSeeder::class,
            ZiswafPageSeeder::class,
            GalleryPageSeeder::class,
            ContactSettingSeeder::class,
        ]);
    }
}
