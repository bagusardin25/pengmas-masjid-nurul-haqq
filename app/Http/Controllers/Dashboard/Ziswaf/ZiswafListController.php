<?php

namespace App\Http\Controllers\Dashboard\Ziswaf;

use App\Http\Controllers\Dashboard\ListItemController;

/**
 * Dasar untuk daftar di halaman Kelola ZISWAF (jenis dana, program, kontak, laporan).
 */
abstract class ZiswafListController extends ListItemController
{
    protected string $page = 'dashboard.ziswaf';
}
