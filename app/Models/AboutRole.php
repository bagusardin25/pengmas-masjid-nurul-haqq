<?php

namespace App\Models;

use App\Models\Concerns\HasSortOrder;
use Database\Factories\AboutRoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutRole extends Model
{
    /** @use HasFactory<AboutRoleFactory> */
    use HasFactory, HasSortOrder;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
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
