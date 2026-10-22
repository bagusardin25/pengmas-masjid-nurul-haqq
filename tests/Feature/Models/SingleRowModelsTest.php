<?php

namespace Tests\Feature\Models;

use App\Exceptions\SingleRowAlreadyExists;
use App\Models\HomePage;
use App\Models\ZiswafPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SingleRowModelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_page_model_keeps_only_one_row(): void
    {
        $this->assertNull(HomePage::current());

        $page = HomePage::factory()->create();

        $this->assertTrue($page->is(HomePage::current()));

        $this->expectException(SingleRowAlreadyExists::class);

        HomePage::factory()->create();
    }

    public function test_the_ziswaf_page_keeps_only_one_row(): void
    {
        ZiswafPage::factory()->create();

        $this->expectException(SingleRowAlreadyExists::class);

        ZiswafPage::factory()->create();
    }
}
