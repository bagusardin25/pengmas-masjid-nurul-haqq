<?php

namespace Database\Seeders;

use App\Models\GalleryCategory;
use Illuminate\Database\Seeder;

class GalleryCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'kajian-rutin' => 'Kajian rutin',
            'sholat-berjamaah' => 'Sholat berjamaah',
            'kegiatan-tpq' => 'Kegiatan TPQ',
            'pengajian-ahad-malam' => 'Pengajian Ahad malam',
        ];

        $sortOrder = 1;

        foreach ($categories as $slug => $name) {
            GalleryCategory::query()->updateOrCreate(
                ['slug' => $slug],
                ['name' => $name, 'sort_order' => $sortOrder++],
            );
        }
    }
}
