<?php

namespace Database\Factories;

use App\Models\TpqPage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TpqPage>
 */
class TpqPageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'hero_title' => 'Tumbuh bersama Al-Qur\'an sejak dini',
        ];
    }
}
