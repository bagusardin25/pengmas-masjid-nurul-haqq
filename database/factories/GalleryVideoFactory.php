<?php

namespace Database\Factories;

use App\Enums\PublicationStatus;
use App\Models\GalleryVideo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GalleryVideo>
 */
class GalleryVideoFactory extends Factory
{
    public function definition(): array
    {
        $youtubeId = fake()->unique()->regexify('[A-Za-z0-9_-]{11}');

        return [
            'youtube_url' => 'https://youtu.be/'.$youtubeId,
            'youtube_id' => $youtubeId,
            'title' => 'Kajian '.fake()->unique()->numerify('###'),
            'date' => '2026-10-05',
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
