<?php

namespace Database\Seeders;

use App\Enums\PublicationStatus;
use App\Enums\Weekday;
use App\Models\ActivityPage;
use App\Models\Agenda;
use App\Models\KitabSchedule;
use App\Models\RoutineActivity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ActivityPageSeeder extends Seeder
{
    public function run(): void
    {
        (ActivityPage::current() ?? new ActivityPage)->fill([
            'hero_title' => 'Ibadah dan kajian setiap pekan',
            'hero_description' => 'Temukan jadwal sholat, kegiatan rutin jamaah, dan informasi agenda Masjid Jami Nurul Haqq.',
            'routine_note' => 'Untuk perubahan kegiatan rutin, silakan hubungi pengurus masjid.',
            'kitab_note' => 'Hubungi pengurus untuk konfirmasi jadwal ngaji kitab terkini.',
        ])->save();

        $routines = [
            ['title' => 'Sholat fardhu berjamaah', 'schedule' => '5 waktu sehari', 'badge' => 'Setiap hari'],
            ['title' => 'Sholat Jumat', 'schedule' => 'Setiap Jumat', 'badge' => 'Jumat'],
            ['title' => 'Pengajian rutin', 'schedule' => 'Ahad · 19.30 WIB', 'badge' => 'Ahad'],
            ['title' => 'Kajian Islam', 'schedule' => 'Rabu · 19.30 WIB', 'badge' => 'Rabu'],
        ];

        $sortOrder = 1;

        foreach ($routines as $routine) {
            RoutineActivity::query()->updateOrCreate(
                ['title' => $routine['title']],
                [
                    'schedule' => $routine['schedule'],
                    'badge' => $routine['badge'],
                    'show_on_home' => true,
                    'sort_order' => $sortOrder++,
                ],
            );
        }

        $kitabSchedules = [
            [
                'title' => 'Safinatun Najah',
                'speaker' => 'Ust. Ahmad Fauzi',
                'day' => Weekday::Sabtu,
                'time' => '18.00 WIB (ba’da Maghrib)',
            ],
            [
                'title' => 'Riyadhus Shalihin',
                'speaker' => 'Ust. Muhammad Ridwan',
                'day' => Weekday::Selasa,
                'time' => '19.30 WIB',
            ],
        ];

        $sortOrder = 1;

        foreach ($kitabSchedules as $schedule) {
            KitabSchedule::query()->updateOrCreate(
                ['title' => $schedule['title'], 'day' => $schedule['day']],
                [
                    'speaker' => $schedule['speaker'],
                    'time' => $schedule['time'],
                    'location' => 'Ruang utama masjid',
                    'sort_order' => $sortOrder++,
                ],
            );
        }

        $agendas = [
            [
                'title' => 'Pengajian Akbar',
                'speaker' => 'Ust. Abdullah',
                'date' => '2026-10-18',
                'time' => '19.30 WIB – selesai',
            ],
            [
                'title' => 'Peringatan Maulid Nabi',
                'speaker' => 'Panitia Maulid 1448 H',
                'date' => '2026-10-25',
                'time' => '08.00 WIB – selesai',
            ],
        ];

        foreach ($agendas as $agenda) {
            Agenda::query()->updateOrCreate(
                ['slug' => Str::slug($agenda['title'])],
                [
                    'title' => $agenda['title'],
                    'speaker' => $agenda['speaker'],
                    'date' => $agenda['date'],
                    'time' => $agenda['time'],
                    'location' => 'Halaman masjid',
                    'status' => PublicationStatus::Published,
                ],
            );
        }
    }
}
