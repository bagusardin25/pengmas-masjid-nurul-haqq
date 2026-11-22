<?php

namespace App\Http\Controllers\Dashboard\Tpq;

use App\Enums\ProfileIcon;
use App\Models\TpqPillar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TpqPillarController extends TpqListController
{
    protected string $section = 'program';

    protected string $bag = 'pillar';

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'icon' => ['nullable', Rule::enum(ProfileIcon::class)],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ];
    }

    protected function attributes(): array
    {
        return [
            'title' => 'nama program',
            'description' => 'deskripsi',
            'icon' => 'ikon',
            'sort_order' => 'urutan',
        ];
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateForm($request);

        TpqPillar::create($this->withNextSortOrder(TpqPillar::class, $data));

        return $this->backToSection('Program unggulan ditambahkan.');
    }

    public function update(Request $request, TpqPillar $pillar): RedirectResponse
    {
        $data = $this->validateForm($request, $pillar);

        $pillar->update($this->withoutEmptySortOrder($data));

        return $this->backToSection('Program unggulan diperbarui.');
    }

    public function destroy(TpqPillar $pillar): RedirectResponse
    {
        $pillar->delete();

        return $this->backToSection('Program unggulan dihapus.');
    }
}
