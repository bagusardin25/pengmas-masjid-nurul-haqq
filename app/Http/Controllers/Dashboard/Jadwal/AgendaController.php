<?php

namespace App\Http\Controllers\Dashboard\Jadwal;

use App\Enums\PublicationStatus;
use App\Models\Agenda;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AgendaController extends JadwalListController
{
    protected string $section = 'agenda';

    protected string $bag = 'agenda';

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date_format:Y-m-d'],
            'time' => ['nullable', 'string', 'max:255'],
            'speaker' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', Rule::enum(PublicationStatus::class)],
        ];
    }

    protected function attributes(): array
    {
        return [
            'title' => 'nama agenda',
            'date' => 'tanggal',
            'time' => 'waktu',
            'speaker' => 'khotib / pengisi',
            'location' => 'tempat',
            'description' => 'keterangan',
            'status' => 'status',
        ];
    }

    public function store(Request $request): RedirectResponse
    {
        Agenda::create($this->validateForm($request));

        return $this->backToSection('Agenda ditambahkan.');
    }

    public function update(Request $request, Agenda $agenda): RedirectResponse
    {
        $agenda->update($this->validateForm($request, $agenda));

        return $this->backToSection('Agenda diperbarui.');
    }

    public function destroy(Agenda $agenda): RedirectResponse
    {
        $agenda->delete();

        return $this->backToSection('Agenda dihapus.');
    }
}
