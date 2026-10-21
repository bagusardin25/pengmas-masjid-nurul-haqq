<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.beranda')->name('home');
Route::view('/profil', 'pages.profil')->name('profil');
Route::view('/jadwal-kegiatan', 'pages.jadwal')->name('jadwal');
Route::view('/program-tpq', 'pages.tpq')->name('tpq');
Route::view('/ziswaf', 'pages.ziswaf')->name('ziswaf');
Route::view('/galeri', 'pages.galeri')->name('galeri');
Route::view('/kontak', 'pages.kontak')->name('kontak');
