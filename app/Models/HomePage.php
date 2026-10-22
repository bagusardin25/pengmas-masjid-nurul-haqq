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

    /**
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
        'hero_primary_label',
        'hero_primary_url',
        'hero_secondary_label',
        'hero_secondary_url',
    ];

    public function heroImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'hero_image_id');
    }
}
