<?php

namespace App\Models;

use App\Enums\KitabDay;
use App\Models\Concerns\OrdersBySortOrder;
use Database\Factories\KitabScheduleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KitabSchedule extends Model
{
    /** @use HasFactory<KitabScheduleFactory> */
    use HasFactory, OrdersBySortOrder;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'kitab_name',
        'ustadz',
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
            'day' => KitabDay::class,
            'sort_order' => 'integer',
        ];
    }
}
