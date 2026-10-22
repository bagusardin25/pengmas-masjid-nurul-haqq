<?php

namespace App\Models;

use Database\Factories\MediaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Media extends Model
{
    /** @use HasFactory<MediaFactory> */
    use HasFactory;

    protected $table = 'media';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'disk',
        'path',
        'original_name',
        'alt',
        'mime_type',
        'size',
        'width',
        'height',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'width' => 'integer',
            'height' => 'integer',
        ];
    }

    public function homePages(): HasMany
    {
        return $this->hasMany(HomePage::class, 'hero_image_id');
    }

    public function aboutPages(): HasMany
    {
        return $this->hasMany(AboutPage::class, 'hero_image_id');
    }

    public function agendaCovers(): HasMany
    {
        return $this->hasMany(Agenda::class, 'cover_image_id');
    }

    public function ziswafQrisSettings(): HasMany
    {
        return $this->hasMany(ZiswafSetting::class, 'qris_image_id');
    }

    public function ziswafReportFiles(): HasMany
    {
        return $this->hasMany(ZiswafReport::class, 'report_file_id');
    }

    public function albumCovers(): HasMany
    {
        return $this->hasMany(GalleryAlbum::class, 'cover_image_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(GalleryPhoto::class, 'image_id');
    }

    public function tpqDocumentations(): HasMany
    {
        return $this->hasMany(TpqDocumentation::class, 'image_id');
    }

    public function articleCovers(): HasMany
    {
        return $this->hasMany(Article::class, 'cover_image_id');
    }
}
