<?php

namespace Database\Factories;

use App\Enums\PublicationStatus;
use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => 'Tulisan '.fake()->unique()->numerify('###'),
            'author' => 'Ust. Ahmad Fauzi',
            'category' => 'Kajian',
            'excerpt' => 'Ringkasan tulisan jamaah.',
            'body' => 'Isi tulisan untuk jamaah.',
            'status' => PublicationStatus::Draft,
        ];
    }

    public function published(): static
    {
        return $this->state(fn () => [
            'status' => PublicationStatus::Published,
        ]);
    }
}
