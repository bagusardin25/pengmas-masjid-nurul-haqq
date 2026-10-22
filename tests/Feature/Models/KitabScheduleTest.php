<?php

namespace Tests\Feature\Models;

use App\Enums\KitabDay;
use App\Models\KitabSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KitabScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_day_is_read_as_an_enum(): void
    {
        $schedule = KitabSchedule::factory()->create(['day' => KitabDay::Sabtu]);

        $this->assertSame(KitabDay::Sabtu, $schedule->fresh()->day);
        $this->assertSame('Sabtu', $schedule->day->label());
    }
}
