<?php

namespace App\Models;

use App\Models\Concerns\GeneratesSlug;
use App\Models\Concerns\HasPublicationStatus;
use App\Models\Concerns\OrdersBySortOrder;
use Database\Factories\GalleryAlbumFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GalleryAlbum extends Model
{
    /** @use HasFactory<GalleryAlbumFactory> */
    use GeneratesSlug, HasFactory, HasPublicationStatus, OrdersBySortOrder;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'description',
        'date',
        'location',
        'cover_image_id',
        'status',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'sort_order' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(GalleryCategory::class, 'category_id');
    }

    public function coverImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'cover_image_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(GalleryPhoto::class, 'album_id')->orderBy('sort_order')->orderBy('id');
    }
}
