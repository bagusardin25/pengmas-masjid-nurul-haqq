<?php

namespace App\Models;

use App\Models\Concerns\HasSingleRow;
use Database\Factories\ZiswafPageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ZiswafPage extends Model
{
    /** @use HasFactory<ZiswafPageFactory> */
    use HasFactory, HasSingleRow;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'hero_title',
        'hero_description',
        'zakat_note',
        'infaq_note',
        'sedekah_note',
        'wakaf_note',
        'bank_name',
        'account_number',
        'account_holder',
        'qris_image_id',
        'qris_note',
        'confirmation_phone',
    ];

    public function qrisImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'qris_image_id');
    }
}
