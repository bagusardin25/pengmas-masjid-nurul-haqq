<?php

namespace Database\Factories;

use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Media>
 */
class MediaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'disk' => 'public',
            'path' => 'photos/'.fake()->unique()->uuid().'.jpg',
            'original_name' => 'foto.jpg',
            'alt' => 'Foto kegiatan',
            'mime_type' => 'image/jpeg',
            'size' => 1200,
            'width' => 800,
            'height' => 600,
        ];
    }
}
