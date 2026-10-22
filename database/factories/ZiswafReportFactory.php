<?php

namespace Database\Factories;

use App\Enums\PublicationStatus;
use App\Models\ZiswafReport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ZiswafReport>
 */
class ZiswafReportFactory extends Factory
{
    protected static int $monthOffset = 0;

    public function definition(): array
    {
        $period = now()->startOfMonth()->addMonths(self::$monthOffset);
        self::$monthOffset++;

        return [
            'period' => $period->toDateString(),
            'zakat' => 0,
            'infaq' => 0,
            'sedekah' => 0,
            'wakaf' => 0,
            'disbursed' => 0,
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
