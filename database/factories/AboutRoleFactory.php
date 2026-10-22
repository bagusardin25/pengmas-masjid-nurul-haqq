<?php

namespace Database\Factories;

use App\Models\AboutRole;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AboutRole>
 */
class AboutRoleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => 'Pusat ibadah',
            'description' => 'Tempat sholat berjamaah warga.',
            'sort_order' => 0,
        ];
    }
}
