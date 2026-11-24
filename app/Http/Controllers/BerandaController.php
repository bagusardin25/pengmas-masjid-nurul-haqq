<?php

namespace App\Http\Controllers;

use App\Models\AboutPage;
use App\Models\AboutStat;
use App\Models\Agenda;
use App\Models\ContactPhone;
use App\Models\ContactSetting;
use App\Models\Facility;
use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Models\HomePage;
use App\Models\KitabSchedule;
use App\Models\RoutineActivity;
use App\Models\TpqPage;
use App\Models\TpqPillar;
use App\Models\ZiswafContact;
use App\Models\ZiswafFund;
use App\Models\ZiswafPage;
use App\Models\ZiswafReport;
use App\Services\PrayerSchedule;
use Carbon\CarbonImmutable;

class BerandaController extends Controller
{
    public function index(PrayerSchedule $prayerSchedule)
    {
        $hero = HomePage::query()->with('heroImage')->first();
        $contact = ContactSetting::first();
        $phones = ContactPhone::query()->ordered()->get();
        $about = AboutPage::first();
        $aboutStats = AboutStat::query()->ordered()->get();
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
            ->limit(3)
            ->get();

        $tpq = TpqPage::current();
        $tpqPillars = TpqPillar::query()->ordered()->get();

        $ziswaf = ZiswafPage::query()->with('qrisImage')->first();
        $ziswafFunds = ZiswafFund::query()->where('is_active', true)->ordered()->get();
        $ziswafContacts = ZiswafContact::query()->ordered()->get();
        $latestReports = ZiswafReport::query()->published()->with('reportFile')->latestFirst()->limit(3)->get();

        $galleryAlbums = GalleryAlbum::query()->published()->has('photos')->withCardData()->latestFirst()->limit(4)->get();
        $galleryPhotoTotal = GalleryPhoto::query()->whereHas('album', fn ($album) => $album->published())->count();

        return view('pages.beranda', compact(
            'hero',
            'today',
            'prayerSchedule',
            'prayers',
            'nextPrayer',
            'contact',
            'phones',
            'about',
            'aboutStats',
            'facilities',
            'routines',
            'kitabGroups',
            'upcomingAgendas',
            'tpq',
            'tpqPillars',
            'ziswaf',
            'ziswafFunds',
            'ziswafContacts',
            'latestReports',
            'galleryAlbums',
            'galleryPhotoTotal',
        ));
    }
}
