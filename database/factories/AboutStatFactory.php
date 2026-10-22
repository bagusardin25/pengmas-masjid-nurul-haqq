<?php

namespace Database\Factories;

use App\Models\AboutStat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AboutStat>
 */
class AboutStatFactory extends Factory
{
    public function definition(): array
    {
        return [
            'value' => '270 m²',
            'label' => 'Luas tanah',
            'sort_order' => 0,
        ];
    }
}
