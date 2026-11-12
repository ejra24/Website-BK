<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /** Batas percobaan salah sebelum dikunci sementara. */
    private const MAKS_PERCOBAAN = 5;
    private const KUNCI_DETIK = 60;

    public function proses(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nis'      => ['required', 'string', 'max:30'],
            'password' => ['required', 'string'],
        ], [
            'nis.required'      => 'NIS wajib diisi.',
            'nis.max'           => 'NIS terlalu panjang.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        // Kunci per NIS + alamat IP supaya tebakan kata sandi tidak bisa dicoba terus-menerus
        $kunci = Str::lower($data['nis']) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($kunci, self::MAKS_PERCOBAAN)) {
            $detik = RateLimiter::availableIn($kunci);

            throw ValidationException::withMessages([
                'nis' => "Terlalu banyak percobaan. Coba lagi dalam {$detik} detik.",
            ]);
        }

        // Pesan galat sengaja sama untuk NIS salah maupun sandi salah
        if (! Auth::attempt(['nis' => $data['nis'], 'password' => $data['password']], $request->boolean('ingat'))) {
            RateLimiter::hit($kunci, self::KUNCI_DETIK);

            throw ValidationException::withMessages([
                'nis' => 'NIS atau kata sandi salah.',
            ]);
        }

        RateLimiter::clear($kunci);
        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    public function keluar(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
