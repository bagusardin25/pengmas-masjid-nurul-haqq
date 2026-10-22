<?php

namespace Tests\Feature\Models;

use App\Models\GalleryVideo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GalleryVideoTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_short_youtube_url_fills_an_empty_id(): void
    {
        $video = GalleryVideo::factory()->create([
            'youtube_url' => 'https://youtu.be/abcdefghijk',
            'youtube_id' => null,
        ]);

        $this->assertSame('abcdefghijk', $video->youtube_id);
    }

    public function test_an_existing_youtube_id_is_not_replaced(): void
    {
        $video = GalleryVideo::factory()->create([
            'youtube_url' => 'https://youtu.be/abcdefghijk',
            'youtube_id' => 'tetapada11x',
        ]);

        $this->assertSame('tetapada11x', $video->youtube_id);
    }

    public function test_changing_only_the_url_refreshes_the_id(): void
    {
        $video = GalleryVideo::factory()->create([
            'youtube_url' => 'https://youtu.be/abcdefghijk',
            'youtube_id' => 'abcdefghijk',
        ]);

        $video->update(['youtube_url' => 'https://www.youtube.com/watch?v=zyxwvutsrqp']);

        $this->assertSame('zyxwvutsrqp', $video->fresh()->youtube_id);
    }
}
