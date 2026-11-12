<?php

namespace App\Http\Controllers;

use App\Enums\PublicationStatus;
use App\Models\Agenda;
use Carbon\CarbonImmutable;
use Illuminate\Http\Response;

/**
 * Berkas kalender (.ics) untuk satu agenda, dipakai tombol "Simpan ke kalender" di Beranda.
 *
 * Waktu agenda ditulis bebas (mis. "Waktu Dzuhur"), jadi acara dibuat sepanjang hari
 * dan keterangan waktunya dimasukkan ke deskripsi.
 */
class AgendaCalendarController extends Controller
{
    public function __invoke(Agenda $agenda): Response
    {
        abort_unless($agenda->status === PublicationStatus::Published, 404);

        $date = CarbonImmutable::parse($agenda->date->toDateString());
        $summary = $agenda->title.($agenda->speaker ? ' — '.$agenda->speaker : '');
        $description = implode("\n", array_filter([
            $agenda->time,
            $agenda->speaker ? 'Khotib / pengisi: '.$agenda->speaker : null,
            $agenda->description,
        ]));

        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//Masjid Jami Nurul Haqq//Agenda//ID',
            'CALSCALE:GREGORIAN',
            'METHOD:PUBLISH',
            'BEGIN:VEVENT',
            'UID:agenda-'.$agenda->id.'@'.request()->getHost(),
            'DTSTAMP:'.now('UTC')->format('Ymd\THis\Z'),
            'DTSTART;VALUE=DATE:'.$date->format('Ymd'),
            'DTEND;VALUE=DATE:'.$date->addDay()->format('Ymd'),
            'SUMMARY:'.$this->escape($summary),
        ];

        if ($description !== '') {
            $lines[] = 'DESCRIPTION:'.$this->escape($description);
        }

        if ($agenda->location) {
            $lines[] = 'LOCATION:'.$this->escape($agenda->location);
        }

        $lines[] = 'URL:'.route('jadwal');
        $lines[] = 'END:VEVENT';
        $lines[] = 'END:VCALENDAR';

        $body = implode("\r\n", array_map($this->fold(...), $lines))."\r\n";

        return response($body, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="agenda-'.$agenda->slug.'.ics"',
        ]);
    }

    /**
     * Escape teks sesuai RFC 5545 (\\, ;, , dan baris baru).
     */
    private function escape(string $text): string
    {
        return str_replace(['\\', ';', ',', "\r\n", "\n"], ['\\\\', '\;', '\,', '\n', '\n'], $text);
    }

    /**
     * Lipat baris panjang menjadi maksimal 75 byte per baris (RFC 5545), tanpa memotong karakter UTF-8.
     */
    private function fold(string $line): string
    {
        $parts = [];

        while (strlen($line) > 75) {
            $chunk = mb_strcut($line, 0, 75, 'UTF-8');
            $parts[] = $chunk;
            $line = ' '.substr($line, strlen($chunk));
        }

        $parts[] = $line;

        return implode("\r\n", $parts);
    }
}
