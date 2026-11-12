<?php

namespace App\Http\Controllers\Dashboard\Concerns;

/**
 * Pesan validasi berbahasa Indonesia untuk form dashboard.
 *
 * Aplikasi masih memakai locale "en" (belum ada folder lang/id), jadi pesan
 * untuk aturan yang dipakai form dashboard disediakan di sini.
 */
trait IndonesianValidationMessages
{
    /**
     * Aturan ukuran (max, min) memakai array per tipe nilai, sesuai format Laravel.
     *
     * @return array<string, string|array<string, string>>
     */
    protected function validationMessages(): array
    {
        return [
            'required' => ':Attribute wajib diisi.',
            'string' => ':Attribute harus berupa teks.',
            'integer' => ':Attribute harus berupa angka bulat.',
            'boolean' => ':Attribute tidak valid.',
            'enum' => 'Pilihan :attribute tidak valid.',
            'max' => [
                'string' => ':Attribute maksimal :max karakter.',
                'numeric' => ':Attribute maksimal :max.',
            ],
            'min' => [
                'string' => ':Attribute minimal :min karakter.',
                'numeric' => ':Attribute minimal :min.',
            ],
        ];
    }
}
