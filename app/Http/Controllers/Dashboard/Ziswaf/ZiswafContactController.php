<?php

namespace App\Http\Controllers\Dashboard\Ziswaf;

use App\Models\ZiswafContact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ZiswafContactController extends ZiswafListController
{
    protected string $section = 'kontak';

    protected string $bag = 'contact';

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^[0-9+\-\s()]+$/'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ];
    }

    protected function attributes(): array
    {
        return [
            'name' => 'nama',
            'role' => 'jabatan',
            'phone' => 'nomor WhatsApp',
            'sort_order' => 'urutan',
        ];
    }

    protected function messages(): array
    {
        return [
            'phone.regex' => 'Nomor WhatsApp hanya boleh berisi angka, spasi, +, -, dan tanda kurung.',
        ];
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateForm($request);

        ZiswafContact::create($this->withNextSortOrder(ZiswafContact::class, $data));

        return $this->backToSection('Kontak konfirmasi ditambahkan.');
    }

    public function update(Request $request, ZiswafContact $contact): RedirectResponse
    {
        $data = $this->validateForm($request, $contact);

        $contact->update($this->withoutEmptySortOrder($data));

        return $this->backToSection('Kontak konfirmasi diperbarui.');
    }

    public function destroy(ZiswafContact $contact): RedirectResponse
    {
        $contact->delete();

        return $this->backToSection('Kontak konfirmasi dihapus.');
    }
}
