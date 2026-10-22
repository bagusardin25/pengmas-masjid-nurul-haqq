<?php

namespace Database\Factories;

use App\Models\TpqPillar;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TpqPillar>
 */
class TpqPillarFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => 'Belajar Iqro\' & Al-Qur\'an',
            'description' => 'Pembelajaran membaca dari Iqro\' sampai Al-Qur\'an.',
            'sort_order' => 0,
        ];
    }
}
