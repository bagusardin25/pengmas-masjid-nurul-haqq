<?php

namespace Database\Factories;

use App\Models\ZiswafContact;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ZiswafContact>
 */
class ZiswafContactFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Pengurus takmir',
            'role' => null,
            'phone' => '0812-0000-0000',
            'sort_order' => 0,
        ];
    }
}
