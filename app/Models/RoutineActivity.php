<?php

namespace App\Models;

use App\Models\Concerns\OrdersBySortOrder;
use Database\Factories\RoutineActivityFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoutineActivity extends Model
{
    /** @use HasFactory<RoutineActivityFactory> */
    use HasFactory, OrdersBySortOrder;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'schedule',
        'badge',
        'icon',
        'show_on_home',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'show_on_home' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
