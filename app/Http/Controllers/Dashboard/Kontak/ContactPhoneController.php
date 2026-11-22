<?php

namespace App\Http\Controllers\Dashboard\Kontak;

use App\Http\Controllers\Dashboard\ListItemController;
use App\Models\ContactPhone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactPhoneController extends ListItemController
{
    protected string $page = 'dashboard.kontak';

    protected string $section = 'telepon';

    protected string $bag = 'phone';

    protected function rules(): array
    {
        return [
            'label' => ['required', 'string', 'max:100'],
            'number' => ['required', 'string', 'max:30', 'regex:/^[0-9+\-\s()]+$/'],
            'is_whatsapp' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ];
    }

    protected function attributes(): array
    {
        return [
            'label' => 'keterangan',
            'number' => 'nomor',
            'is_whatsapp' => 'pilihan WhatsApp',
            'sort_order' => 'urutan',
        ];
    }

    protected function messages(): array
    {
        return [
            'number.regex' => 'Nomor hanya boleh berisi angka, spasi, +, -, dan tanda kurung.',
        ];
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateForm($request);
        $data['is_whatsapp'] = $request->boolean('is_whatsapp');

        ContactPhone::create($this->withNextSortOrder(ContactPhone::class, $data));

        return $this->backToSection('Nomor kontak ditambahkan.');
    }

    public function update(Request $request, ContactPhone $phone): RedirectResponse
    {
        $data = $this->validateForm($request, $phone);
        $data['is_whatsapp'] = $request->boolean('is_whatsapp');

        $phone->update($this->withoutEmptySortOrder($data));

        return $this->backToSection('Nomor kontak diperbarui.');
    }

    public function destroy(ContactPhone $phone): RedirectResponse
    {
        $phone->delete();

        return $this->backToSection('Nomor kontak dihapus.');
    }
}
