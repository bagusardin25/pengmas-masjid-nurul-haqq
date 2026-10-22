<?php

namespace App\Models;

use App\Models\Concerns\HasPublicationStatus;
use App\Models\Concerns\HasSortOrder;
use Database\Factories\GalleryVideoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryVideo extends Model
{
    /** @use HasFactory<GalleryVideoFactory> */
    use HasFactory, HasPublicationStatus, HasSortOrder;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'youtube_url',
        'youtube_id',
        'title',
        'duration',
        'category_id',
        'description',
        'speaker',
        'date',
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

    protected static function booted(): void
    {
        static::saving(function (GalleryVideo $video): void {
            if (blank($video->youtube_url)) {
                return;
            }

            // ID yang diisi bersama URL dipertahankan; kalau hanya URL yang berubah, ID ikut URL.
            $urlChangedAlone = $video->isDirty('youtube_url') && $video->isClean('youtube_id');

            if (filled($video->youtube_id) && ! $urlChangedAlone) {
                return;
            }

            $youtubeId = self::parseYoutubeId($video->youtube_url);

            if ($youtubeId !== null) {
                $video->youtube_id = $youtubeId;
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(GalleryCategory::class, 'category_id');
    }

    public static function parseYoutubeId(string $url): ?string
    {
        $patterns = [
            '~youtu\.be/([A-Za-z0-9_-]{11})~',
            '~youtube\.com/(?:watch\?v=|shorts/|embed/)([A-Za-z0-9_-]{11})~',
            '~[?&]v=([A-Za-z0-9_-]{11})~',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches) === 1) {
                return $matches[1];
            }
        }

        return null;
    }
}
