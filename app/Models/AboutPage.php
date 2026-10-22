<?php

namespace App\Models;

use App\Models\Concerns\HasSingleton;
use Database\Factories\AboutPageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AboutPage extends Model
{
    /** @use HasFactory<AboutPageFactory> */
    use HasFactory, HasSingleton;

    protected $table = 'about_page';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'hero_title',
        'hero_description',
        'hero_image_id',
        'hero_badge',
        'intro_title',
        'intro_summary',
        'history_title',
        'history_content',
    ];

    public function heroImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'hero_image_id');
    }
}
