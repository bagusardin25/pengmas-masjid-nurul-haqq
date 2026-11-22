<?php

use App\Http\Controllers\AgendaCalendarController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\BerandaController as DashboardBerandaController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\Galeri\ArticleController;
use App\Http\Controllers\Dashboard\Galeri\GalleryAlbumController;
use App\Http\Controllers\Dashboard\Galeri\GalleryCategoryController;
use App\Http\Controllers\Dashboard\Galeri\GalleryPhotoController;
use App\Http\Controllers\Dashboard\Galeri\GalleryVideoController;
use App\Http\Controllers\Dashboard\GaleriController as DashboardGaleriController;
use App\Http\Controllers\Dashboard\Jadwal\AgendaController;
use App\Http\Controllers\Dashboard\Jadwal\KitabScheduleController;
use App\Http\Controllers\Dashboard\Jadwal\RoutineActivityController;
use App\Http\Controllers\Dashboard\JadwalController as DashboardJadwalController;
use App\Http\Controllers\Dashboard\Kontak\ContactPhoneController;
use App\Http\Controllers\Dashboard\KontakController as DashboardKontakController;
use App\Http\Controllers\Dashboard\MaintenanceController;
use App\Http\Controllers\Dashboard\Profil\AboutRoleController;
use App\Http\Controllers\Dashboard\Profil\AboutStatController;
use App\Http\Controllers\Dashboard\Profil\FacilityController;
use App\Http\Controllers\Dashboard\Profil\OrganizationMemberController;
use App\Http\Controllers\Dashboard\ProfilController as DashboardProfilController;
use App\Http\Controllers\Dashboard\Tpq\TpqDocumentationController;
use App\Http\Controllers\Dashboard\Tpq\TpqFaqController;
use App\Http\Controllers\Dashboard\Tpq\TpqPillarController;
use App\Http\Controllers\Dashboard\TpqController as DashboardTpqController;
use App\Http\Controllers\Dashboard\Ziswaf\ZiswafContactController;
use App\Http\Controllers\Dashboard\Ziswaf\ZiswafFundController;
use App\Http\Controllers\Dashboard\Ziswaf\ZiswafProgramController;
use App\Http\Controllers\Dashboard\Ziswaf\ZiswafReportController;
use App\Http\Controllers\Dashboard\ZiswafController as DashboardZiswafController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\TpqController;
use App\Http\Controllers\ZiswafController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BerandaController::class, 'index'])->name('home');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
Route::get('/jadwal-kegiatan', [JadwalController::class, 'index'])->name('jadwal');
Route::get('/program-tpq', [TpqController::class, 'index'])->name('tpq');
Route::get('/agenda/{agenda:slug}/kalender.ics', AgendaCalendarController::class)->name('agenda.calendar');
Route::get('/ziswaf', [ZiswafController::class, 'index'])->name('ziswaf');
Route::get('/ziswaf/laporan', [ZiswafController::class, 'reports'])->name('ziswaf.laporan');
Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri');
Route::get('/galeri/album/{album:slug}', [GaleriController::class, 'album'])->name('galeri.album');
Route::get('/galeri/artikel/{article:slug}', [GaleriController::class, 'article'])->name('galeri.artikel');
Route::get('/kontak', [KontakController::class, 'index'])->name('kontak');

