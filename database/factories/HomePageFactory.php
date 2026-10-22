<?php

namespace Database\Factories;

use App\Models\HomePage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HomePage>
 */
class HomePageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'hero_title' => 'Masjid Nurul Haqq',
            'hero_description' => 'Rumah ibadah warga Griya Taman Asri.',
        ];
    }
}
