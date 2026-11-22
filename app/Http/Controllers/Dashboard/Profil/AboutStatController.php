<?php

namespace App\Http\Controllers\Dashboard\Profil;

use App\Enums\ProfileIcon;
use App\Models\AboutStat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AboutStatController extends ProfilListController
{
    protected string $section = 'statistik';

    protected string $bag = 'stat';

    protected function rules(): array
    {
        return [
            'value' => ['required', 'string', 'max:255'],
            'label' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', Rule::enum(ProfileIcon::class)],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ];
    }

    protected function attributes(): array
    {
        return [
            'value' => 'nilai',
            'label' => 'keterangan',
            'icon' => 'ikon',
            'sort_order' => 'urutan',
        ];
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateForm($request);

        AboutStat::create($this->withNextSortOrder(AboutStat::class, $data));

        return $this->backToSection('Statistik ditambahkan.');
    }

    public function update(Request $request, AboutStat $stat): RedirectResponse
    {
        $data = $this->validateForm($request, $stat);

        $stat->update($this->withoutEmptySortOrder($data));

        return $this->backToSection('Statistik diperbarui.');
    }

    public function destroy(AboutStat $stat): RedirectResponse
    {
        $stat->delete();

        return $this->backToSection('Statistik dihapus.');
    }
}
