<?php

namespace Database\Seeders;

use App\Enums\PublicationStatus;
use App\Models\Article;
use App\Models\GalleryAlbum;
use App\Models\GalleryCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GalleryPageSeeder extends Seeder
{
    public function run(): void
    {
        $categoryIds = GalleryCategory::query()->pluck('id', 'slug');

        $albums = [
            [
                'title' => 'Kajian Rutin',
                'category' => 'kajian-rutin',
                'description' => 'Dokumentasi kajian Islam rutin jamaah bersama ustaz di masjid.',
                'date' => '2026-10-07',
            ],
            [
                'title' => 'Sholat Berjamaah',
                'category' => 'sholat-berjamaah',
                'description' => 'Momen kekhusyukan ibadah berjamaah di Masjid Nurul Haqq.',
                'date' => '2026-10-09',
            ],
            [
                'title' => 'Belajar TPQ',
                'category' => 'kegiatan-tpq',
                'description' => 'Semangat santri TPQ dalam mempelajari Al-Qur’an bersama ustaz.',
                'date' => '2026-10-08',
            ],
            [
                'title' => 'Pengajian Ahad Malam',
                'category' => 'pengajian-ahad-malam',
                'description' => 'Suasana pengajian rutin yang penuh kekhidmatan bersama jamaah.',
                'date' => '2026-10-11',
            ],
        ];

        $sortOrder = 1;

        foreach ($albums as $album) {
            GalleryAlbum::query()->updateOrCreate(
                ['slug' => Str::slug($album['title'])],
                [
                    'title' => $album['title'],
                    'category_id' => $categoryIds[$album['category']] ?? null,
                    'description' => $album['description'],
                    'date' => $album['date'],
                    'status' => PublicationStatus::Published,
                    'sort_order' => $sortOrder++,
                ],
            );
        }

        $articles = [
            [
                'title' => 'Adab Menuntut Ilmu',
                'author' => 'Ust. Ahmad Fauzi',
                'category' => 'Kajian',
                'excerpt' => 'Rasulullah ﷺ bersabda bahwa menuntut ilmu adalah kewajiban setiap Muslim. Menjaga adab dalam perjalanan mencari ilmu sama pentingnya dengan ilmu itu sendiri.',
                'published_at' => '2026-10-05',
            ],
            [
                'title' => 'Gotong Royong Memakmurkan Masjid',
                'author' => 'Panitia Masjid',
                'category' => 'Kegiatan',
                'excerpt' => 'Masjid bukan sekadar tempat sholat, melainkan pusat peradaban umat. Ketika jamaah bahu-membahu merawat dan memakmurkan masjid, terbentuk ikatan ukhuwah yang kuat.',
                'published_at' => '2026-10-10',
            ],
        ];

        foreach ($articles as $article) {
            Article::query()->updateOrCreate(
                ['slug' => Str::slug($article['title'])],
                [
                    'title' => $article['title'],
                    'author' => $article['author'],
                    'category' => $article['category'],
                    'excerpt' => $article['excerpt'],
                    'status' => PublicationStatus::Published,
                    'published_at' => $article['published_at'],
                ],
            );
        }
    }
}
