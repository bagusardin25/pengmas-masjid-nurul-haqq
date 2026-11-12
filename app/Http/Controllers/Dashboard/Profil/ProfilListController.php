<?php

namespace App\Http\Controllers\Dashboard\Profil;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Dashboard\Concerns\IndonesianValidationMessages;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Dasar untuk daftar di halaman Kelola Profil (statistik, peran, fasilitas, pengurus).
 *
 * Semua daftar tampil di satu halaman, jadi setiap form memakai error bag sendiri
 * ("{bag}-new" untuk tambah, "{bag}-{id}" untuk ubah) agar pesan error dan isian lama
 * hanya muncul di form yang dikirim.
 */
abstract class ProfilListController extends Controller
{
    use IndonesianValidationMessages;

    /** Id bagian di halaman Kelola Profil, dipakai sebagai anchor setelah redirect. */
    protected string $section;

    /** Awalan nama error bag. */
    protected string $bag;

    /**
     * @return array<string, mixed>
     */
    abstract protected function rules(): array;

    /**
     * @return array<string, string>
     */
    abstract protected function attributes(): array;

    /**
     * @return array<string, mixed>
     */
    protected function validateForm(Request $request, ?Model $model = null): array
    {
        $bag = $this->bag.'-'.($model?->getKey() ?? 'new');

        $validator = Validator::make($request->all(), $this->rules(), $this->validationMessages(), $this->attributes());

        if ($validator->fails()) {
            throw (new ValidationException($validator))
                ->errorBag($bag)
                ->redirectTo(route('dashboard.profil').'#'.$this->section);
        }

        return $validator->validated();
    }

    /**
     * Isi urutan otomatis (paling akhir) saat menambah data tanpa urutan.
     *
     * @param  class-string<Model>  $modelClass
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function withNextSortOrder(string $modelClass, array $data): array
    {
        if (! isset($data['sort_order'])) {
            $data['sort_order'] = ((int) $modelClass::query()->max('sort_order')) + 1;
        }

        return $data;
    }

    /**
     * Saat mengubah data, urutan yang dikosongkan berarti tetap.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function withoutEmptySortOrder(array $data): array
    {
        if (! isset($data['sort_order'])) {
            unset($data['sort_order']);
        }

        return $data;
    }

    protected function backToSection(string $message): RedirectResponse
    {
        return to_route('dashboard.profil')
            ->withFragment($this->section)
            ->with('status', $message);
    }
}
