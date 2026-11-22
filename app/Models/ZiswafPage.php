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
        'summary',
        'bank_name',
        'account_number',
        'account_holder',
        'account_note',
        'qris_image_id',
        'qris_note',
        'infaq_box_description',
        'report_description',
    ];

    public function qrisImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'qris_image_id');
    }

    /**
     * Rekening baru ditampilkan bila nomor rekeningnya sudah diisi.
     */
    public function hasBankAccount(): bool
    {
        return filled($this->account_number);
    }
}
