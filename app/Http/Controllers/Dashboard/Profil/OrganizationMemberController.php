<?php

namespace App\Http\Controllers\Dashboard\Profil;

use App\Models\OrganizationMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrganizationMemberController extends ProfilListController
{
    protected string $section = 'pengurus';

    protected string $bag = 'member';

    protected function rules(): array
    {
        return [
            'position' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['boolean'],
        ];
    }

    protected function attributes(): array
    {
        return [
            'position' => 'jabatan',
            'name' => 'nama',
            'note' => 'keterangan',
            'sort_order' => 'urutan',
            'is_active' => 'status tampil',
        ];
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateForm($request);
        $data['is_active'] = $request->boolean('is_active');

        OrganizationMember::create($this->withNextSortOrder(OrganizationMember::class, $data));

        return $this->backToSection('Pengurus ditambahkan.');
    }

    public function update(Request $request, OrganizationMember $member): RedirectResponse
    {
        $data = $this->validateForm($request, $member);
        $data['is_active'] = $request->boolean('is_active');

        $member->update($this->withoutEmptySortOrder($data));

        return $this->backToSection('Pengurus diperbarui.');
    }

    public function destroy(OrganizationMember $member): RedirectResponse
    {
        $member->delete();

        return $this->backToSection('Pengurus dihapus.');
    }
}
