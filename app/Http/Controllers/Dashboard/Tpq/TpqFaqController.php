<?php

namespace App\Http\Controllers\Dashboard\Tpq;

use App\Models\TpqFaq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TpqFaqController extends TpqListController
{
    protected string $section = 'pertanyaan';

    protected string $bag = 'faq';

    protected function rules(): array
    {
        return [
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string', 'max:2000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ];
    }

    protected function attributes(): array
    {
        return [
            'question' => 'pertanyaan',
            'answer' => 'jawaban',
            'sort_order' => 'urutan',
        ];
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateForm($request);

        TpqFaq::create($this->withNextSortOrder(TpqFaq::class, $data));

        return $this->backToSection('Pertanyaan ditambahkan.');
    }

    public function update(Request $request, TpqFaq $faq): RedirectResponse
    {
        $data = $this->validateForm($request, $faq);

        $faq->update($this->withoutEmptySortOrder($data));

        return $this->backToSection('Pertanyaan diperbarui.');
    }

    public function destroy(TpqFaq $faq): RedirectResponse
    {
        $faq->delete();

        return $this->backToSection('Pertanyaan dihapus.');
    }
}
