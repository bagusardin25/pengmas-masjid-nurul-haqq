<?php

namespace App\Models;

use App\Models\Concerns\OrdersBySortOrder;
use Database\Factories\TpqFaqFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TpqFaq extends Model
{
    /** @use HasFactory<TpqFaqFactory> */
    use HasFactory, OrdersBySortOrder;

    protected $table = 'tpq_faqs';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'question',
        'answer',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }
}
