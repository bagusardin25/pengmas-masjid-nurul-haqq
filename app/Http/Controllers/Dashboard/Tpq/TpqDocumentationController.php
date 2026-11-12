<?php

namespace App\Http\Controllers\Dashboard\Tpq;

use App\Models\TpqDocumentation;
use App\Services\MediaUploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TpqDocumentationController extends TpqListController
{
    protected string $section = 'dokumentasi';

    protected string $bag = 'documentation';

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
            'image' => ['required', ...MediaUploader::rules()],
        ];
    }

    protected function messages(): array
    {
        return MediaUploader::messages('image');
    }

    protected function attributes(): array
    {
        return [
            'image' => 'foto',
            'caption' => 'keterangan foto',
            'sort_order' => 'urutan',
        ];
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateForm($request);

        $media = $this->uploader->store($request->file('image'), 'tpq', $data['caption'] ?? null);

        TpqDocumentation::create($this->withNextSortOrder(TpqDocumentation::class, [
            'image_id' => $media->id,
            'caption' => $data['caption'] ?? null,
            'sort_order' => $data['sort_order'] ?? null,
        ]));

        return $this->backToSection('Foto dokumentasi diunggah.');
    }

    public function update(Request $request, TpqDocumentation $documentation): RedirectResponse
    {
        $data = $this->validateForm($request, $documentation);

        $documentation->update($this->withoutEmptySortOrder($data));
        $documentation->image?->update(['alt' => $data['caption'] ?? null]);

        return $this->backToSection('Foto dokumentasi diperbarui.');
    }

    public function destroy(TpqDocumentation $documentation): RedirectResponse
    {
        $media = $documentation->image;

        $documentation->delete();
        $media?->delete();

        return $this->backToSection('Foto dokumentasi dihapus.');
    }
}
