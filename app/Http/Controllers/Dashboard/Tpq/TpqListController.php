<?php

namespace App\Http\Controllers\Dashboard\Tpq;

use App\Http\Controllers\Dashboard\ListItemController;

/**
 * Dasar untuk daftar di halaman Kelola TPQ (program unggulan, pertanyaan umum).
 */
abstract class TpqListController extends ListItemController
{
    protected string $page = 'dashboard.tpq';
}
