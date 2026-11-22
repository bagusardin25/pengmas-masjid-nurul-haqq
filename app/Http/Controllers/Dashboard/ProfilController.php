<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\ProfileIcon;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Dashboard\Concerns\IndonesianValidationMessages;
use App\Models\AboutPage;
use App\Models\AboutRole;
use App\Models\AboutStat;
use App\Models\Facility;
use App\Models\OrganizationMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfilController extends Controller
{
    use IndonesianValidationMessages;

    public function edit(): View
    {
        return view('dashboard.profil', [
            'about' => AboutPage::current(),
            'stats' => AboutStat::query()->ordered()->get(),
            'roles' => AboutRole::query()->ordered()->get(),
            'facilities' => Facility::query()->ordered()->get(),
            'members' => OrganizationMember::query()->ordered()->get(),
            'icons' => ProfileIcon::cases(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validateWithBag('about', [
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_description' => ['nullable', 'string', 'max:1000'],
            'intro_title' => ['nullable', 'string', 'max:255'],
            'intro_description' => ['nullable', 'string', 'max:2000'],
            'history_title' => ['nullable', 'string', 'max:255'],
            'history_content' => ['nullable', 'string', 'max:20000'],
        ], $this->validationMessages(), [
            'hero_title' => 'judul halaman',
            'hero_description' => 'deskripsi halaman',
            'intro_title' => 'judul pengantar',
            'intro_description' => 'isi pengantar',
            'history_title' => 'judul sejarah',
            'history_content' => 'isi sejarah',
        ]);

        $data['history_title'] = $data['history_title'] ?? 'Sejarah';

        (AboutPage::current() ?? new AboutPage)->fill($data)->save();

        return to_route('dashboard.profil')
            ->with('status', 'Teks halaman profil berhasil disimpan.');
    }
}
