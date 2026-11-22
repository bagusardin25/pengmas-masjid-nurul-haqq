<?php

namespace App\Models;

use App\Models\Concerns\HasSortOrder;
use Database\Factories\ZiswafProgramFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Program penyaluran sedekah (mis. Peduli Palestina, Anak Yatim, Kotak Ambulans).
 */
class ZiswafProgram extends Model
{
    /** @use HasFactory<ZiswafProgramFactory> */
    use HasFactory, HasSortOrder;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
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
