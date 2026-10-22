<?php

namespace Database\Factories;

use App\Enums\KitabDay;
use App\Models\KitabSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KitabSchedule>
 */
class KitabScheduleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kitab_name' => 'Safinatun Najah',
            'ustadz' => 'Ust. Ahmad Fauzi',
            'day' => KitabDay::Sabtu,
            'time' => '18.00 WIB (ba\'da Maghrib)',
            'location' => 'Ruang utama masjid',
            'sort_order' => 0,
        ];
    }
}
