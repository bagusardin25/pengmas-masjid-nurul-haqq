<?php

namespace App\Http\Controllers\Dashboard\Galeri;

use App\Enums\PublicationStatus;
use App\Models\GalleryVideo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GalleryVideoController extends GaleriListController
{
    protected string $section = 'video';

    protected string $bag = 'video';

    protected function rules(): array
    {
        return [
            'youtube_url' => [
                'required',
                'string',
                'max:255',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (GalleryVideo::parseYoutubeId((string) $value) === null) {
                        $fail('Tautan YouTube tidak dikenali. Salin tautan dari tombol Bagikan di YouTube, mis. https://youtu.be/abc123XYZ_0.');
                    }
                },
            ],
            'title' => ['required', 'string', 'max:255'],
            'category_id' => $this->categoryRule(),
            'speaker' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'date_format:Y-m-d'],
            'duration' => ['nullable', 'string', 'max:20', 'regex:/^\d{1,2}(:\d{2}){1,2}$/'],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', Rule::enum(PublicationStatus::class)],
        ];
    }

    protected function messages(): array
    {
        return [
            'duration.regex' => 'Durasi ditulis seperti di YouTube, mis. 24:18 atau 1:05:30.',
        ];
    }

    protected function attributes(): array
    {
        return [
            'youtube_url' => 'tautan YouTube',
            'title' => 'judul video',
            'category_id' => 'kategori',
            'speaker' => 'pemateri',
            'date' => 'tanggal',
            'duration' => 'durasi',
            'description' => 'keterangan',
            'status' => 'status',
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function withYoutubeId(array $data): array
    {
        $data['youtube_url'] = trim($data['youtube_url']);
        $data['youtube_id'] = GalleryVideo::parseYoutubeId($data['youtube_url']);

        return $data;
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateForm($request);

        GalleryVideo::create($this->withNextSortOrder(GalleryVideo::class, $this->withYoutubeId($data)));

        return $this->backToSection('Video ditambahkan.');
    }

    public function update(Request $request, GalleryVideo $video): RedirectResponse
    {
        $data = $this->validateForm($request, $video);

        $video->update($this->withYoutubeId($data));

        return $this->backToSection('Video diperbarui.');
    }

    public function destroy(GalleryVideo $video): RedirectResponse
    {
        $video->delete();

        return $this->backToSection('Video dihapus.');
    }
}
