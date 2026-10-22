<?php

namespace Tests\Feature\Models;

use App\Models\GalleryPhoto;
use App\Models\HomePage;
use App\Models\Media;
use App\Models\TpqDocumentation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MediaRelationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_deleting_media_nulls_optional_images_and_removes_owned_rows(): void
    {
        $media = Media::factory()->create();
        $page = HomePage::factory()->create(['hero_image_id' => $media->id]);
        $photo = GalleryPhoto::factory()->create(['image_id' => $media->id]);
        $documentation = TpqDocumentation::factory()->create(['image_id' => $media->id]);

        $media->delete();

        $this->assertNull($page->fresh()->hero_image_id);
        $this->assertNull(GalleryPhoto::query()->find($photo->id));
        $this->assertNull(TpqDocumentation::query()->find($documentation->id));
    }
}
