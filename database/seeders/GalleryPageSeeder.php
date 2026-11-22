<?php

namespace Database\Seeders;

use App\Enums\PublicationStatus;
use App\Models\Article;
use App\Models\GalleryAlbum;
use App\Models\GalleryCategory;
use App\Services\MediaUploader;
use Illuminate\Database\Seeder;

/**
 * Album galeri dari dokumentasi asli masjid (folder "Data Masjid", diperkecil ke 1600 px dan
 * metadata/lokasi GPS-nya dibuang, disimpan di database/seeders/images/galeri).
 *
 * Tanggal hanya diisi bila jelas dari foto (EXIF) atau spanduknya; selebihnya dikosongkan.
 * Video dan artikel tidak diisi karena belum ada data aslinya; pengurus menambahkannya dari dashboard.
 */
class GalleryPageSeeder extends Seeder
{
    /** Album dan artikel contoh dari tahap desain yang diganti data asli. */
    private const DEMO_ALBUM_SLUGS = ['kajian-rutin', 'sholat-berjamaah', 'belajar-tpq', 'pengajian-ahad-malam'];

    private const DEMO_ARTICLE_SLUGS = ['adab-menuntut-ilmu', 'gotong-royong-memakmurkan-masjid'];

    public function run(): void
    {
        $this->removeDemoContent();

        $uploader = app(MediaUploader::class);
        $categoryIds = GalleryCategory::query()->pluck('id', 'slug');
        $sourceDir = database_path('seeders/images/galeri');

        $albums = [
            [
                'slug' => 'silaturahmi-pengurus-yayasan-dan-takmir',
                'title' => 'Silaturahmi Pengurus Yayasan Nurul Haqq dengan Takmir',
                'category' => 'kegiatan-masjid',
                'description' => 'Pertemuan silaturahmi pengurus Yayasan Nurul Haqq bersama Takmir Masjid Nurul Haqq di ruang utama masjid.',
                'date' => '2026-02-02',
                'location' => 'Ruang utama Masjid Nurul Haqq',
                'cover' => 'silaturahmi-pengurus-1.jpg',
                'photos' => [
                    'silaturahmi-pengurus-1.jpg' => 'Foto bersama pengurus Yayasan Nurul Haqq dan Takmir Masjid Nurul Haqq',
                    'silaturahmi-pengurus-2.jpg' => null,
                    'silaturahmi-pengurus-3.jpg' => null,
                ],
            ],
            [
                'slug' => 'safari-dakwah-isra-miraj-1446-h',
                'title' => "Safari Dakwah Peringatan Isra Mi'raj 1446 H",
                'category' => 'hari-besar-islam',
                'description' => "Safari dakwah memperingati Isra Mi'raj Nabi Muhammad ﷺ sekaligus menyambut Ramadhan 1446 H.",
                'date' => '2025-01-31',
                'location' => 'Halaman Masjid Nurul Haqq',
                'cover' => 'safari-dakwah-isra-miraj-2.jpg',
                'photos' => [
                    'safari-dakwah-isra-miraj-1.jpg' => "Warga di depan spanduk Safari Dakwah Isra Mi'raj 1446 H",
                    'safari-dakwah-isra-miraj-2.jpg' => "Panitia berfoto di depan spanduk Peringatan Isra Mi'raj dan menyambut Ramadhan",
                ],
            ],
            [
                'slug' => 'masjid-nurul-haqq',
                'title' => 'Masjid Nurul Haqq',
                'category' => 'masjid-fasilitas',
                'description' => 'Bangunan Masjid Nurul Haqq di Perumahan Griya Taman Asri, Tawangsari. Dibangun 13 Juni 2010 dan diresmikan 15 April 2018.',
                'date' => null,
                'location' => 'Griya Taman Asri Blok FF 08-09, Tawangsari',
                'cover' => 'masjid-tampak-depan-malam.jpg',
                'photos' => [
                    'masjid-tampak-depan-malam.jpg' => 'Tampak depan Masjid Nurul Haqq pada malam hari',
                    'prasasti-peresmian-masjid.jpg' => 'Prasasti: dibangun 1 Rajab 1431 H / 13 Juni 2010 M, diresmikan 28 Rajab 1439 H / 15 April 2018 M',
                ],
            ],
            [
                'slug' => 'kajian-dan-thalabul-ilmi',
                'title' => 'Kajian & Thalabul Ilmi',
                'category' => 'kajian-pengajian',
                'description' => 'Pengajian jamaah dan jadwal kajian rutin di Masjid Nurul Haqq yang terbuka untuk umum.',
                'date' => null,
                'location' => 'Masjid Nurul Haqq',
                'cover' => 'pengajian-jamaah-putri.jpg',
                'photos' => [
                    'pengajian-jamaah-putri.jpg' => 'Pengajian jamaah putri, 6 Februari 2025',
                    'jadwal-thalabul-ilmi-2026.jpg' => 'Jadwal Thalabul Ilmi 2026',
                    'jadwal-kajian-2025.jpg' => 'Jadwal kajian 2025 di serambi masjid',
                ],
            ],
            [
                'slug' => 'tpq-dan-madrasah-diniyah',
                'title' => 'TPQ & Madrasah Diniyah Nurul Haqq',
                'category' => 'tpq-madrasah',
                'description' => "Papan visi dan misi Madrasah Diniyah serta Taman Pendidikan Al-Qur'an (TPQ) Nurul Haqq.",
                'date' => null,
                'location' => 'Masjid Nurul Haqq',
                'cover' => 'papan-visi-misi-tpq.jpg',
                'photos' => [
                    'papan-visi-misi-tpq.jpg' => "Visi dan misi Taman Pendidikan Al-Qur'an Nurul Haqq",
                    'papan-madrasah-diniyah.jpg' => 'Visi dan misi Madrasah Diniyah Nurul Haqq',
                ],
            ],
        ];

        foreach ($albums as $index => $data) {
            $album = GalleryAlbum::query()->updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'title' => $data['title'],
                    'category_id' => $categoryIds[$data['category']] ?? null,
                    'description' => $data['description'],
                    'date' => $data['date'],
                    'location' => $data['location'],
                    'status' => PublicationStatus::Published,
                    'sort_order' => $index + 1,
                ],
            );

            $sortOrder = 1;

            foreach ($data['photos'] as $file => $caption) {
                $source = $sourceDir.'/'.$file;

                if (! is_file($source)) {
                    continue;
                }

                // importFile memakai data lama bila path yang sama sudah ada, jadi seeder aman dijalankan ulang.
                $media = $uploader->importFile($source, 'galeri/seed/'.$file, $caption ?? $data['title']);

                $album->photos()->updateOrCreate(
                    ['image_id' => $media->id],
                    ['caption' => $caption, 'sort_order' => $sortOrder++],
                );

                if ($file === $data['cover']) {
                    $album->update(['cover_image_id' => $media->id]);
                }
            }
        }
    }

    private function removeDemoContent(): void
    {
        GalleryAlbum::query()
            ->whereIn('slug', self::DEMO_ALBUM_SLUGS)
            ->whereDoesntHave('photos')
            ->delete();

        Article::query()
            ->whereIn('slug', self::DEMO_ARTICLE_SLUGS)
            ->whereNull('cover_image_id')
            ->delete();

        GalleryCategorySeeder::removeUnusedDemoCategories();
    }
}
