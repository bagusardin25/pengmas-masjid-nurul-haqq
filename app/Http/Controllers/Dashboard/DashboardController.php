<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\ReportCategory;
use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use App\Models\Agenda;
use App\Models\Article;
use App\Models\ContactPhone;
use App\Models\ContactSetting;
use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Models\GalleryVideo;
use App\Models\HomePage;
use App\Models\KitabSchedule;
use App\Models\OrganizationMember;
use App\Models\RoutineActivity;
use App\Models\TpqDocumentation;
use App\Models\TpqPage;
use App\Models\ZiswafPage;
use App\Models\ZiswafReport;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /** Laporan mingguan dianggap terlambat bila laporan terakhir lebih lama dari ini (hari). */
    private const WEEKLY_REPORT_DAYS = 7;

    public function index(): View
    {
        $today = CarbonImmutable::now('Asia/Jakarta')->startOfDay();

        $upcomingAgendas = Agenda::query()
            ->published()
            ->whereDate('date', '>=', $today->toDateString())
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        $latestReports = ZiswafReport::query()->published()->latestFirst()->limit(4)->get();
        $latestWeekly = ZiswafReport::query()->published()->where('category', ReportCategory::Weekly->value)->latestFirst()->first();

        $stats = [
            [
                'label' => 'Agenda mendatang',
                'value' => $upcomingAgendas->count(),
                'note' => $upcomingAgendas->first()
                    ? 'Terdekat '.$upcomingAgendas->first()->date->locale('id')->translatedFormat('j M Y')
                    : 'Belum ada agenda terjadwal',
                'url' => route('dashboard.jadwal').'#agenda',
            ],
            [
                'label' => 'Laporan keuangan terbit',
                'value' => ZiswafReport::query()->published()->count(),
                'note' => $latestReports->first() ? 'Terakhir '.$latestReports->first()->date_label : 'Belum ada laporan',
                'url' => route('dashboard.ziswaf').'#laporan',
            ],
            [
                'label' => 'Album galeri terbit',
                'value' => GalleryAlbum::query()->published()->count(),
                'note' => GalleryPhoto::query()->count().' foto · '.GalleryVideo::query()->published()->count().' video',
                'url' => route('dashboard.galeri').'#album',
            ],
            [
                'label' => 'Artikel terbit',
                'value' => Article::query()->published()->count(),
                'note' => Article::query()->where('status', 'draft')->count().' masih draf',
                'url' => route('dashboard.galeri').'#artikel',
            ],
        ];

        return view('dashboard.index', [
            'today' => $today,
            'stats' => $stats,
            'checklist' => $this->checklist($today, $upcomingAgendas->isNotEmpty(), $latestWeekly),
            'pages' => $this->pages(),
            'upcomingAgendas' => $upcomingAgendas->take(4),
            'latestReports' => $latestReports,
        ]);
    }

    /**
     * Isian penting yang belum lengkap, agar pengurus tahu apa yang perlu dikerjakan.
     *
     * @return list<array{label: string, hint: string, done: bool, url: string}>
     */
    private function checklist(CarbonImmutable $today, bool $hasUpcomingAgenda, ?ZiswafReport $latestWeekly): array
    {
        $home = HomePage::current();
        $ziswaf = ZiswafPage::current();
        $contact = ContactSetting::current();
        $tpq = TpqPage::current();

        $weeklyAge = $latestWeekly ? (int) $latestWeekly->report_date->diffInDays($today) : null;

        return [
            [
                'label' => 'Foto utama Beranda',
                'hint' => 'Foto masjid di bagian paling atas Beranda.',
                'done' => $home?->hero_image_id !== null,
                'url' => route('dashboard.beranda'),
            ],
            [
                'label' => 'Rekening & QRIS donasi',
                'hint' => 'Nomor rekening dan gambar QRIS di halaman ZISWAF.',
                'done' => filled($ziswaf?->account_number) && $ziswaf?->qris_image_id !== null,
                'url' => route('dashboard.ziswaf').'#teks',
            ],
            [
                'label' => 'Laporan keuangan mingguan',
                'hint' => match (true) {
                    $weeklyAge === null => 'Belum ada laporan mingguan yang terbit.',
                    $weeklyAge > self::WEEKLY_REPORT_DAYS => 'Laporan mingguan terakhir '.$weeklyAge.' hari lalu.',
                    default => 'Laporan mingguan pekan ini sudah terbit.',
                },
                'done' => $weeklyAge !== null && $weeklyAge <= self::WEEKLY_REPORT_DAYS,
                'url' => route('dashboard.ziswaf').'#laporan',
            ],
            [
                'label' => 'Agenda mendatang',
                'hint' => 'Kegiatan terdekat yang tampil di Beranda dan halaman Jadwal.',
                'done' => $hasUpcomingAgenda,
                'url' => route('dashboard.jadwal').'#agenda',
            ],
            [
                'label' => 'Nomor WhatsApp pengurus',
                'hint' => 'Dipakai semua tombol “Hubungi pengurus” di situs.',
                'done' => ContactPhone::query()->where('is_whatsapp', true)->exists(),
                'url' => route('dashboard.kontak').'#telepon',
            ],
            [
                'label' => 'Email & peta lokasi',
                'hint' => 'Email takmir dan peta Google Maps di halaman Kontak.',
                'done' => filled($contact?->email) && filled($contact?->maps_embed_url),
                'url' => route('dashboard.kontak').'#info',
            ],
            [
                'label' => 'Album galeri',
                'hint' => 'Minimal satu album foto kegiatan yang terbit.',
                'done' => GalleryAlbum::query()->published()->has('photos')->exists(),
                'url' => route('dashboard.galeri').'#album',
            ],
            [
                'label' => 'Kontak pendaftaran TPQ',
                'hint' => 'Nomor WhatsApp untuk pendaftaran santri baru.',
                'done' => filled($tpq?->registration_phone),
                'url' => route('dashboard.tpq').'#teks',
            ],
        ];
    }

    /**
     * Halaman kelola beserta waktu terakhir isinya diubah.
     *
     * @return list<array{label: string, description: string, url: string, updated: ?CarbonImmutable}>
     */
    private function pages(): array
    {
        return [
            [
                'label' => 'Kelola Beranda',
                'description' => 'Judul, ayat, dan foto utama.',
                'url' => route('dashboard.beranda'),
                'updated' => $this->lastUpdated([HomePage::class]),
            ],
            [
                'label' => 'Profil Masjid',
                'description' => 'Sejarah, fasilitas, dan pengurus.',
                'url' => route('dashboard.profil'),
                'updated' => $this->lastUpdated([AboutPage::class, OrganizationMember::class]),
            ],
            [
                'label' => 'Jadwal & Kegiatan',
                'description' => 'Kegiatan rutin, ngaji kitab, dan agenda.',
                'url' => route('dashboard.jadwal'),
                'updated' => $this->lastUpdated([RoutineActivity::class, KitabSchedule::class, Agenda::class]),
            ],
            [
                'label' => 'Program TPQ',
                'description' => 'Informasi, pendaftaran, dan dokumentasi.',
                'url' => route('dashboard.tpq'),
                'updated' => $this->lastUpdated([TpqPage::class, TpqDocumentation::class]),
            ],
            [
                'label' => 'ZISWAF',
                'description' => 'Rekening, program, dan laporan keuangan.',
                'url' => route('dashboard.ziswaf'),
                'updated' => $this->lastUpdated([ZiswafPage::class, ZiswafReport::class]),
            ],
            [
                'label' => 'Galeri & Media',
                'description' => 'Album foto, video kajian, dan artikel.',
                'url' => route('dashboard.galeri'),
                'updated' => $this->lastUpdated([GalleryAlbum::class, GalleryPhoto::class, GalleryVideo::class, Article::class]),
            ],
            [
                'label' => 'Kontak',
                'description' => 'Alamat, peta, email, dan nomor pengurus.',
                'url' => route('dashboard.kontak'),
                'updated' => $this->lastUpdated([ContactSetting::class, ContactPhone::class]),
            ],
        ];
    }

    /**
     * @param  list<class-string<Model>>  $models
     */
    private function lastUpdated(array $models): ?CarbonImmutable
    {
        $latest = collect($models)
            ->map(fn (string $model) => $model::query()->max('updated_at'))
            ->filter()
            ->max();

        return $latest ? CarbonImmutable::parse($latest)->timezone('Asia/Jakarta') : null;
    }
}
