<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\ContactSetting;
use App\Models\KitabSchedule;
use App\Models\RoutineActivity;
use App\Models\ZiswafFund;
use App\Models\ZiswafReport;
use App\Services\PrayerSchedule;
use Carbon\CarbonImmutable;

class TvDisplayController extends Controller
{
    public function __invoke(PrayerSchedule $prayerSchedule)
    {
        $now = CarbonImmutable::now('Asia/Jakarta');
        $prayerData = $prayerSchedule->forDay($now);

        return view('pages.tv-display', [
            'today' => $now,
            'todayLabel' => $now->locale('id')->translatedFormat('l, j F Y'),
            'prayerSchedule' => $prayerData['schedule'],
            'prayers' => array_values(array_filter(
                $prayerData['prayers'],
                fn (array $prayer): bool => $prayer['key'] !== 'terbit',
            )),
            'nextPrayer' => $prayerData['next'],
            'contact' => ContactSetting::query()->first(),
            'routines' => RoutineActivity::query()
                ->where('show_on_home', true)
                ->ordered()
                ->limit(4)
                ->get(),
            'kitabSchedules' => KitabSchedule::query()
                ->ordered()
                ->limit(3)
                ->get(),
            'agendas' => Agenda::query()
                ->published()
                ->whereDate('date', '>=', $now->toDateString())
                ->orderBy('date')
                ->orderBy('id')
                ->limit(2)
                ->get(),
            'ziswafFunds' => ZiswafFund::query()
                ->where('is_active', true)
                ->ordered()
                ->limit(4)
                ->get(),
            'latestReports' => ZiswafReport::query()
                ->published()
                ->latestFirst()
                ->limit(2)
                ->get(),
        ]);
    }
}
