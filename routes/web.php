<?php

use App\Http\Controllers\BerandaController;
use App\Http\Controllers\Dashboard\BerandaController as DashboardBerandaController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BerandaController::class, 'index'])->name('home');
Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
Route::get('/jadwal-kegiatan', function () {
    return view('pages.jadwal');
})->name('jadwal');
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
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/beranda', [DashboardBerandaController::class, 'index'])->name('dashboard.beranda');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
