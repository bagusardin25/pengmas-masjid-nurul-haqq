<?php

namespace App\Models;

use App\Enums\ReportCategory;
use App\Models\Concerns\HasPublicationStatus;
use Database\Factories\ZiswafReportFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Laporan keuangan masjid berupa dokumen PDF: rutin mingguan, per kegiatan, atau pembangunan.
 */
class ZiswafReport extends Model
{
    /** @use HasFactory<ZiswafReportFactory> */
    use HasFactory, HasPublicationStatus;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'category',
        'report_date',
        'description',
        'report_file_id',
        'status',
        'published_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'category' => ReportCategory::class,
            'report_date' => 'date',
        ];
    }

    public function reportFile(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'report_file_id');
    }

    /**
     * Laporan terbaru di atas.
     */
    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->orderByDesc('report_date')->orderByDesc('id');
    }

    protected function dateLabel(): Attribute
    {
        return Attribute::get(fn (): string => $this->report_date->copy()->locale('id')->translatedFormat('j F Y'));
    }
}
