<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /** Pesan error tampil di form profil saja, tidak di form lain di halaman yang sama. */
    protected $errorBag = 'profile';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.lowercase' => 'Email harus ditulis dengan huruf kecil.',
            'email.email' => 'Email harus berupa alamat email yang valid, mis. nama@gmail.com.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email ini sudah dipakai admin lain.',
        ];
    }
}
