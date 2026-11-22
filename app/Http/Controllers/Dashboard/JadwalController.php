<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\PublicationStatus;
use App\Enums\Weekday;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Dashboard\Concerns\IndonesianValidationMessages;
use App\Models\ActivityPage;
use App\Models\Agenda;
use App\Models\KitabSchedule;
use App\Models\RoutineActivity;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JadwalController extends Controller
{
    use IndonesianValidationMessages;

    public function edit(): View
    {
        $today = CarbonImmutable::now('Asia/Jakarta')->toDateString();

        return view('dashboard.jadwal', [
            'page' => ActivityPage::current(),
            'routines' => RoutineActivity::query()->ordered()->get(),
            'kitabSchedules' => KitabSchedule::query()->ordered()->get(),
            'upcomingAgendas' => Agenda::query()
                ->whereDate('date', '>=', $today)
                ->orderBy('date')
                ->orderBy('id')
                ->get(),
            'pastAgendas' => Agenda::query()
                ->whereDate('date', '<', $today)
                ->orderByDesc('date')
                ->orderByDesc('id')
                ->get(),
            'weekdays' => Weekday::cases(),
            'statuses' => PublicationStatus::cases(),
            'suggestedFriday' => $this->suggestedFriday(),
        ]);
    }

    /**
     * Tanggal awal form tambah agenda: Jumat setelah jadwal Sholat Jumat terakhir,
     * atau Jumat terdekat bila jadwal terakhir sudah lewat.
     */
    private function suggestedFriday(): string
    {
        $nextFriday = CarbonImmutable::now('Asia/Jakarta')->startOfDay()->next(CarbonImmutable::FRIDAY);
        $lastJumat = Agenda::query()->where('title', 'Sholat Jumat')->max('date');

        if ($lastJumat === null) {
            return $nextFriday->toDateString();
        }

        $afterLast = CarbonImmutable::parse($lastJumat)->addWeek();

        return $afterLast->greaterThan($nextFriday) ? $afterLast->toDateString() : $nextFriday->toDateString();
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validateWithBag('activity', [
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_description' => ['nullable', 'string', 'max:1000'],
            'routine_note' => ['nullable', 'string', 'max:255'],
            'kitab_note' => ['nullable', 'string', 'max:255'],
        ], $this->validationMessages(), [
            'hero_title' => 'judul halaman',
            'hero_description' => 'deskripsi halaman',
            'routine_note' => 'catatan kegiatan rutin',
            'kitab_note' => 'catatan ngaji kitab',
        ]);

        (ActivityPage::current() ?? new ActivityPage)->fill($data)->save();

        return to_route('dashboard.jadwal')
            ->with('status', 'Teks halaman jadwal berhasil disimpan.');
    }
}
