<?php

namespace App\Services;

use Carbon\CarbonImmutable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use UnexpectedValueException;

/**
 * Jadwal sholat harian Sidoarjo dari API myquran (di-cache per hari selama 1 jam).
 * Dipakai Beranda dan halaman Jadwal & Kegiatan.
 */
class PrayerSchedule
{
    private const CITY_ID = 'cfa0860e83a4c3a763a7e62d825349f7';

    private const PRAYER_NAMES = [
        'subuh' => 'Subuh',
        'terbit' => 'Terbit',
        'dzuhur' => 'Dzuhur',
        'ashar' => 'Ashar',
        'maghrib' => 'Maghrib',
        'isya' => 'Isya',
    ];

    /** Terbit bukan waktu sholat, jadi tidak dihitung sebagai "sholat berikutnya". */
    private const NEXT_PRAYER_KEYS = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];

    /**
     * @return array{
     *     schedule: array{date: string, location: string, times: array<string, string>}|null,
     *     prayers: list<array{key: string, name: string, time: string, is_next: bool}>,
     *     next: array{key: string, name: string, time: string}|null,
     * }
     */
    public function forDay(CarbonImmutable $now): array
    {
        $date = $now->toDateString();
        $schedule = $this->fetch($date);

        if ($schedule === null) {
            return ['schedule' => null, 'prayers' => [], 'next' => null];
        }

        $prayers = [];
        $next = null;

        foreach (self::PRAYER_NAMES as $key => $name) {
            $time = $schedule['times'][$key];
            $prayers[] = ['key' => $key, 'name' => $name, 'time' => $time, 'is_next' => false];

            if ($next === null
                && in_array($key, self::NEXT_PRAYER_KEYS, true)
                && CarbonImmutable::parse("{$date} {$time}", $now->getTimezone())->isAfter($now)) {
                $next = ['key' => $key, 'name' => $name, 'time' => $time];
            }
        }

        if ($next !== null) {
            foreach ($prayers as &$prayer) {
                $prayer['is_next'] = $prayer['key'] === $next['key'];
            }
            unset($prayer);
        }

        return ['schedule' => $schedule, 'prayers' => $prayers, 'next' => $next];
    }

    /**
     * @return array{date: string, location: string, times: array<string, string>}|null
     */
    private function fetch(string $date): ?array
    {
        try {
            return Cache::remember("prayer-schedule.sidoarjo.{$date}", now()->addHour(), function () use ($date) {
                $response = Http::acceptJson()
                    ->timeout(5)
                    ->get('https://api.myquran.com/v3/sholat/jadwal/'.self::CITY_ID."/{$date}")
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

                $timeKeys = array_keys(self::PRAYER_NAMES);
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
        } catch (ConnectionException|RequestException $exception) {
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

        return null;
    }
}
