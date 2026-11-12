<?php

namespace App\Http\Controllers\Dashboard\Profil;

use App\Models\Facility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FacilityController extends ProfilListController
{
    protected string $section = 'fasilitas';

    protected string $bag = 'facility';

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['boolean'],
        ];
    }

    protected function attributes(): array
    {
        return [
            'name' => 'nama fasilitas',
            'sort_order' => 'urutan',
            'is_active' => 'status tampil',
        ];
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateForm($request);
        $data['is_active'] = $request->boolean('is_active');

        Facility::create($this->withNextSortOrder(Facility::class, $data));

        return $this->backToSection('Fasilitas ditambahkan.');
    }

    public function update(Request $request, Facility $facility): RedirectResponse
    {
        $data = $this->validateForm($request, $facility);
        $data['is_active'] = $request->boolean('is_active');

        $facility->update($this->withoutEmptySortOrder($data));

        return $this->backToSection('Fasilitas diperbarui.');
    }

    public function destroy(Facility $facility): RedirectResponse
    {
        $facility->delete();

        return $this->backToSection('Fasilitas dihapus.');
    }
}
