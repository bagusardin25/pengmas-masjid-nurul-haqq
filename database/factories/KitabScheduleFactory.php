<?php

namespace Database\Factories;

use App\Enums\Weekday;
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
            'title' => 'Safinatun Najah',
            'speaker' => 'Ust. Ahmad Fauzi',
            'day' => Weekday::Sabtu,
            'time' => '18.00 WIB (ba\'da Maghrib)',
            'location' => 'Ruang utama masjid',
            'sort_order' => 0,
        ];
    }
}
