<?php

namespace App\Models;

use App\Models\Concerns\GeneratesSlug;
use App\Models\Concerns\HasPublicationStatus;
use Database\Factories\ArticleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    /** @use HasFactory<ArticleFactory> */
    use GeneratesSlug, HasFactory, HasPublicationStatus;

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

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function coverImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'cover_image_id');
    }
}
