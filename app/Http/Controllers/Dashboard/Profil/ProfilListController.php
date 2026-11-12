<?php

namespace App\Http\Controllers\Dashboard\Profil;

use App\Http\Controllers\Dashboard\ListItemController;

/**
 * Dasar untuk daftar di halaman Kelola Profil (statistik, peran, fasilitas, pengurus).
 */
abstract class ProfilListController extends ListItemController
{
    protected string $page = 'dashboard.profil';
}
