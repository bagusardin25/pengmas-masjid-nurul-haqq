<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

/**
 * Halaman "Akun & Admin" di dashboard: profil sendiri, ganti password, dan daftar admin.
 */
class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('dashboard.akun', [
            'user' => $request->user(),
            'admins' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'Profil berhasil disimpan.');
    }
}
