<?php

namespace App\Models;

use App\Models\Concerns\HasSortOrder;
use Database\Factories\TpqFaqFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TpqFaq extends Model
{
    /** @use HasFactory<TpqFaqFactory> */
    use HasFactory, HasSortOrder;

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
