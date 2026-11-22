<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * Pengelola dashboard. Pendaftaran umum ditutup, jadi admin baru hanya bisa ditambahkan admin lain.
 */
class AdminController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validateWithBag('admin', [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.lowercase' => 'Email harus ditulis dengan huruf kecil.',
            'email.email' => 'Email harus berupa alamat email yang valid, mis. nama@gmail.com.',
            'email.unique' => 'Email ini sudah terdaftar sebagai admin.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal :min karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
        ]);

        $admin = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Akun dibuat oleh admin yang sudah masuk, jadi emailnya dianggap terverifikasi.
        $admin->forceFill(['email_verified_at' => now()])->save();

        return to_route('profile.edit')
            ->withFragment('admin')
            ->with('status', 'Admin '.$admin->name.' ditambahkan. Berikan email dan password-nya agar bisa masuk.');
    }

    public function destroy(Request $request, User $admin): RedirectResponse
    {
        if ($admin->is($request->user())) {
            return to_route('profile.edit')->withFragment('admin')
                ->with('status', 'Anda tidak bisa menghapus akun sendiri. Minta admin lain untuk menghapusnya.');
        }

        $admin->delete();

        return to_route('profile.edit')->withFragment('admin')
            ->with('status', 'Admin '.$admin->name.' dihapus.');
    }
}
