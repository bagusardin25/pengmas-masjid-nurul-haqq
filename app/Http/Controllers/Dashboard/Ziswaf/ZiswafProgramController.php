<?php

namespace App\Http\Controllers\Dashboard\Ziswaf;

use App\Models\ZiswafProgram;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ZiswafProgramController extends ZiswafListController
{
    protected string $section = 'program';

    protected string $bag = 'program';

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['boolean'],
        ];
    }

    protected function attributes(): array
    {
        return [
            'title' => 'nama program',
            'description' => 'keterangan',
            'sort_order' => 'urutan',
            'is_active' => 'status tampil',
        ];
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateForm($request);
        $data['is_active'] = $request->boolean('is_active');

        ZiswafProgram::create($this->withNextSortOrder(ZiswafProgram::class, $data));

        return $this->backToSection('Program sedekah ditambahkan.');
    }

    public function update(Request $request, ZiswafProgram $program): RedirectResponse
    {
        $data = $this->validateForm($request, $program);
        $data['is_active'] = $request->boolean('is_active');

        $program->update($this->withoutEmptySortOrder($data));

        return $this->backToSection('Program sedekah diperbarui.');
    }

    public function destroy(ZiswafProgram $program): RedirectResponse
    {
        $program->delete();

        return $this->backToSection('Program sedekah dihapus.');
    }
}
