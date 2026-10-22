<?php

namespace App\Models;

use App\Models\Concerns\HasPublicationStatus;
use Database\Factories\ZiswafReportFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ZiswafReport extends Model
{
    /** @use HasFactory<ZiswafReportFactory> */
    use HasFactory, HasPublicationStatus;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'period',
        'zakat',
        'infaq',
        'sedekah',
        'wakaf',
        'disbursed',
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
            'period' => 'date',
            'zakat' => 'integer',
            'infaq' => 'integer',
            'sedekah' => 'integer',
            'wakaf' => 'integer',
            'disbursed' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (ZiswafReport $report): void {
            $period = $report->period;

            if ($period === null) {
                return;
            }

            $report->period = $period->copy()->startOfMonth()->toDateString();
        });
    }

    public function reportFile(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'report_file_id');
    }

    protected function collected(): Attribute
    {
        return Attribute::get(fn (): int => $this->zakat + $this->infaq + $this->sedekah + $this->wakaf);
    }

    protected function balance(): Attribute
    {
        return Attribute::get(fn (): int => $this->collected - $this->disbursed);
    }

    public function periodLabel(): string
    {
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return $months[$this->period->month].' '.$this->period->year;
    }
}
