<?php

namespace App\Http\Controllers\Dashboard\Galeri;

use App\Enums\PublicationStatus;
use App\Models\Article;
use App\Services\MediaUploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ArticleController extends GaleriListController
{
    protected string $section = 'artikel';

    protected string $bag = 'article';

    public function __construct(private readonly MediaUploader $uploader) {}

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'author' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:50'],
            'published_at' => ['nullable', 'date_format:Y-m-d'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['required', 'string', 'max:50000'],
            'status' => ['required', Rule::enum(PublicationStatus::class)],
            'cover_image' => ['nullable', ...MediaUploader::rules()],
            'remove_cover_image' => ['boolean'],
        ];
    }

    protected function messages(): array
    {
        return MediaUploader::messages('cover_image');
    }

    protected function attributes(): array
    {
        return [
            'title' => 'judul artikel',
            'author' => 'penulis',
            'category' => 'kategori',
            'published_at' => 'tanggal terbit',
            'excerpt' => 'ringkasan',
            'body' => 'isi artikel',
            'status' => 'status',
            'cover_image' => 'foto sampul',
            'remove_cover_image' => 'pilihan hapus foto sampul',
        ];
    }

    /**
     * Isian dari form tanpa berkas. Tanggal terbit yang dikosongkan diisi otomatis saat artikel terbit.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function articleData(array $data): array
    {
        return collect($data)->except(['cover_image', 'remove_cover_image'])->all();
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateForm($request);
        $article = new Article($this->articleData($data));

        if ($request->hasFile('cover_image')) {
            $article->cover_image_id = $this->uploader->store($request->file('cover_image'), 'artikel', $data['title'])->id;
        }

        $article->save();

        return $this->backToSection('Artikel ditambahkan.');
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $data = $this->validateForm($request, $article);
        $oldCover = $article->coverImage;

        $article->fill($this->articleData($data));

        if ($request->hasFile('cover_image')) {
            $article->cover_image_id = $this->uploader->store($request->file('cover_image'), 'artikel', $data['title'])->id;
        } elseif ($request->boolean('remove_cover_image')) {
            $article->cover_image_id = null;
        }

        $article->save();

        if ($oldCover !== null && $oldCover->id !== $article->cover_image_id) {
            $oldCover->delete();
        }

        return $this->backToSection('Artikel diperbarui.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        $cover = $article->coverImage;

        $article->delete();
        $cover?->delete();

        return $this->backToSection('Artikel dihapus.');
    }
}
