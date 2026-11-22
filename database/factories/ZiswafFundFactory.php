<?php

namespace Database\Factories;

use App\Models\ZiswafFund;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ZiswafFund>
 */
class ZiswafFundFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Sedekah & Infak',
            'description' => 'Operasional dan pembangunan masjid',
            'icon' => null,
            'sort_order' => 0,
            'is_active' => true,
        ];
    }
}
