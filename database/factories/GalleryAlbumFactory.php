<?php

namespace Database\Factories;

use App\Enums\PublicationStatus;
use App\Models\GalleryAlbum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GalleryAlbum>
 */
class GalleryAlbumFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => 'Album '.fake()->unique()->numerify('###'),
            'description' => 'Dokumentasi kegiatan jamaah.',
            'date' => '2026-10-07',
            'location' => 'Ruang utama',
            'status' => PublicationStatus::Draft,
            'sort_order' => 0,
        ];
    }

    public function published(): static
    {
        return $this->state(fn () => [
            'status' => PublicationStatus::Published,
        ]);
    }
}
