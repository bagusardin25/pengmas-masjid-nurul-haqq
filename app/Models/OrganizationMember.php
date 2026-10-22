<?php

namespace App\Models;

use App\Models\Concerns\HasPublicationStatus;
use App\Models\Concerns\OrdersBySortOrder;
use Database\Factories\OrganizationMemberFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationMember extends Model
{
    /** @use HasFactory<OrganizationMemberFactory> */
    use HasFactory, HasPublicationStatus, OrdersBySortOrder;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'position',
        'name',
        'note',
        'sort_order',
        'status',
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
