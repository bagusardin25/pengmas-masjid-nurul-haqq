<?php

namespace App\Models;

use App\Enums\ProfileIcon;
use App\Models\Concerns\HasSortOrder;
use Database\Factories\ZiswafFundFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Jenis dana di halaman ZISWAF (mis. Sedekah & Infak, Zakat, Wakaf).
 */
class ZiswafFund extends Model
{
    /** @use HasFactory<ZiswafFundFactory> */
    use HasFactory, HasSortOrder;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'icon',
        'sort_order',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'icon' => ProfileIcon::class,
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
