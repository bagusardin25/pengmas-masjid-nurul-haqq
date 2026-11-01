<?php

namespace App\Http\Controllers;

use App\Models\AboutPage;
use App\Models\ContactPhone;
use App\Models\ContactSetting;
use App\Models\Facility;
use App\Models\HomePage;
use Carbon\CarbonImmutable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use UnexpectedValueException;

class BerandaController extends Controller
{
    public function index()
    {
        $hero = HomePage::first();
        $contact = ContactSetting::first();
        $phones = ContactPhone::all();
        $about = AboutPage::first();
        $facilities = Facility::all();
        $today = CarbonImmutable::now('Asia/Jakarta');
        $date = $today->toDateString();
        $prayerSchedule = null;
        $prayers = [];
        $nextPrayer = null;

        try {
            $prayerSchedule = Cache::remember("prayer-schedule.sidoarjo.{$date}", now()->addHour(), function () use ($date) {
                $response = Http::acceptJson()
                    ->timeout(5)
                    ->get("https://api.myquran.com/v3/sholat/jadwal/cfa0860e83a4c3a763a7e62d825349f7/{$date}")
                    ->throw();

                $payload = $response->json();
                if (! is_array($payload) || ($payload['status'] ?? false) !== true) {
                    throw new UnexpectedValueException('The prayer schedule API returned an invalid response.');
                }

                $data = $payload['data'] ?? null;
                if (! is_array($data)) {
                    throw new UnexpectedValueException('The prayer schedule API returned an invalid response.');
                }

                $schedules = $data['jadwal'] ?? null;
                $daySchedule = is_array($schedules) ? ($schedules[$date] ?? null) : null;

                if (! is_array($daySchedule)) {
                    throw new UnexpectedValueException('The prayer schedule API returned an invalid response.');
                }

                $timeKeys = ['subuh', 'terbit', 'dzuhur', 'ashar', 'maghrib', 'isya'];
                foreach (['tanggal', ...$timeKeys] as $key) {
                    if (! isset($daySchedule[$key]) || ! is_string($daySchedule[$key])) {
                        throw new UnexpectedValueException("The prayer schedule API response is missing {$key}.");
                    }
                }

                foreach ($timeKeys as $key) {
                    if (! preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $daySchedule[$key])) {
                        throw new UnexpectedValueException("The prayer schedule API returned an invalid {$key} time.");
                    }
                }

                if (! isset($data['kabko']) || ! is_string($data['kabko'])) {
                    throw new UnexpectedValueException('The prayer schedule API response is missing the location.');
                }

                return [
                    'date' => $daySchedule['tanggal'],
                    'location' => $data['kabko'],
                    'times' => array_intersect_key($daySchedule, array_flip($timeKeys)),
                ];
            });
        } catch (ConnectionException | RequestException $exception) {
            Log::warning('Unable to fetch today\'s prayer schedule.', [
                'date' => $date,
                'exception' => $exception->getMessage(),
            ]);
        } catch (UnexpectedValueException $exception) {
            Log::error('The prayer schedule API returned invalid data.', [
                'date' => $date,
                'exception' => $exception->getMessage(),
            ]);
        }

        if ($prayerSchedule !== null) {
            $prayerNames = [
                'subuh' => 'Subuh',
                'terbit' => 'Terbit',
                'dzuhur' => 'Dzuhur',
                'ashar' => 'Ashar',
                'maghrib' => 'Maghrib',
                'isya' => 'Isya',
            ];
            $nextPrayerKeys = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];

            foreach ($prayerNames as $key => $name) {
                $time = $prayerSchedule['times'][$key];
                $prayers[] = [
                    'key' => $key,
                    'name' => $name,
                    'time' => $time,
                    'is_next' => false,
                ];

                if (in_array($key, $nextPrayerKeys, true)) {
                    $prayerAt = CarbonImmutable::parse("{$date} {$time}", 'Asia/Jakarta');

                    if ($nextPrayer === null && $prayerAt->isAfter($today)) {
                        $nextPrayer = ['key' => $key, 'name' => $name, 'time' => $time];
                    }
                }
            }

            if ($nextPrayer !== null) {
                foreach ($prayers as &$prayer) {
                    $prayer['is_next'] = $prayer['key'] === $nextPrayer['key'];
                }
                unset($prayer);
            }
        }

        return view('pages.beranda', compact('hero', 'today', 'prayerSchedule', 'prayers', 'nextPrayer', 'contact', 'phones', 'about', 'facilities'));
    }
}