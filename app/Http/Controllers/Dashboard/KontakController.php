<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Dashboard\Concerns\IndonesianValidationMessages;
use App\Models\ContactPhone;
use App\Models\ContactSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KontakController extends Controller
{
    use IndonesianValidationMessages;

    public function edit(): View
    {
        return view('dashboard.kontak', [
            'contact' => ContactSetting::current(),
            'phones' => ContactPhone::query()->ordered()->get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        // Isian dirapikan dulu: "@masjid" atau tautan profil → username, kode <iframe> peta → alamatnya saja.
        $request->merge([
            'instagram' => ContactSetting::normalizeInstagram($request->input('instagram')),
            'maps_embed_url' => ContactSetting::extractEmbedUrl($request->input('maps_embed_url')),
        ]);

        $data = $request->validateWithBag('contact', [
            'address' => ['nullable', 'string', 'max:500'],
            'maps_url' => ['nullable', 'url:http,https', 'max:255'],
            'maps_embed_url' => [
                'nullable',
                'string',
                'max:4000',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (! ContactSetting::isValidEmbedUrl($value)) {
                        $fail('Sematan peta harus dari Google Maps: buka lokasi di Google Maps, pilih Bagikan → Sematkan peta, lalu salin kodenya ke sini.');
                    }
                },
            ],
            'email' => ['nullable', 'email', 'max:255'],
            'instagram' => ['nullable', 'string', 'max:30', 'regex:/^[A-Za-z0-9._]+$/'],
            'youtube_url' => ['nullable', 'url:http,https', 'max:255'],
            'service_hours' => ['nullable', 'string', 'max:255'],
        ], [
            ...$this->validationMessages(),
            'instagram.regex' => 'Username Instagram hanya boleh berisi huruf, angka, titik, dan garis bawah.',
        ], [
            'address' => 'alamat',
            'maps_url' => 'tautan Google Maps',
            'maps_embed_url' => 'sematan peta',
            'email' => 'email',
            'instagram' => 'username Instagram',
            'youtube_url' => 'tautan kanal YouTube',
            'service_hours' => 'jam layanan sekretariat',
        ]);

        (ContactSetting::current() ?? new ContactSetting)->fill($data)->save();

        return to_route('dashboard.kontak')
            ->with('status', 'Informasi kontak berhasil disimpan.');
    }
}
