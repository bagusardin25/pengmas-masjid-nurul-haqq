<?php

use App\Http\Controllers\BerandaController;
use App\Http\Controllers\Dashboard\BerandaController as DashboardBerandaController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\Jadwal\AgendaController;
use App\Http\Controllers\Dashboard\Jadwal\KitabScheduleController;
use App\Http\Controllers\Dashboard\Jadwal\RoutineActivityController;
use App\Http\Controllers\Dashboard\JadwalController as DashboardJadwalController;
use App\Http\Controllers\Dashboard\Profil\AboutRoleController;
use App\Http\Controllers\Dashboard\Profil\AboutStatController;
use App\Http\Controllers\Dashboard\Profil\FacilityController;
use App\Http\Controllers\Dashboard\Profil\OrganizationMemberController;
use App\Http\Controllers\Dashboard\ProfilController as DashboardProfilController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BerandaController::class, 'index'])->name('home');
Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
Route::get('/jadwal-kegiatan', [JadwalController::class, 'index'])->name('jadwal');
Route::get('/program-tpq', function () {
    return view('pages.tpq');
})->name('tpq');
Route::get('/ziswaf', function () {
    return view('pages.ziswaf');
})->name('ziswaf');
Route::get('/galeri', function () {
    return view('pages.galeri');
})->name('galeri');
Route::get('/kontak', function () {
    return view('pages.kontak');
})->name('kontak');

Route::middleware(['auth'])->prefix('dashboard')->group(function () {
    // Nama "dashboard" dipakai controller auth bawaan Breeze untuk redirect.
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::name('dashboard.')->group(function () {
        Route::get('/beranda', [DashboardBerandaController::class, 'index'])->name('beranda');

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
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';