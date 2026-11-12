<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Dashboard\Concerns\IndonesianValidationMessages;
use App\Models\AboutStat;
use App\Models\Agenda;
use App\Models\Facility;
use App\Models\HomePage;
use App\Models\KitabSchedule;
use App\Models\RoutineActivity;
use App\Models\TpqPillar;
use App\Services\MediaUploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BerandaController extends Controller
{
    use IndonesianValidationMessages;

    public function edit(): View
    {
        return view('dashboard.beranda', [
            'hero' => HomePage::query()->with('heroImage')->first(),
            'counts' => [
                'stats' => AboutStat::query()->count(),
                'facilities' => Facility::query()->where('is_active', true)->count(),
                'routines' => RoutineActivity::query()->where('show_on_home', true)->count(),
                'kitab' => KitabSchedule::query()->count(),
                'agendas' => Agenda::query()->published()->whereDate('date', '>=', now('Asia/Jakarta')->toDateString())->count(),
                'tpqPillars' => TpqPillar::query()->count(),
            ],
        ]);
    }

    public function update(Request $request, MediaUploader $uploader): RedirectResponse
    {
        $data = $request->validateWithBag('hero', [
            'hero_eyebrow' => ['nullable', 'string', 'max:255'],
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_description' => ['nullable', 'string', 'max:1000'],
            'hero_arabic_text' => ['nullable', 'string', 'max:1000'],
            'hero_translation' => ['nullable', 'string', 'max:1000'],
            'hero_citation' => ['nullable', 'string', 'max:255'],
            'hero_image' => ['nullable', ...MediaUploader::rules()],
            'hero_image_alt' => ['nullable', 'string', 'max:255'],
            'remove_hero_image' => ['boolean'],
        ], [
            ...$this->validationMessages(),
            ...MediaUploader::messages('hero_image'),
        ], [
            'hero_eyebrow' => 'label kecil',
            'hero_title' => 'judul',
            'hero_description' => 'deskripsi',
            'hero_arabic_text' => 'teks ayat',
            'hero_translation' => 'terjemahan',
            'hero_citation' => 'sumber ayat',
            'hero_image' => 'foto hero',
            'hero_image_alt' => 'keterangan foto',
            'remove_hero_image' => 'pilihan foto bawaan',
        ]);

        $hero = HomePage::current() ?? new HomePage;
        $oldImage = $hero->heroImage;
        $alt = $data['hero_image_alt'] ?? null;

        $hero->fill(collect($data)->only([
            'hero_eyebrow',
            'hero_title',
            'hero_description',
            'hero_arabic_text',
            'hero_translation',
            'hero_citation',
        ])->all());

        if ($request->hasFile('hero_image')) {
            $hero->hero_image_id = $uploader->store($request->file('hero_image'), 'beranda', $alt)->id;
        } elseif ($request->boolean('remove_hero_image')) {
            $hero->hero_image_id = null;
        } elseif ($oldImage !== null) {
            $oldImage->update(['alt' => $alt]);
        }

        $hero->save();

        // Foto lama yang sudah diganti atau dilepas ikut dihapus beserta berkasnya.
        if ($oldImage !== null && $oldImage->id !== $hero->hero_image_id) {
            $oldImage->delete();
        }

        return to_route('dashboard.beranda')
            ->with('status', 'Bagian pembuka Beranda berhasil disimpan.');
    }
}
