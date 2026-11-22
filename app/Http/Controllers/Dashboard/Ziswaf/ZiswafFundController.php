<?php

namespace App\Http\Controllers\Dashboard\Ziswaf;

use App\Enums\ProfileIcon;
use App\Models\ZiswafFund;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ZiswafFundController extends ZiswafListController
{
    protected string $section = 'jenis-dana';

    protected string $bag = 'fund';

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', Rule::enum(ProfileIcon::class)],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['boolean'],
        ];
    }

    protected function attributes(): array
    {
        return [
            'name' => 'nama jenis dana',
            'description' => 'keterangan',
            'icon' => 'ikon',
            'sort_order' => 'urutan',
            'is_active' => 'status tampil',
        ];
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateForm($request);
        $data['is_active'] = $request->boolean('is_active');

        ZiswafFund::create($this->withNextSortOrder(ZiswafFund::class, $data));

        return $this->backToSection('Jenis dana ditambahkan.');
    }

    public function update(Request $request, ZiswafFund $fund): RedirectResponse
    {
        $data = $this->validateForm($request, $fund);
        $data['is_active'] = $request->boolean('is_active');

        $fund->update($this->withoutEmptySortOrder($data));

        return $this->backToSection('Jenis dana diperbarui.');
    }

    public function destroy(ZiswafFund $fund): RedirectResponse
    {
        $fund->delete();

        return $this->backToSection('Jenis dana dihapus.');
    }
}
