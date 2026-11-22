<?php

namespace App\Http\Controllers\Dashboard\Galeri;

use App\Models\GalleryCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GalleryCategoryController extends GaleriListController
{
    protected string $section = 'kategori';

    protected string $bag = 'category';

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ];
    }

    protected function attributes(): array
    {
        return [
            'name' => 'nama kategori',
            'sort_order' => 'urutan',
        ];
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateForm($request);

        GalleryCategory::create($this->withNextSortOrder(GalleryCategory::class, $data));

        return $this->backToSection('Kategori ditambahkan.');
    }

    public function update(Request $request, GalleryCategory $category): RedirectResponse
    {
        $data = $this->validateForm($request, $category);

        // Slug tidak diubah agar tautan filter yang sudah dibagikan tetap berlaku.
        $category->update($this->withoutEmptySortOrder($data));

        return $this->backToSection('Kategori diperbarui.');
    }

    public function destroy(GalleryCategory $category): RedirectResponse
    {
        // Album dan video di kategori ini tetap ada, hanya menjadi tanpa kategori.
        $category->delete();

        return $this->backToSection('Kategori dihapus. Album dan videonya kini tanpa kategori.');
    }
}
