<?php

namespace Database\Factories;

use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GalleryPhoto>
 */
class GalleryPhotoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'album_id' => GalleryAlbum::factory(),
            'image_id' => Media::factory(),
            'caption' => null,
            'sort_order' => 0,
        ];
    }
}
