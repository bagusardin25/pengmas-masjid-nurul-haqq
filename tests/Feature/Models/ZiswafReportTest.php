<?php

namespace Tests\Feature\Models;

use App\Models\ZiswafReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ZiswafReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_period_starts_on_the_first_and_balance_is_calculated(): void
    {
        $report = ZiswafReport::factory()->create([
            'period' => '2026-05-18',
            'zakat' => 10,
            'infaq' => 20,
            'sedekah' => 30,
            'wakaf' => 40,
            'disbursed' => 15,
        ]);

        $this->assertSame('2026-05-01', $report->period->toDateString());
        $this->assertSame(100, $report->collected);
        $this->assertSame(85, $report->balance);
        $this->assertSame('Mei 2026', $report->period_label);
    }

    public function test_balance_may_be_negative(): void
    {
        $report = ZiswafReport::factory()->create([
            'period' => '2026-06-02',
            'zakat' => 10,
            'disbursed' => 25,
        ]);

        $this->assertSame(-15, $report->balance);
    }
}
