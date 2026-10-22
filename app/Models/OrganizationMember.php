<?php

namespace App\Models;

use App\Models\Concerns\HasSortOrder;
use Database\Factories\OrganizationMemberFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationMember extends Model
{
    /** @use HasFactory<OrganizationMemberFactory> */
    use HasFactory, HasSortOrder;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'position',
        'name',
        'note',
        'sort_order',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
