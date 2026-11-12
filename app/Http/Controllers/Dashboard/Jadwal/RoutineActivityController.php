<?php

namespace App\Http\Controllers\Dashboard\Jadwal;

use App\Models\RoutineActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RoutineActivityController extends JadwalListController
{
    protected string $section = 'rutin';

    protected string $bag = 'routine';

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'schedule' => ['nullable', 'string', 'max:255'],
            'badge' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'show_on_home' => ['boolean'],
        ];
    }

    protected function attributes(): array
    {
        return [
            'title' => 'nama kegiatan',
            'schedule' => 'keterangan',
            'badge' => 'label waktu',
            'sort_order' => 'urutan',
            'show_on_home' => 'status tampil di Beranda',
        ];
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateForm($request);
        $data['show_on_home'] = $request->boolean('show_on_home');

        RoutineActivity::create($this->withNextSortOrder(RoutineActivity::class, $data));

        return $this->backToSection('Kegiatan rutin ditambahkan.');
    }

    public function update(Request $request, RoutineActivity $routine): RedirectResponse
    {
        $data = $this->validateForm($request, $routine);
        $data['show_on_home'] = $request->boolean('show_on_home');

        $routine->update($this->withoutEmptySortOrder($data));

        return $this->backToSection('Kegiatan rutin diperbarui.');
    }

    public function destroy(RoutineActivity $routine): RedirectResponse
    {
        $routine->delete();

        return $this->backToSection('Kegiatan rutin dihapus.');
    }
}
