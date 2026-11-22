<?php

namespace App\Http\Controllers\Dashboard\Galeri;

use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Services\MediaUploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Foto di dalam satu album. Beberapa foto bisa diunggah sekaligus.
 */
class GalleryPhotoController extends GaleriListController
{
    /** Batas foto per unggahan, agar total kiriman tetap di bawah batas post_max_size PHP (8 MB). */
    public const MAX_PER_UPLOAD = 10;

    protected string $section = 'foto';

    protected string $bag = 'photo';

    public function __construct(private readonly MediaUploader $uploader) {}

    protected function rules(): array
    {
        return [
            'caption' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ];
    }

    protected function createRules(): array
    {
        return [
            'photos' => ['required', 'array', 'min:1', 'max:'.self::MAX_PER_UPLOAD],
            'photos.*' => ['required', ...MediaUploader::rules()],
        ];
    }

    protected function messages(): array
    {
        return [
            ...MediaUploader::messages('photos.*'),
            'photos.required' => 'Pilih minimal satu foto.',
            'photos.min' => 'Pilih minimal satu foto.',
            'photos.max' => 'Maksimal '.self::MAX_PER_UPLOAD.' foto sekali unggah. Unggah sisanya setelah ini.',
        ];
    }

    protected function attributes(): array
    {
        return [
            'photos' => 'foto',
            'photos.*' => 'foto',
            'caption' => 'keterangan foto',
            'sort_order' => 'urutan',
        ];
    }

    protected function pageUrl(): string
    {
        return route('dashboard.galeri.albums.edit', request()->route('album'));
    }

    public function store(Request $request, GalleryAlbum $album): RedirectResponse
    {
        $this->validateForm($request);

        $sortOrder = (int) $album->photos()->max('sort_order');
        $files = $request->file('photos');

        foreach ($files as $file) {
            $media = $this->uploader->store($file, 'galeri/'.$album->id, $album->title);

            $album->photos()->create([
                'image_id' => $media->id,
                'sort_order' => ++$sortOrder,
            ]);
        }

        return $this->backToSection(count($files).' foto diunggah ke album.');
    }

    public function update(Request $request, GalleryAlbum $album, GalleryPhoto $photo): RedirectResponse
    {
        $data = $this->validateForm($request, $photo);

        $photo->update($this->withoutEmptySortOrder($data));
        $photo->image?->update(['alt' => $data['caption'] ?? $album->title]);

        return $this->backToSection('Keterangan foto diperbarui.');
    }

    public function cover(GalleryAlbum $album, GalleryPhoto $photo): RedirectResponse
    {
        $album->update(['cover_image_id' => $photo->image_id]);

        return $this->backToSection('Sampul album diganti.');
    }

    public function destroy(GalleryAlbum $album, GalleryPhoto $photo): RedirectResponse
    {
        $media = $photo->image;

        if ($album->cover_image_id === $photo->image_id) {
            $album->update(['cover_image_id' => null]);
        }

        $photo->delete();
        $media?->delete();

        return $this->backToSection('Foto dihapus dari album.');
    }
}
