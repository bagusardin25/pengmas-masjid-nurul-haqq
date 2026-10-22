<?php

namespace Tests\Feature\Models;

use App\Exceptions\SingletonAlreadyExists;
use App\Models\HomePage;
use App\Models\ZiswafSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SingletonModelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_page_model_keeps_only_one_row(): void
    {
        $this->assertNull(HomePage::current());

        $page = HomePage::factory()->create();

        $this->assertTrue($page->is(HomePage::current()));

        $this->expectException(SingletonAlreadyExists::class);

        HomePage::factory()->create();
    }

    public function test_ziswaf_settings_keep_only_one_row(): void
    {
        ZiswafSetting::factory()->create();

        $this->expectException(SingletonAlreadyExists::class);

        ZiswafSetting::factory()->create();
    }
}
