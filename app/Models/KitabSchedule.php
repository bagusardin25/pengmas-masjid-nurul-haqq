<?php

namespace App\Models;

use App\Enums\Weekday;
use App\Models\Concerns\HasSortOrder;
use Database\Factories\KitabScheduleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KitabSchedule extends Model
{
    /** @use HasFactory<KitabScheduleFactory> */
    use HasFactory, HasSortOrder;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'speaker',
        'day',
        'time',
        'location',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'day' => Weekday::class,
            'sort_order' => 'integer',
        ];
    }
}
