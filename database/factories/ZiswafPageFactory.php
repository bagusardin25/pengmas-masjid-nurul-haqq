<?php

namespace Database\Factories;

use App\Models\ZiswafPage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ZiswafPage>
 */
class ZiswafPageFactory extends Factory
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
