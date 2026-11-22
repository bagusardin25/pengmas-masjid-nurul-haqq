<?php

namespace App\Models;

use App\Models\Concerns\HasPublicationStatus;
use App\Models\Concerns\HasSlug;
use Database\Factories\ArticleFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Article extends Model
{
    /** @use HasFactory<ArticleFactory> */
    use HasFactory, HasPublicationStatus, HasSlug;

    /** Rata-rata kecepatan membaca (kata per menit) untuk perkiraan waktu baca. */
    private const WORDS_PER_MINUTE = 200;

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

    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->orderByDesc('published_at')->orderByDesc('id');
    }

    /**
     * Ringkasan untuk kartu artikel: ringkasan yang diisi, atau awal isi artikel.
     */
    public function summary(int $limit = 220): ?string
    {
        if (filled($this->excerpt)) {
            return $this->excerpt;
        }

        return filled($this->body) ? Str::limit(Str::squish($this->body), $limit) : null;
    }

    /**
     * Isi artikel dipecah per paragraf (dipisahkan baris kosong).
     *
     * @return list<string>
     */
    public function paragraphs(): array
    {
        $paragraphs = preg_split('/\R\s*\R/', trim((string) $this->body)) ?: [];

        return array_values(array_filter(array_map('trim', $paragraphs), fn (string $paragraph) => $paragraph !== ''));
    }

    public function readingMinutes(): int
    {
        $words = str_word_count(strip_tags((string) $this->body.' '.$this->excerpt));

        return max(1, (int) ceil($words / self::WORDS_PER_MINUTE));
    }
}
