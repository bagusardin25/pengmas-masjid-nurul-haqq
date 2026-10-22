<?php

namespace Database\Factories;

use App\Models\ContactSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactSetting>
 */
class ContactSettingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'email' => 'takmir@nurulhaqq.test',
            'instagram' => 'masjidnurulhaqq',
            'address' => 'Griya Taman Asri, Sidoarjo',
        ];
    }
}
