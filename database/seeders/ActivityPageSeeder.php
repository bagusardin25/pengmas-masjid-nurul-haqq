<?php

namespace Database\Seeders;

use App\Enums\PublicationStatus;
use App\Enums\Weekday;
use App\Models\ActivityPage;
use App\Models\Agenda;
use App\Models\KitabSchedule;
use App\Models\RoutineActivity;
use Illuminate\Database\Seeder;

/**
 * Data jadwal & kegiatan asli Masjid Nurul Haqq.
 *
 * Sumber: "JADWAL KHOTIB IMAM SHOLAT JUMAT MNH 2026.pdf" (Takmir MNH, 4 Desember 2025)
 * dan daftar kegiatan rutin yang sudah dipakai di Beranda.
 */
class ActivityPageSeeder extends Seeder
{
    /**
     * Khotib/imam sholat Jumat 2026. Gelar akademik ditulis seragam (mis. "M.Pd").
     *
     * @var array<string, string>
     */
    private const KHOTIB_JUMAT_2026 = [
        '2026-01-02' => 'Ust. Nor Habib Ahmad',
        '2026-01-09' => 'Ust. Rimun, M.Ag',
        '2026-01-16' => 'Ust. Dr. M. Syaikhon, SHL. MHL',
        '2026-01-23' => 'Ust. H. M. Ghofirin, S.Pd, M.Pd',
        '2026-01-30' => 'Ust. Fuad Mustofa, S.Pd',
        '2026-02-06' => 'Ust. Nor Habib Ahmad',
        '2026-02-13' => 'Ust. Drs. H. Ainur Rofiq',
        '2026-02-20' => 'Ust. Rimun, M.Ag',
        '2026-02-27' => 'Ust. H. M. Ghofirin, S.Pd, M.Pd',
        '2026-03-06' => 'Ust. Dr. Achmad Yasin Muchtar, M.Ag',
        '2026-03-13' => 'Ust. Nor Habib Ahmad',
        '2026-03-20' => 'Ust. M. Syarif, M.Pd',
        '2026-03-27' => 'Ust. Achmad Muaidi Jakfar',
        '2026-04-03' => 'Ust. Fuad Mustofa, S.Pd',
        '2026-04-10' => 'Ust. Kholil Anwar, SH',
        '2026-04-17' => 'Ust. Adib Amrulloh Al Chafidz',
        '2026-04-24' => 'Ust. H. M. Ghofirin, S.Pd, M.Pd',
        '2026-05-01' => 'Ust. Dr. Achmad Yasin Muchtar, M.Ag',
        '2026-05-08' => 'Ust. H. M. Ghofirin, S.Pd, M.Pd',
        '2026-05-15' => 'Ust. Muzamil, M.Pd',
        '2026-05-22' => 'Ust. Kholil Anwar, SH',
        '2026-05-29' => 'Ust. Rimun, M.Ag',
        '2026-06-05' => 'Ust. H. M. Ghofirin, S.Pd, M.Pd',
        '2026-06-12' => 'Ust. Fuad Mustofa, S.Pd',
        '2026-06-19' => 'Ust. Kholil Anwar, SH',
        '2026-06-26' => 'Ust. Adib Amrulloh Al Chafidz',
        '2026-07-03' => 'Ust. Dr. M. Syaikhon, SHL. MHL',
        '2026-07-10' => 'Ust. Abdul Rosid Achmad, S.Pd',
        '2026-07-17' => 'Ust. H. M. Ghofirin, S.Pd, M.Pd',
        '2026-07-24' => 'Ust. Dr. Achmad Yasin Muchtar, M.Ag',
        '2026-07-31' => 'Ust. Adib Amrulloh Al Chafidz',
        '2026-08-07' => 'Ust. Fuad Mustofa, S.Pd',
        '2026-08-14' => 'Ust. Khusnul Yaqin Noor',
        '2026-08-21' => 'Ust. Abdul Rosid Achmad, S.Pd',
        '2026-08-28' => 'Ust. Achmad Muaidi Jakfar',
        '2026-09-04' => 'Ust. M. Syarif, M.Pd',
        '2026-09-11' => 'Ust. H. M. Ghofirin, S.Pd, M.Pd',
        '2026-09-18' => 'Ust. Abdul Rosid Achmad, S.Pd',
        '2026-09-25' => 'Ust. Dr. Achmad Yasin Muchtar, M.Ag',
        '2026-10-02' => 'Ust. Fuad Mustofa, S.Pd',
        '2026-10-09' => 'Ust. Adib Amrulloh Al Chafidz',
        '2026-10-16' => 'Ust. Abdul Rosid Achmad, S.Pd',
        '2026-10-23' => 'Ust. Drs. H. Ainur Rofiq',
        '2026-10-30' => 'Ust. Ibnu Abdillah, S.Ag',
        '2026-11-06' => 'Ust. Kholil Anwar, SH',
        '2026-11-13' => 'Ust. Muzamil, M.Pd',
        '2026-11-20' => 'Ust. Achmad Muaidi Jakfar',
        '2026-11-27' => 'Ust. Abdul Fattah',
        '2026-12-04' => 'Ust. H. M. Ghofirin, S.Pd, M.Pd',
        '2026-12-11' => 'Ust. Adib Amrulloh Al Chafidz',
        '2026-12-18' => 'Ust. Abdul Rosid Achmad, S.Pd',
        '2026-12-25' => 'Ust. Ibnu Abdillah, S.Ag',
    ];

