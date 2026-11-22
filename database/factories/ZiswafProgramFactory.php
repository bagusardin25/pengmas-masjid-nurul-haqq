<?php

namespace Database\Factories;

use App\Models\ZiswafProgram;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ZiswafProgram>
 */
class ZiswafProgramFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => 'Anak Yatim',
            'description' => 'Santunan dan bantuan bagi anak yatim.',
            'sort_order' => 0,
            'is_active' => true,
        ];
    }
}
