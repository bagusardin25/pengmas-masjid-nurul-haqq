<?php

namespace App\Http\Controllers\Dashboard\Profil;

use App\Enums\ProfileIcon;
use App\Models\AboutRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AboutRoleController extends ProfilListController
{
    protected string $section = 'peran';

    protected string $bag = 'role';

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
            'title' => 'judul',
            'description' => 'deskripsi',
            'icon' => 'ikon',
            'sort_order' => 'urutan',
        ];
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateForm($request);

        AboutRole::create($this->withNextSortOrder(AboutRole::class, $data));

        return $this->backToSection('Peran masjid ditambahkan.');
    }

    public function update(Request $request, AboutRole $role): RedirectResponse
    {
        $data = $this->validateForm($request, $role);

        $role->update($this->withoutEmptySortOrder($data));

        return $this->backToSection('Peran masjid diperbarui.');
    }

    public function destroy(AboutRole $role): RedirectResponse
    {
        $role->delete();

        return $this->backToSection('Peran masjid dihapus.');
    }
}
