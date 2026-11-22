<?php

namespace App\Models;

use App\Models\Concerns\HasSingleRow;
use Database\Factories\HomePageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomePage extends Model
{
    /** @use HasFactory<HomePageFactory> */
    use HasFactory, HasSingleRow;

    /** Foto hero bawaan (ikut di repo) bila belum ada foto yang diunggah dari dashboard. */
    public const DEFAULT_HERO_IMAGE = 'images/beranda/hero-masjid-pintu-1200.jpg';

    public const DEFAULT_HERO_ALT = 'Pintu masuk Masjid Nurul Haqq dengan papan nama dan fasad yang diterangi lampu pada malam hari';

    /**
     * Kolom tombol hero (hero_primary_*, hero_secondary_*) sudah dihapus dari migrasi,
     * jadi tidak dicantumkan di sini.
     *
     * @var list<string>
     */
    protected $fillable = [
        'hero_eyebrow',
        'hero_title',
        'hero_description',
        'hero_image_id',
        'hero_arabic_text',
        'hero_translation',
        'hero_citation',
    ];

    public function heroImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'hero_image_id');
    }
}
