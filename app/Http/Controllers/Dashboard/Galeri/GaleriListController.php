<?php

namespace App\Http\Controllers\Dashboard\Galeri;

use App\Http\Controllers\Dashboard\ListItemController;
use App\Models\GalleryCategory;
use Illuminate\Validation\Rule;

/**
 * Dasar untuk daftar di halaman Kelola Galeri (kategori, album, video, artikel).
 */
abstract class GaleriListController extends ListItemController
{
    protected string $page = 'dashboard.galeri';

    /**
     * @return list<mixed>
     */
    protected function categoryRule(): array
    {
        return ['nullable', 'integer', Rule::exists(GalleryCategory::class, 'id')];
    }
}
