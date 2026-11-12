<?php

use App\Enums\PublicationStatus;
use App\Models\Agenda;

test('a published agenda can be downloaded as an all-day calendar event', function () {
    $agenda = Agenda::factory()->create([
        'title' => 'Sholat Jumat',
        'slug' => 'sholat-jumat-2026-11-20',
        'speaker' => 'Ust. Achmad Muaidi Jakfar',
        'date' => '2026-11-20',
        'time' => 'Waktu Dzuhur',
        'location' => 'Masjid Nurul Haqq',
        'status' => PublicationStatus::Published,
    ]);

    $response = $this->get(route('agenda.calendar', $agenda))->assertOk();

    expect($response->headers->get('Content-Type'))->toStartWith('text/calendar')
        ->and($response->headers->get('Content-Disposition'))->toBe('attachment; filename="agenda-sholat-jumat-2026-11-20.ics"');

    $body = $response->getContent();

    expect($body)->toStartWith("BEGIN:VCALENDAR\r\n")
        ->toEndWith("END:VCALENDAR\r\n")
        ->toContain("DTSTART;VALUE=DATE:20261120\r\n")
        ->toContain("DTEND;VALUE=DATE:20261121\r\n")
        ->toContain('SUMMARY:Sholat Jumat — Ust. Achmad Muaidi Jakfar')
        ->toContain("LOCATION:Masjid Nurul Haqq\r\n")
        ->toContain('DESCRIPTION:Waktu Dzuhur\nKhotib / pengisi: Ust. Achmad Muaidi Jakfar');
});

test('calendar text is escaped and long lines are folded', function () {
    $agenda = Agenda::factory()->create([
        'title' => 'Pengajian',
        'date' => '2026-12-01',
        'location' => 'Masjid, Lantai 1; Ruang utama',
        'description' => str_repeat('Keterangan panjang agenda masjid. ', 10),
        'status' => PublicationStatus::Published,
    ]);

    $body = $this->get(route('agenda.calendar', $agenda))->assertOk()->getContent();

    expect($body)->toContain('LOCATION:Masjid\, Lantai 1\; Ruang utama');

    foreach (explode("\r\n", $body) as $line) {
        expect(strlen($line))->toBeLessThanOrEqual(75);
    }
});

test('draft or unknown agendas cannot be downloaded', function () {
    $draft = Agenda::factory()->create(['status' => PublicationStatus::Draft]);

    $this->get(route('agenda.calendar', $draft))->assertNotFound();
    $this->get('/agenda/tidak-ada/kalender.ics')->assertNotFound();
});
