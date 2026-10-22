<?php

namespace Database\Factories;

use App\Models\Facility;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Facility>
 */
class FacilityFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Tempat wudhu',
            'is_active' => true,
            'sort_order' => 0,
        ];
    }
}
