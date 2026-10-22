<?php

namespace Database\Factories;

use App\Models\ActivityPage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ActivityPage>
 */
class ActivityPageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'hero_title' => 'Ibadah dan kajian setiap pekan',
        ];
    }
}
