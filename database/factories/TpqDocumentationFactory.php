<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\TpqDocumentation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TpqDocumentation>
 */
class TpqDocumentationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'image_id' => Media::factory(),
            'caption' => 'Kegiatan belajar bersama',
            'sort_order' => 0,
        ];
    }
}
