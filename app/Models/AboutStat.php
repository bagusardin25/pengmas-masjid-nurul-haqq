<?php

namespace App\Models;

use App\Models\Concerns\OrdersBySortOrder;
use Database\Factories\AboutStatFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutStat extends Model
{
    /** @use HasFactory<AboutStatFactory> */
    use HasFactory, OrdersBySortOrder;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'value',
        'label',
        'icon',
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
