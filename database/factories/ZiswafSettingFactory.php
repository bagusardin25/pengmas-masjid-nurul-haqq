<?php

namespace Database\Factories;

use App\Models\ZiswafSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ZiswafSetting>
 */
class ZiswafSettingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'hero_title' => 'Salurkan kebaikan, kuatkan kebersamaan',
            'account_number' => '0123456789',
            'account_holder' => 'Yayasan Nurul Haqq',
        ];
    }
}
