<?php

namespace Tests\Feature\Models;

use App\Models\GalleryAlbum;
use App\Models\GalleryCategory;
use App\Models\GalleryPhoto;
use App\Models\GalleryVideo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GalleryRelationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_album_photos_follow_sort_order(): void
    {
        $album = GalleryAlbum::factory()->create();
        $later = GalleryPhoto::factory()->create([
            'album_id' => $album->id,
            'sort_order' => 2,
        ]);
        $earlier = GalleryPhoto::factory()->create([
            'album_id' => $album->id,
            'sort_order' => 1,
        ]);

        $this->assertSame(
            [$earlier->id, $later->id],
            $album->photos()->pluck('id')->all(),
        );
    }

    public function test_category_owns_albums_and_videos(): void
    {
        $category = GalleryCategory::factory()->create();
        $album = GalleryAlbum::factory()->create(['category_id' => $category->id]);
        $video = GalleryVideo::factory()->create(['category_id' => $category->id]);

        $this->assertTrue($category->albums()->whereKey($album->id)->exists());
        $this->assertTrue($category->videos()->whereKey($video->id)->exists());
    }
}
