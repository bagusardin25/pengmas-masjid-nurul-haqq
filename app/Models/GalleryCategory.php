<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Models\Concerns\HasSortOrder;
use Database\Factories\GalleryCategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GalleryCategory extends Model
{
    /** @use HasFactory<GalleryCategoryFactory> */
    use HasFactory, HasSlug, HasSortOrder;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    protected function slugSource(): string
    {
        return 'name';
    }

    public function albums(): HasMany
    {
        return $this->hasMany(GalleryAlbum::class, 'category_id');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(GalleryVideo::class, 'category_id');
    }
}
