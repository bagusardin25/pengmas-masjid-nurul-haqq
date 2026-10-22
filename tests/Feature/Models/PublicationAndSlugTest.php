<?php

namespace Tests\Feature\Models;

use App\Models\Agenda;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicationAndSlugTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_scope_hides_drafts(): void
    {
        Article::factory()->create(['title' => 'Masih draf']);
        $published = Article::factory()->published()->create(['title' => 'Sudah terbit']);

        $this->assertSame([$published->id], Article::query()->published()->pluck('id')->all());
    }

    public function test_blank_slug_comes_from_the_title_and_a_given_slug_stays(): void
    {
        $article = Article::factory()->create([
            'title' => 'Kajian Ahad',
            'slug' => null,
        ]);

        $this->assertSame('kajian-ahad', $article->slug);

        $article->update(['title' => 'Judul yang berubah']);

        $this->assertSame('kajian-ahad', $article->fresh()->slug);
    }

    public function test_duplicate_slugs_get_a_numeric_suffix(): void
    {
        Article::factory()->create([
            'title' => 'Kajian Ahad',
            'slug' => 'kajian-ahad',
        ]);

        $second = Article::factory()->create([
            'title' => 'Kajian Ahad',
            'slug' => null,
        ]);

        $this->assertSame('kajian-ahad-2', $second->slug);
    }

    public function test_publishing_an_article_sets_published_at_once(): void
    {
        $article = Article::factory()->published()->create(['title' => 'Terbit pertama']);

        $this->assertNotNull($article->published_at);

        $original = $article->published_at->toDateTimeString();
        $this->travel(1)->hour();
        $article->update(['status' => $article->status]);

        $this->assertSame($original, $article->fresh()->published_at->toDateTimeString());
    }

    public function test_publishing_an_agenda_sets_published_at(): void
    {
        $agenda = Agenda::factory()->published()->create();
        $draft = Agenda::factory()->create();

        $this->assertNotNull($agenda->fresh()->published_at);
        $this->assertNull($draft->fresh()->published_at);
    }
}
