<?php

namespace App\Http\Controllers\Dashboard\Jadwal;

use App\Http\Controllers\Dashboard\ListItemController;

/**
 * Dasar untuk daftar di halaman Kelola Jadwal (kegiatan rutin, ngaji kitab, agenda).
 */
abstract class JadwalListController extends ListItemController
{
    protected string $page = 'dashboard.jadwal';
}
