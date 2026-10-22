<?php

namespace Database\Factories;

use App\Models\AboutPage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AboutPage>
 */
class AboutPageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'hero_title' => 'Rumah ibadah warga Griya Taman Asri',
        ];
    }
}
