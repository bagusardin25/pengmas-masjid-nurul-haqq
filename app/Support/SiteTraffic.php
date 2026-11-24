<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiteTraffic
{
    /** @var array{online: int, today_visitors: int, today_hits: int, month_hits: int, total_hits: int, series: list<array{date: string, label: string, hits: int, visitors: int}>}|null */
    private ?array $cached = null;

    public function record(Request $request): void
    {
        if (! $this->shouldTrack($request)) {
            return;
        }

        $now = now();
        $today = $now->toDateString();
        $hash = hash('sha256', ($request->ip() ?? '0.0.0.0').'|'.substr((string) $request->userAgent(), 0, 200));

        $updated = DB::table('site_visits')
            ->where('visit_date', $today)
            ->where('visitor_hash', $hash)
            ->update([
                'hits' => DB::raw('hits + 1'),
                'last_seen_at' => $now,
                'updated_at' => $now,
            ]);

        if ($updated === 0) {
            DB::table('site_visits')->insertOrIgnore([
                'visit_date' => $today,
                'visitor_hash' => $hash,
                'hits' => 1,
                'last_seen_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $this->cached = null;
    }

    /**
     * @return array{online: int, today_visitors: int, today_hits: int, month_hits: int, total_hits: int, series: list<array{date: string, label: string, hits: int, visitors: int}>}
     */
    public function stats(): array
    {
        if ($this->cached !== null) {
            return $this->cached;
        }

        $now = now();
        $today = $now->toDateString();
        $monthStart = $now->startOfMonth()->toDateString();
        $seriesStart = $now->subDays(6)->toDateString();
        $onlineCutoff = $now->subMinutes(15);

        $dailyRows = DB::table('site_visits')
            ->where('visit_date', '>=', $seriesStart)
            ->groupBy('visit_date')
            ->selectRaw('visit_date, SUM(hits) as hits, COUNT(*) as visitors')
            ->get()
            ->keyBy('visit_date');

        $series = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = $now->subDays($i);
            $key = $day->toDateString();
            $row = $dailyRows->get($key);
            $series[] = [
                'date' => $key,
                'label' => $day->locale('id')->translatedFormat('j M'),
                'hits' => (int) ($row->hits ?? 0),
                'visitors' => (int) ($row->visitors ?? 0),
            ];
        }

        return $this->cached = [
            'online' => (int) DB::table('site_visits')->where('last_seen_at', '>=', $onlineCutoff)->count(),
            'today_visitors' => (int) DB::table('site_visits')->where('visit_date', $today)->count(),
            'today_hits' => (int) DB::table('site_visits')->where('visit_date', $today)->sum('hits'),
            'month_hits' => (int) DB::table('site_visits')->where('visit_date', '>=', $monthStart)->sum('hits'),
            'total_hits' => (int) DB::table('site_visits')->sum('hits'),
            'series' => $series,
        ];
    }

    private function shouldTrack(Request $request): bool
    {
        if (! $request->isMethod('GET') || $request->expectsJson() || $request->filled('partial')) {
            return false;
        }

        if ($request->header('Purpose') === 'prefetch' || $request->header('Sec-Purpose') === 'prefetch') {
            return false;
        }

        return ! $request->is(
            'dashboard*',
            'login',
            'logout',
            'confirm-password',
            'verify-email*',
            'up',
            'robots.txt',
            'sitemap.xml',
            'agenda/*/kalender.ics',
        );
    }
}
