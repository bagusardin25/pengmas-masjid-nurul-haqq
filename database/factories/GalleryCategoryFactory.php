<?php

namespace Database\Factories;

use App\Models\GalleryCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GalleryCategory>
 */
class GalleryCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Kategori '.fake()->unique()->numerify('###'),
            'sort_order' => 0,
        ];
    }
}