    public function run(): void
    {
        (ActivityPage::current() ?? new ActivityPage)->fill([
            'hero_title' => 'Ibadah dan kajian setiap pekan',
            'hero_description' => 'Temukan jadwal sholat, kegiatan rutin jamaah, dan informasi agenda Masjid Jami Nurul Haqq.',
            'routine_note' => 'Untuk perubahan kegiatan rutin, silakan hubungi pengurus masjid.',
            'kitab_note' => 'Hubungi pengurus untuk konfirmasi jadwal ngaji kitab terkini.',
        ])->save();

        $routines = [
            ['title' => 'Sholat fardhu berjamaah', 'schedule' => 'Imam rawatib bergiliran', 'badge' => '5 waktu sehari'],
            ['title' => 'Sholat Jumat', 'schedule' => 'Khotib & imam terjadwal oleh takmir', 'badge' => 'Setiap Jumat'],
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
            ['title' => 'Ngaji Kitab Nashoihud Diniyyah', 'speaker' => 'KH. Ahmad Jazuli Sholeh, S.Ag', 'day' => Weekday::Sabtu, 'time' => "Pekan ke-1 & 3 · Ba'da Subuh"],
            ['title' => 'Ngaji Kitab Nashoihud Diniyyah', 'speaker' => 'KH. Ahmad Jazuli Sholeh, S.Ag', 'day' => Weekday::Rabu, 'time' => "Pekan ke-2 & 4 · Ba'da Isya'"],
            ['title' => 'Ngaji Kitab Irsyadul Ibad', 'speaker' => 'Ust. Ainur Rofiq Muslimin', 'day' => Weekday::Ahad, 'time' => "Ba'da Maghrib"],
            ['title' => 'Ngaji Kitab Riyadush Shalihin', 'speaker' => 'Ust. H. M. Ghofirin, S.Pd, M.Pd', 'day' => Weekday::Sabtu, 'time' => "Pekan ke-2 & 4 · Ba'da Subuh"],
            ['title' => "Ngaji Al-Qur'an Sak Maknane", 'speaker' => 'Ust. Rachmad', 'day' => Weekday::Selasa, 'time' => "Ba'da Isya'"],
            ['title' => 'Ngaji Kitab Tajwid Hidayatush Shibyan', 'speaker' => 'Ust. M. Faishal Riza, S.HI', 'day' => Weekday::Kamis, 'time' => "Ba'da Isya'"],
        ];

        $sortOrder = 1;

        foreach ($kitabSchedules as $schedule) {
            KitabSchedule::query()->updateOrCreate(
                ['title' => $schedule['title'], 'day' => $schedule['day']],
                [
                    'speaker' => $schedule['speaker'],
                    'time' => $schedule['time'],
                    'sort_order' => $sortOrder++,
                ],
            );
        }

        // Sholat Jumat disimpan sebagai agenda agar tampil di "Agenda terdekat" Beranda dan halaman Jadwal.
        foreach (self::KHOTIB_JUMAT_2026 as $date => $khotib) {
            $agenda = Agenda::query()
                ->where('title', 'Sholat Jumat')
                ->whereDate('date', $date)
                ->first() ?? new Agenda(['slug' => 'sholat-jumat-'.$date]);

            $agenda->fill([
                'title' => 'Sholat Jumat',
                'speaker' => $khotib,
                'date' => $date,
                'time' => 'Waktu Dzuhur',
                'location' => 'Masjid Nurul Haqq',
                'status' => PublicationStatus::Published,
            ])->save();
        }
    }
}
