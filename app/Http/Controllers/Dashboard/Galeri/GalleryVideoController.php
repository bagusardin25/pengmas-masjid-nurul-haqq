<?php

namespace App\Http\Controllers\Dashboard\Galeri;

use App\Enums\PublicationStatus;
use App\Models\GalleryVideo;
use App\Services\YoutubeMetadata;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class GalleryVideoController extends GaleriListController
{
    protected string $section = 'video';

    protected string $bag = 'video';

    public function __construct(private readonly YoutubeMetadata $youtube) {}

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
            // Judul dan durasi yang dikosongkan diambil otomatis dari YouTube.
            'title' => ['nullable', 'string', 'max:255'],
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
    private function withYoutubeData(array $data, ?GalleryVideo $video = null): array
    {
        $data['youtube_url'] = trim($data['youtube_url']);
        $data['youtube_id'] = GalleryVideo::parseYoutubeId($data['youtube_url']);

        if (blank($data['title'] ?? null) || blank($data['duration'] ?? null)) {
            $metadata = $this->youtube->fetch($data['youtube_id']);
            $data['title'] = filled($data['title'] ?? null) ? $data['title'] : $metadata['title'];
            $data['duration'] = filled($data['duration'] ?? null) ? $data['duration'] : $metadata['duration'];
        }

        if (blank($data['title'])) {
            throw ValidationException::withMessages([
                'title' => 'Judul tidak bisa diambil otomatis dari YouTube (video privat, dihapus, atau YouTube tidak bisa dihubungi). Isi judulnya secara manual.',
            ])->errorBag($this->bag.'-'.($video?->getKey() ?? 'new'))
                ->redirectTo($this->pageUrl().'#'.$this->section);
        }

        return $data;
    }

    /**
     * Judul dan durasi untuk mengisi form otomatis saat tautan YouTube ditempel.
     */
    public function lookup(Request $request): JsonResponse
    {
        $url = $request->query('url');
        $youtubeId = is_string($url) ? GalleryVideo::parseYoutubeId($url) : null;

        if ($youtubeId === null) {
            return response()->json(['message' => 'Tautan YouTube tidak dikenali.'], 422);
        }

        return response()->json($this->youtube->fetch($youtubeId));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateForm($request);

        GalleryVideo::create($this->withNextSortOrder(GalleryVideo::class, $this->withYoutubeData($data)));

        return $this->backToSection('Video ditambahkan.');
    }

    public function update(Request $request, GalleryVideo $video): RedirectResponse
    {
        $data = $this->validateForm($request, $video);

        $video->update($this->withYoutubeData($data, $video));

        return $this->backToSection('Video diperbarui.');
    }

    public function destroy(GalleryVideo $video): RedirectResponse
    {
        $video->delete();

        return $this->backToSection('Video dihapus.');
    }
}
