<?php

namespace App\Http\Controllers;

use App\Models\AboutPage;
use App\Models\Agenda;
use App\Models\ContactPhone;
use App\Models\ContactSetting;
use App\Models\Facility;
use App\Models\HomePage;
use App\Models\KitabSchedule;
use App\Models\RoutineActivity;
use App\Services\PrayerSchedule;
use Carbon\CarbonImmutable;

class BerandaController extends Controller
{
    public function index(PrayerSchedule $prayerSchedule)
    {
        $hero = HomePage::first();
        $contact = ContactSetting::first();
        $phones = ContactPhone::all();
        $about = AboutPage::first();
        $facilities = Facility::query()->where('is_active', true)->ordered()->get();
        $today = CarbonImmutable::now('Asia/Jakarta');

        [
            'schedule' => $prayerSchedule,
            'prayers' => $prayers,
            'next' => $nextPrayer,
        ] = $prayerSchedule->forDay($today);

        $routines = RoutineActivity::query()->where('show_on_home', true)->ordered()->get();

        // Satu kitab bisa punya beberapa jadwal (mis. Sabtu dan Rabu), tampil sebagai satu baris.
        $kitabGroups = KitabSchedule::query()->ordered()->get()
            ->groupBy(fn (KitabSchedule $kitab) => $kitab->title.'|'.$kitab->speaker)
            ->values();

        $upcomingAgendas = Agenda::query()
            ->published()
            ->whereDate('date', '>=', $today->toDateString())
            ->orderBy('date')
            ->orderBy('id')
            ->limit(2)
            ->get();

        return view('pages.beranda', compact(
            'hero',
            'today',
            'prayerSchedule',
            'prayers',
            'nextPrayer',
            'contact',
            'phones',
            'about',
            'facilities',
            'routines',
            'kitabGroups',
            'upcomingAgendas',
        ));
    }
}
