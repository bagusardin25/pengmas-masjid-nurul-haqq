<?php

namespace Database\Factories;

use App\Models\TpqFaq;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TpqFaq>
 */
class TpqFaqFactory extends Factory
{
    public function definition(): array
    {
        return [
            'question' => 'Usia berapa yang bisa mendaftar?',
            'answer' => 'Program ini untuk anak usia 6–15 tahun.',
            'sort_order' => 0,
        ];
    }
}
