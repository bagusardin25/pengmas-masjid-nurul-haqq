<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\ProfileIcon;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Dashboard\Concerns\IndonesianValidationMessages;
use App\Models\TpqDocumentation;
use App\Models\TpqFaq;
use App\Models\TpqPage;
use App\Models\TpqPillar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TpqController extends Controller
{
    use IndonesianValidationMessages;

    public function edit(): View
    {
        return view('dashboard.tpq', [
            'tpq' => TpqPage::current(),
            'pillars' => TpqPillar::query()->ordered()->get(),
            'faqs' => TpqFaq::query()->ordered()->get(),
            'documentations' => TpqDocumentation::query()->with('image')->ordered()->get(),
            'icons' => ProfileIcon::cases(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validateWithBag('tpq', [
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_description' => ['nullable', 'string', 'max:1000'],
            'summary' => ['nullable', 'string', 'max:1000'],
            'info_description' => ['nullable', 'string', 'max:2000'],
            'info_age_range' => ['nullable', 'string', 'max:255'],
            'info_schedule' => ['nullable', 'string', 'max:255'],
            'info_fee' => ['nullable', 'string', 'max:255'],
            'registration_title' => ['nullable', 'string', 'max:255'],
            'registration_description' => ['nullable', 'string', 'max:2000'],
            'registration_phone' => ['nullable', 'string', 'max:30', 'regex:/^[0-9+\-\s()]+$/'],
            'registration_form_url' => ['nullable', 'url:http,https', 'max:255'],
        ], [
            ...$this->validationMessages(),
            'registration_phone.regex' => 'Nomor pendaftaran hanya boleh berisi angka, spasi, +, -, dan tanda kurung.',
        ], [
            'hero_title' => 'judul halaman',
            'hero_description' => 'deskripsi halaman',
            'summary' => 'ringkasan di Beranda',
            'info_description' => 'penjelasan program',
            'info_age_range' => 'usia',
            'info_schedule' => 'jadwal',
            'info_fee' => 'biaya',
            'registration_title' => 'judul pendaftaran',
            'registration_description' => 'keterangan pendaftaran',
            'registration_phone' => 'nomor pendaftaran',
            'registration_form_url' => 'tautan formulir',
        ]);

        (TpqPage::current() ?? new TpqPage)->fill($data)->save();

        return to_route('dashboard.tpq')
            ->with('status', 'Informasi TPQ berhasil disimpan.');
    }
}
