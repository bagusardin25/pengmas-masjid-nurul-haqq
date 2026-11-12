<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Dashboard\Concerns\IndonesianValidationMessages;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Dasar untuk daftar yang dikelola di satu halaman dashboard (mis. Kelola Profil, Kelola Jadwal).
 *
 * Semua daftar tampil di satu halaman, jadi setiap form memakai error bag sendiri
 * ("{bag}-new" untuk tambah, "{bag}-{id}" untuk ubah) agar pesan error dan isian lama
 * hanya muncul di form yang dikirim.
 */
abstract class ListItemController extends Controller
{
    use IndonesianValidationMessages;

    /** Nama route halaman kelola tempat daftar ini tampil. */
    protected string $page;

    /** Id bagian di halaman kelola, dipakai sebagai anchor setelah redirect. */
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
                ->redirectTo(route($this->page).'#'.$this->section);
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
        return to_route($this->page)
            ->withFragment($this->section)
            ->with('status', $message);
    }
}
