<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Menyimpan foto yang diunggah dari dashboard ke disk "public" beserta datanya di tabel media.
 * Dipakai bersama oleh dokumentasi TPQ dan (nanti) Galeri.
 */
class MediaUploader
{
    /**
     * Batas ukuran berkas (KB). Disamakan dengan upload_max_filesize bawaan PHP (2 MB);
     * foto besar sudah diperkecil di browser sebelum dikirim.
     */
    public const MAX_KILOBYTES = 2048;

    public const MIMES = ['jpg', 'jpeg', 'png', 'webp'];

    /**
     * Aturan validasi untuk isian foto.
     *
     * @return list<string>
     */
    public static function rules(): array
    {
        return ['image', 'mimes:'.implode(',', self::MIMES), 'max:'.self::MAX_KILOBYTES];
    }

    /**
     * Pesan validasi berbahasa Indonesia untuk isian foto dengan nama $field.
     *
     * @return array<string, string>
     */
    public static function messages(string $field = 'image'): array
    {
        return [
            "{$field}.required" => 'Pilih foto yang akan diunggah.',
            "{$field}.uploaded" => 'Foto gagal diunggah. Pastikan ukurannya tidak lebih dari 2 MB.',
            "{$field}.image" => 'Berkas harus berupa foto berformat JPG, PNG, atau WebP.',
            "{$field}.mimes" => 'Berkas harus berupa foto berformat JPG, PNG, atau WebP.',
            "{$field}.max" => 'Ukuran foto maksimal 2 MB.',
        ];
    }

    public function store(UploadedFile $file, string $directory, ?string $alt = null): Media
    {
        $path = $file->store($directory, 'public');
        $dimensions = @getimagesize($file->getRealPath()) ?: [null, null];

        return Media::create([
            'disk' => 'public',
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'alt' => $alt,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'width' => $dimensions[0],
            'height' => $dimensions[1],
        ]);
    }

    /**
     * Salin berkas yang sudah ada di proyek (mis. foto di public/images) ke disk "public".
     * Dipakai seeder agar data awal memakai foto asli. Bila path sudah ada, data lama dipakai.
     */
    public function importFile(string $sourcePath, string $targetPath, ?string $alt = null): Media
    {
        $existing = Media::query()->where('disk', 'public')->where('path', $targetPath)->first();

        if ($existing !== null) {
            return $existing;
        }

        Storage::disk('public')->put($targetPath, file_get_contents($sourcePath));
        $dimensions = @getimagesize($sourcePath) ?: [null, null];

        return Media::create([
            'disk' => 'public',
            'path' => $targetPath,
            'original_name' => basename($sourcePath),
            'alt' => $alt,
            'mime_type' => mime_content_type($sourcePath) ?: null,
            'size' => filesize($sourcePath) ?: null,
            'width' => $dimensions[0],
            'height' => $dimensions[1],
        ]);
    }
}
