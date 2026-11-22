<?php

namespace App\Models;

use App\Models\Concerns\HasPublicationStatus;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\HasSortOrder;
use Database\Factories\GalleryAlbumFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GalleryAlbum extends Model
{
    /** @use HasFactory<GalleryAlbumFactory> */
    use HasFactory, HasPublicationStatus, HasSlug, HasSortOrder;

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
        'published_at',
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
        return $this->hasMany(GalleryPhoto::class, 'album_id')->ordered();
    }

    /**
     * Foto pertama album (urutan paling atas), dipakai sebagai sampul bila sampul belum dipilih.
     */
    public function firstPhoto(): HasOne
    {
        return $this->hasOne(GalleryPhoto::class, 'album_id')->ofMany([
            'sort_order' => 'min',
            'id' => 'min',
        ]);
    }

    /**
     * Gambar sampul album: sampul yang dipilih, atau foto pertama album.
     * Muat relasi coverImage dan firstPhoto.image agar tidak ada query tambahan.
     */
    public function cover(): ?Media
    {
        return $this->coverImage ?? $this->firstPhoto?->image;
    }

    /**
     * Album terbaru lebih dulu; album tanpa tanggal di akhir, diurutkan menurut urutan.
     */
    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->orderByRaw('date is null')
            ->orderByDesc('date')
            ->orderBy('sort_order')
            ->orderByDesc('id');
    }

    /**
     * Relasi yang dibutuhkan untuk menampilkan kartu album (sampul dan jumlah foto).
     */
    public function scopeWithCardData(Builder $query): Builder
    {
        return $query->with(['category', 'coverImage', 'firstPhoto.image'])->withCount('photos');
    }
}
