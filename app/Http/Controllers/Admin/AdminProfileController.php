<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminProfileController extends Controller
{
    /**
     * Show profile & account linking settings page.
     */
    public function edit(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $isGoogleConfigured = (!empty(config('services.google.client_id')) && !empty(config('services.google.client_secret'))) || config('services.google.simulation', true);

        return view('admin.profile.index', [
            'user' => $user,
            'isGoogleConfigured' => $isGoogleConfigured,
        ]);
    }

    /**
     * Update user profile information (name, email).
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
        ]);

        $user->update($validated);

        return back()->with('success', 'Informasi profil berhasil diperbarui.');
    }

    /**
     * Update or set user password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $rules = [
            'password' => ['required', 'string', 'confirmed', Password::min(8)],
        ];

        // If user already has a password, verify current password
        if ($user->hasPassword()) {
            $rules['current_password'] = ['required', 'current_password'];
        }

        $messages = [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini yang Anda masukkan salah.',
            'password.required' => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password.min' => 'Password minimal harus 8 karakter.',
        ];

        $request->validate($rules, $messages);

        $user->update([
            'password' => Hash::make($request->string('password')),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
