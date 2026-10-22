<?php

namespace App\Models;

use App\Models\Concerns\HasPublicationStatus;
use App\Models\Concerns\HasSlug;
use Database\Factories\ArticleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    /** @use HasFactory<ArticleFactory> */
    use HasFactory, HasPublicationStatus, HasSlug;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'author',
        'category',
        'excerpt',
        'body',
        'cover_image_id',
        'status',
        'published_at',
    ];

    public function coverImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'cover_image_id');
    }
}
