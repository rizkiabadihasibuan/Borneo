<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    /**
     * Tampilkan formulir login admin.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    /**
     * Proses autentikasi masuk admin dengan validasi, otorisasi peran, dan regenerasi session.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email administrator wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => 'Kredensial yang Anda berikan tidak cocok dengan data kami.',
                ]);
        }

        $user = Auth::user();

        // Verifikasi hak akses admin
        if (!$user->is_admin) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => 'Akun Anda tidak memiliki hak akses administrator.',
                ]);
        }

        // Regenerasi session ID untuk mencegah session fixation attack
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'))
            ->with('success_message', 'Selamat datang kembali di Dashboard Posko Borneo Siaga.');
    }

    /**
     * Logout admin secara aman dan invalidate session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success_message', 'Sesi administrator berhasil diakhiri dengan aman.');
    }
}
