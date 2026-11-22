<?php

namespace App\Http\Controllers\Dashboard\Galeri;

use App\Enums\PublicationStatus;
use App\Models\GalleryAlbum;
use App\Models\GalleryCategory;
use App\Models\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Album dibuat dari halaman Kelola Galeri, lalu detail dan fotonya dikelola di halaman album sendiri.
 */
class GalleryAlbumController extends GaleriListController
{
    protected string $section = 'album';

    protected string $bag = 'album';

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category_id' => $this->categoryRule(),
            'date' => ['nullable', 'date_format:Y-m-d'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', Rule::enum(PublicationStatus::class)],
        ];
    }

    protected function attributes(): array
    {
        return [
            'title' => 'judul album',
            'category_id' => 'kategori',
            'date' => 'tanggal kegiatan',
            'location' => 'lokasi',
            'description' => 'keterangan',
            'status' => 'status',
        ];
    }

    protected function pageUrl(): string
    {
        $album = request()->route('album');

        return $album instanceof GalleryAlbum
            ? route('dashboard.galeri.albums.edit', $album)
            : route($this->page);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateForm($request);

        $album = GalleryAlbum::create($data);

        return to_route('dashboard.galeri.albums.edit', $album)
            ->withFragment('foto')
            ->with('status', 'Album dibuat. Sekarang unggah foto-fotonya.');
    }

    public function edit(GalleryAlbum $album): View
    {
        return view('dashboard.galeri-album', [
            'album' => $album->load(['coverImage', 'firstPhoto.image']),
            'photos' => $album->photos()->with('image')->get(),
            'categories' => GalleryCategory::query()->ordered()->get(),
            'statuses' => PublicationStatus::cases(),
        ]);
    }

    public function update(Request $request, GalleryAlbum $album): RedirectResponse
    {
        $this->section = 'detail';
        $data = $this->validateForm($request, $album);

        $album->update($data);

        return $this->backToSection('Detail album diperbarui.');
    }

    public function destroy(GalleryAlbum $album): RedirectResponse
    {
        $mediaIds = $album->photos()->pluck('image_id')->push($album->cover_image_id)->filter()->unique();

        $album->delete();

        // Berkas foto ikut dihapus (Media menghapus berkasnya sendiri saat datanya dihapus).
        Media::query()->whereKey($mediaIds)->get()->each->delete();

        return to_route('dashboard.galeri')
            ->withFragment('album')
            ->with('status', 'Album “'.$album->title.'” beserta fotonya dihapus.');
    }
}
