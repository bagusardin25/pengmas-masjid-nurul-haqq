<?php

namespace Database\Factories;

use App\Models\RoutineActivity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RoutineActivity>
 */
class RoutineActivityFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => 'Pengajian rutin',
            'schedule' => 'Ahad · 19.30 WIB',
            'badge' => 'Ahad',
            'show_on_home' => true,
            'sort_order' => 0,
        ];
    }
}
