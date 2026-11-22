<?php

namespace App\Http\Controllers\Dashboard\Jadwal;

use App\Enums\Weekday;
use App\Models\KitabSchedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KitabScheduleController extends JadwalListController
{
    protected string $section = 'kitab';

    protected string $bag = 'kitab';

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'speaker' => ['required', 'string', 'max:255'],
            'day' => ['required', Rule::enum(Weekday::class)],
            'time' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ];
    }

    protected function attributes(): array
    {
        return [
            'title' => 'nama kitab',
            'speaker' => 'pengajar',
            'day' => 'hari',
            'time' => 'waktu',
            'location' => 'tempat',
            'sort_order' => 'urutan',
        ];
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateForm($request);

        KitabSchedule::create($this->withNextSortOrder(KitabSchedule::class, $data));

        return $this->backToSection('Jadwal ngaji kitab ditambahkan.');
    }

    public function update(Request $request, KitabSchedule $kitab): RedirectResponse
    {
        $data = $this->validateForm($request, $kitab);

        $kitab->update($this->withoutEmptySortOrder($data));

        return $this->backToSection('Jadwal ngaji kitab diperbarui.');
    }

    public function destroy(KitabSchedule $kitab): RedirectResponse
    {
        $kitab->delete();

        return $this->backToSection('Jadwal ngaji kitab dihapus.');
    }
}
