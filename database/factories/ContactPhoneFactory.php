<?php

namespace Database\Factories;

use App\Models\ContactPhone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactPhone>
 */
class ContactPhoneFactory extends Factory
{
    public function definition(): array
    {
        return [
            'label' => 'Kontak takmir',
            'number' => '081239567390',
            'is_whatsapp' => false,
            'sort_order' => 0,
        ];
    }

    public function whatsapp(): static
    {
        return $this->state(fn () => [
            'is_whatsapp' => true,
        ]);
    }
}
