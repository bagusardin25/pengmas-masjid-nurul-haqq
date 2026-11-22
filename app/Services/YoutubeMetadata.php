<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

/**
 * Mengambil judul dan durasi video YouTube agar admin cukup menempel tautannya.
 *
 * Judul diambil dari oEmbed resmi YouTube (tanpa API key). Durasi diambil dari YouTube Data API
 * bila YOUTUBE_API_KEY diisi, selain itu dari halaman video. Bila YouTube tidak bisa dihubungi,
 * nilainya null dan admin mengisinya sendiri.
 */
class YoutubeMetadata
{
    private const TIMEOUT_SECONDS = 6;

    /**
     * @return array{title: ?string, duration: ?string}
     */
    public function fetch(string $youtubeId): array
    {
        $key = 'youtube-metadata:'.$youtubeId;
        $cached = Cache::get($key);

        if (is_array($cached)) {
            return $cached;
        }

        $metadata = [
            'title' => $this->title($youtubeId),
            'duration' => $this->duration($youtubeId),
        ];

        // Hanya hasil yang berhasil disimpan, agar kegagalan sesaat (mis. YouTube lambat) bisa dicoba lagi.
        if ($metadata['title'] !== null) {
            Cache::put($key, $metadata, now()->addDay());
        }

        return $metadata;
    }

    private function title(string $youtubeId): ?string
    {
        try {
            $response = Http::timeout(self::TIMEOUT_SECONDS)->get('https://www.youtube.com/oembed', [
                'url' => 'https://www.youtube.com/watch?v='.$youtubeId,
                'format' => 'json',
            ]);
        } catch (Throwable) {
            return null;
        }

        $title = $response->successful() ? trim((string) $response->json('title')) : '';

        return $title !== '' ? mb_substr($title, 0, 255) : null;
    }

    private function duration(string $youtubeId): ?string
    {
        $seconds = filled(config('services.youtube.key'))
            ? $this->durationFromApi($youtubeId)
            : $this->durationFromWatchPage($youtubeId);

        return $seconds !== null && $seconds > 0 ? self::formatDuration($seconds) : null;
    }

    private function durationFromApi(string $youtubeId): ?int
    {
        try {
            $response = Http::timeout(self::TIMEOUT_SECONDS)->get('https://www.googleapis.com/youtube/v3/videos', [
                'part' => 'contentDetails',
                'id' => $youtubeId,
                'key' => config('services.youtube.key'),
            ]);
        } catch (Throwable) {
            return null;
        }

        return self::parseIsoDuration((string) $response->json('items.0.contentDetails.duration'));
    }

    private function durationFromWatchPage(string $youtubeId): ?int
    {
        try {
            $response = Http::timeout(self::TIMEOUT_SECONDS)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (compatible; MasjidNurulHaqqBot/1.0)',
                    'Accept-Language' => 'id,en;q=0.8',
                    // Lewati halaman persetujuan cookie yang muncul untuk server di Eropa.
                    'Cookie' => 'CONSENT=YES+1',
                ])
                ->get('https://www.youtube.com/watch', ['v' => $youtubeId]);
        } catch (Throwable) {
            return null;
        }

        if (! $response->successful()) {
            return null;
        }

        $html = $response->body();

        if (preg_match('/"lengthSeconds":"(\d+)"/', $html, $matches) === 1) {
            return (int) $matches[1];
        }

        if (preg_match('/itemprop="duration" content="([^"]+)"/', $html, $matches) === 1) {
            return self::parseIsoDuration($matches[1]);
        }

        return null;
    }

    /**
     * "PT1H5M30S" → 3930 detik.
     */
    public static function parseIsoDuration(string $value): ?int
    {
        if (preg_match('/^PT(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?$/', $value, $matches) !== 1) {
            return null;
        }

        return ((int) ($matches[1] ?? 0)) * 3600 + ((int) ($matches[2] ?? 0)) * 60 + ((int) ($matches[3] ?? 0));
    }

    /**
     * 213 → "3:33", 3930 → "1:05:30" (seperti tampilan durasi di YouTube).
     */
    public static function formatDuration(int $seconds): string
    {
        $hours = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);
        $rest = $seconds % 60;

        return $hours > 0
            ? sprintf('%d:%02d:%02d', $hours, $minutes, $rest)
            : sprintf('%d:%02d', $minutes, $rest);
    }
}
