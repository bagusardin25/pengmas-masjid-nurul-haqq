<?php

namespace App\Http\Controllers;

use App\Models\ActivityPage;
use App\Models\Agenda;
use App\Models\KitabSchedule;
use App\Models\RoutineActivity;
use App\Services\PrayerSchedule;
use Carbon\CarbonImmutable;

class JadwalController extends Controller
{
    public function index(PrayerSchedule $prayerSchedule)
    {
        $today = CarbonImmutable::now('Asia/Jakarta');

        [
            'schedule' => $schedule,
            'prayers' => $prayers,
            'next' => $nextPrayer,
        ] = $prayerSchedule->forDay($today);

        return view('pages.jadwal', [
            'page' => ActivityPage::current(),
            'prayerSchedule' => $schedule,
            'prayers' => $prayers,
            'nextPrayer' => $nextPrayer,
            'today' => $today,
            'routines' => RoutineActivity::query()->ordered()->get(),
            'kitabSchedules' => KitabSchedule::query()->ordered()->get(),
            'agendas' => Agenda::query()
                ->published()
                ->whereDate('date', '>=', $today->toDateString())
                ->orderBy('date')
                ->orderBy('id')
                ->limit(5)
                ->get(),
        ]);
    }
}
