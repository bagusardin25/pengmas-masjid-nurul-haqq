<?php

namespace Database\Factories;

use App\Enums\PublicationStatus;
use App\Enums\ReportCategory;
use App\Models\Media;
use App\Models\ZiswafReport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ZiswafReport>
 */
class ZiswafReportFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => 'Laporan kas pekan ke-1',
            'category' => ReportCategory::Weekly,
            'report_date' => now()->toDateString(),
            'description' => null,
            'report_file_id' => Media::factory()->state(fn () => [
                'path' => 'laporan/'.fake()->unique()->uuid().'.pdf',
                'original_name' => 'laporan.pdf',
                'alt' => null,
                'mime_type' => 'application/pdf',
                'width' => null,
                'height' => null,
            ]),
            'status' => PublicationStatus::Draft,
        ];
    }

    public function published(): static
    {
        return $this->state(fn () => [
            'status' => PublicationStatus::Published,
        ]);
    }
}
