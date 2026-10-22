<?php

namespace Database\Factories;

use App\Enums\PublicationStatus;
use App\Models\Agenda;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Agenda>
 */
class AgendaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => 'Pengajian Akbar '.fake()->unique()->numerify('###'),
            'date' => fake()->dateTimeBetween('+1 week', '+2 months')->format('Y-m-d'),
            'time' => '19.30 WIB',
            'location' => 'Halaman masjid',
            'status' => PublicationStatus::Draft,
        ];
    }

    public function published(): static
    {
        return $this->state(fn () => [
            'status' => PublicationStatus::Published,
        ]);
    }
}
