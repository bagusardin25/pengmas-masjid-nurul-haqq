<?php

namespace App\Models;

use App\Enums\ProfileIcon;
use App\Models\Concerns\HasSortOrder;
use Database\Factories\AboutStatFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutStat extends Model
{
    /** @use HasFactory<AboutStatFactory> */
    use HasFactory, HasSortOrder;

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
            'icon' => ProfileIcon::class,
            'sort_order' => 'integer',
        ];
    }
}