Route::middleware(['auth'])->prefix('dashboard')->group(function () {
    // Nama "dashboard" dipakai controller auth bawaan Breeze untuk redirect.
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::name('dashboard.')->group(function () {
        Route::get('/beranda', [DashboardBerandaController::class, 'edit'])->name('beranda');
        Route::put('/beranda', [DashboardBerandaController::class, 'update'])->name('beranda.update');

        Route::get('/profil', [DashboardProfilController::class, 'edit'])->name('profil');
        Route::put('/profil', [DashboardProfilController::class, 'update'])->name('profil.update');

        Route::prefix('profil')->name('profil.')->group(function () {
            Route::resource('statistik', AboutStatController::class)
                ->only(['store', 'update', 'destroy'])
                ->parameters(['statistik' => 'stat'])
                ->names('stats');
            Route::resource('peran', AboutRoleController::class)
                ->only(['store', 'update', 'destroy'])
                ->parameters(['peran' => 'role'])
                ->names('roles');
            Route::resource('fasilitas', FacilityController::class)
                ->only(['store', 'update', 'destroy'])
                ->parameters(['fasilitas' => 'facility'])
                ->names('facilities');
            Route::resource('pengurus', OrganizationMemberController::class)
                ->only(['store', 'update', 'destroy'])
                ->parameters(['pengurus' => 'member'])
                ->names('members');
        });

        Route::get('/jadwal', [DashboardJadwalController::class, 'edit'])->name('jadwal');
        Route::put('/jadwal', [DashboardJadwalController::class, 'update'])->name('jadwal.update');

        Route::prefix('jadwal')->name('jadwal.')->group(function () {
            Route::resource('kegiatan-rutin', RoutineActivityController::class)
                ->only(['store', 'update', 'destroy'])
                ->parameters(['kegiatan-rutin' => 'routine'])
                ->names('routines');
            Route::resource('ngaji-kitab', KitabScheduleController::class)
                ->only(['store', 'update', 'destroy'])
                ->parameters(['ngaji-kitab' => 'kitab'])
                ->names('kitab');
            Route::resource('agenda', AgendaController::class)
                ->only(['store', 'update', 'destroy'])
                ->parameters(['agenda' => 'agenda'])
                ->names('agendas');
        });

        Route::get('/tpq', [DashboardTpqController::class, 'edit'])->name('tpq');
        Route::put('/tpq', [DashboardTpqController::class, 'update'])->name('tpq.update');

        Route::prefix('tpq')->name('tpq.')->group(function () {
            Route::resource('program', TpqPillarController::class)
                ->only(['store', 'update', 'destroy'])
                ->parameters(['program' => 'pillar'])
                ->names('pillars');
            Route::resource('pertanyaan', TpqFaqController::class)
                ->only(['store', 'update', 'destroy'])
                ->parameters(['pertanyaan' => 'faq'])
                ->names('faqs');
            Route::resource('dokumentasi', TpqDocumentationController::class)
                ->only(['store', 'update', 'destroy'])
                ->parameters(['dokumentasi' => 'documentation'])
                ->names('documentations');
        });

        Route::get('/ziswaf', [DashboardZiswafController::class, 'edit'])->name('ziswaf');
        Route::put('/ziswaf', [DashboardZiswafController::class, 'update'])->name('ziswaf.update');

        Route::prefix('ziswaf')->name('ziswaf.')->group(function () {
            Route::resource('jenis-dana', ZiswafFundController::class)
                ->only(['store', 'update', 'destroy'])
                ->parameters(['jenis-dana' => 'fund'])
                ->names('funds');
            Route::resource('program', ZiswafProgramController::class)
                ->only(['store', 'update', 'destroy'])
                ->parameters(['program' => 'program'])
                ->names('programs');
            Route::resource('kontak', ZiswafContactController::class)
                ->only(['store', 'update', 'destroy'])
                ->parameters(['kontak' => 'contact'])
                ->names('contacts');
            Route::resource('laporan', ZiswafReportController::class)
                ->only(['store', 'update', 'destroy'])
                ->parameters(['laporan' => 'report'])
                ->names('reports');
        });

        Route::get('/galeri', [DashboardGaleriController::class, 'edit'])->name('galeri');

        Route::prefix('galeri')->name('galeri.')->group(function () {
            Route::resource('kategori', GalleryCategoryController::class)
                ->only(['store', 'update', 'destroy'])
                ->parameters(['kategori' => 'category'])
                ->names('categories');
            Route::resource('album', GalleryAlbumController::class)
                ->only(['store', 'edit', 'update', 'destroy'])
                ->parameters(['album' => 'album'])
                ->names('albums');
            Route::resource('video', GalleryVideoController::class)
                ->only(['store', 'update', 'destroy'])
                ->parameters(['video' => 'video'])
                ->names('videos');
            Route::resource('artikel', ArticleController::class)
                ->only(['store', 'update', 'destroy'])
                ->parameters(['artikel' => 'article'])
                ->names('articles');

            // Foto selalu diakses lewat albumnya, jadi foto album lain tidak bisa diubah dari sini.
            Route::scopeBindings()->prefix('album/{album}/foto')->name('albums.photos.')->group(function () {
                Route::post('/', [GalleryPhotoController::class, 'store'])->name('store');
                Route::put('/{photo}', [GalleryPhotoController::class, 'update'])->name('update');
                Route::put('/{photo}/sampul', [GalleryPhotoController::class, 'cover'])->name('cover');
                Route::delete('/{photo}', [GalleryPhotoController::class, 'destroy'])->name('destroy');
            });
        });

        // Perawatan server (shared hosting). Tidak ada di menu; alamatnya memuat MAINTENANCE_TOKEN.
        Route::prefix('perawatan/{token}')->name('maintenance')->group(function () {
            Route::get('/', [MaintenanceController::class, 'show'])->name('');
            Route::post('/storage-link', [MaintenanceController::class, 'link'])->name('.link');
            Route::delete('/storage-link', [MaintenanceController::class, 'unlink'])->name('.unlink');
            Route::post('/bersihkan-cache', [MaintenanceController::class, 'clearCache'])->name('.cache');
        });

        Route::get('/kontak', [DashboardKontakController::class, 'edit'])->name('kontak');
        Route::put('/kontak', [DashboardKontakController::class, 'update'])->name('kontak.update');

        Route::prefix('kontak')->name('kontak.')->group(function () {
            Route::resource('telepon', ContactPhoneController::class)
                ->only(['store', 'update', 'destroy'])
                ->parameters(['telepon' => 'phone'])
                ->names('phones');
        });
    });
});

Route::middleware('auth')->group(function () {
    // Halaman "Akun & Admin" di dashboard.
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::post('/dashboard/admin', [AdminController::class, 'store'])->name('dashboard.admins.store');
    Route::delete('/dashboard/admin/{admin}', [AdminController::class, 'destroy'])->name('dashboard.admins.destroy');
});

require __DIR__.'/auth.php';
